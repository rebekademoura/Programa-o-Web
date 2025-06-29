<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdutoModel;
use App\Models\FotoProdutoModel;
use App\Models\UsuarioModel;
use App\Models\CarrinhoModel;
use App\Models\VendaModel;


class LojaController extends BaseController
{
    protected $produtoModel;
    protected $fotoModel;
    protected $usuarioModel;
    protected $carrinhoModel;
    protected $vendaModel;
        protected $resendApiKey;


    public function __construct()
    {
        $this->produtoModel   = new ProdutoModel();
        $this->fotoModel      = new FotoProdutoModel();
        $this->usuarioModel   = new UsuarioModel();
        $this->carrinhoModel  = new CarrinhoModel();
        $this->vendaModel     = new VendaModel();
                $this->resendApiKey   = getenv('RESEND_API_KEY'); // coloque sua chave no .env

    }

    // 1) Lista pública de produtos
     public function publico()
    {
        $categoriaModel = new \App\Models\CategoriasModel();

        $filtroNome   = $this->request->getGet('filtroNome');
        $filtroPreco  = $this->request->getGet('filtroPreco');
        $idCategoria  = $this->request->getGet('id_categoria');

        $query = $this->produtoModel->getComCategoriaECapa()
                    ->where('produtos.status', 'aprovado');

        if (! empty($filtroNome)) {
            $query->like('produtos.nome', $filtroNome);
        }
        if (! empty($filtroPreco)) {
            if ($filtroPreco === 'baixo') {
                $query->where('produtos.preco <', 100);
            } elseif ($filtroPreco === 'medio') {
                $query->where('produtos.preco >=', 100)
                      ->where('produtos.preco <=', 500);
            } else {
                $query->where('produtos.preco >', 500);
            }
        }
        if (! empty($idCategoria)) {
            $query->where('produtos.id_categoria', $idCategoria);
        }

        return view('publico/index', [
            'produtos'   => $query->paginate(8),
            'pager'      => $query->pager,
            'categorias' => $categoriaModel->findAll(),
            'filtroNome' => $filtroNome,
            'filtroPreco'=> $filtroPreco,
            'idCategoria'=> $idCategoria,
        ]);
    }

    // 2) Detalhes de um produto
    public function detalhes($id)
{
    $produto = $this->produtoModel
        ->select('produtos.*, usuarios.username as nome_vendedor')
        ->join('usuarios', 'usuarios.id = produtos.usuario_id', 'left')
        ->where('produtos.status', 'aprovado')
        ->find($id);

    if (! $produto) {
        return redirect()->to('/')->with('erro', 'Produto não encontrado ou não aprovado');
    }

    $fotos = $this->fotoModel->where('produto_id', $id)->findAll();
    $relacionados = $this->produtoModel
        ->getComCategoriaECapa()
        ->where('produtos.status', 'aprovado')
        ->where('produtos.id !=', $produto['id'])
        ->findAll();

    // <<< Aqui buscamos o vendedor e o enviamos à view
    $vendedor = $this->usuarioModel->find($produto['usuario_id']);

    return view('publico/detalhe', [
        'produto'     => $produto,
        'fotos'       => $fotos,
        'produtos'    => $relacionados,
        'vendedor'    => $vendedor,   // *** não esqueça dessa linha ***
    ]);
}


    // 3) Loja de um vendedor
    public function lojaVendedor($usuarioId)    {
        $produtos = $this->produtoModel
            ->getProdutosPorUsuarioComCategoriaECapa((int) $usuarioId)
            ->where('produtos.status', 'aprovado')
            ->findAll();

        $vendedor = $this->usuarioModel->find($usuarioId);
        if (! $vendedor) {
            return redirect()->to('/')->with('erro', 'Vendedor não encontrado');
        }

        return view('publico/loja', [
            'produtos' => $produtos,
            'vendedor' => $vendedor,
        ]);
    }


    // 4) Adiciona ao carrinho
    public function adicionarAoCarrinho($produtoId = null)
    {
        $usuarioId = session()->get('usuario_id');

        // Se não estiver logado, redireciona para login
        if (!$usuarioId) {
            return redirect()->to('/login')
                             ->with('error', 'Você precisa estar logado para adicionar produtos ao carrinho');
        }

        // Valida ID do produto
        if (!$produtoId) {
            return redirect()->back()->with('error', 'ID do produto não informado');
        }

        // Busca o produto
        $produto = $this->produtoModel->find($produtoId);
        if (!$produto) {
            return redirect()->back()->with('error', 'Produto não encontrado');
        }

        // Captura dados do form
        $quantidade     = (int) $this->request->getPost('quantidade');
        $formaPagamento = $this->request->getPost('forma_pagamento') ?? 'cartao';
        $parcelas       = (int) $this->request->getPost('parcelas');

        // Se Pix, força uma parcela apenas
        if ($formaPagamento === 'pix') {
            $parcelas = 1;
        }

        // Validações
        if ($quantidade < 1) {
            return redirect()->back()->with('error', 'Quantidade inválida. Deve ser ao menos 1.');
        }
        if ($formaPagamento === 'cartao' && ($parcelas < 1 || $parcelas > 12)) {
            return redirect()->back()->with('error', 'Número de parcelas inválido. Escolha entre 1 e 12.');
        }
        if ($produto['estoque'] < $quantidade) {
            return redirect()->back()->with('error', "Estoque insuficiente. Há apenas {$produto['estoque']} unidade(s) disponível(is).");
        }

        // Decrementa estoque
        $this->produtoModel->update(
            $produtoId,
            ['estoque' => $produto['estoque'] - $quantidade]
        );

        // Insere no carrinho
        $this->carrinhoModel->insert([
            'usuario_id'      => $usuarioId,
            'produto_id'      => $produtoId,
            'quantidade'      => $quantidade,
            'adicionado_em'   => date('Y-m-d H:i:s'),
            'forma_pagamento' => $formaPagamento,
            'parcelas'        => $parcelas,
        ]);

        return redirect()->to('/')
                         ->with('success', 'Produto adicionado ao carrinho com sucesso!');
    }

    // 5) Exibe itens do carrinho
    public function carrinho()
    {
        $usuarioId = session()->get('usuario_id');
        if (! $usuarioId) {
            return redirect()->to('/login')
                             ->with('error', 'Você precisa estar logado para ver o carrinho.');
        }

        $itens = $this->carrinhoModel
                      ->where('usuario_id', $usuarioId)
                      ->findAll();

        foreach ($itens as &$item) {
            $produto         = $this->produtoModel->find($item['produto_id']);
            $item['produto'] = $produto;
            $base            = $produto['preco'] * $item['quantidade'];
            $item['preco_total'] = ($item['parcelas'] > 1)
                ? $base * 1.03
                : $base;
        }

        return view('publico/carrinho', ['itens' => $itens]);
    }

    // 6) Remove um item
    public function removerDoCarrinho($id)
    {
        $this->carrinhoModel->delete($id);
        return redirect()->back()
                         ->with('success', 'Item removido do carrinho.');
    }

    // 7) Remove itens selecionados
    public function removerSelecionados()
    {
        $selecionados = $this->request->getPost('selecionados') ?? [];
        foreach ($selecionados as $id) {
            $this->carrinhoModel->delete((int) $id);
        }
        return redirect()->back()
                         ->with('success', 'Itens selecionados removidos com sucesso.');
    }

    // 8) Finaliza a compra (envia e-mails via Resend API e limpa o carrinho)
    protected function sendResendEmail(string $to, string $subject, string $html): void
    {
        $payload = json_encode([
            'from'    => 'no-reply@sua-loja.com',
            'to'      => $to,
            'subject' => $subject,
            'html'    => $html,
        ]);

        $ch = curl_init('https://api.resend.com/emails');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->resendApiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS     => $payload,
        ]);
        $response = curl_exec($ch);
        if ($response === false) {
            log_message('error', 'Resend cURL error: '.curl_error($ch));
        }
        curl_close($ch);
    }

    public function finalizarCompra()
    {
        $usuarioId = session()->get('usuario_id');
        if (! $usuarioId) {
            return redirect()->to('/login')
                             ->with('error', 'Você precisa estar logado para finalizar a compra.');
        }

        $itens = $this->carrinhoModel
                      ->where('usuario_id', $usuarioId)
                      ->findAll();

        if (empty($itens)) {
            return redirect()->back()
                             ->with('error', 'Seu carrinho está vazio.');
        }

        // 1) Prepara dados do comprador
        $comprador = $this->usuarioModel->find($usuarioId);
        $descricaoComprador = [];
        $totalComprador     = 0;

        // agrupar itens por vendedor
        $itensPorVendedor = [];

        foreach ($itens as $item) {
            $p      = $this->produtoModel->find($item['produto_id']);
            $qtde   = $item['quantidade'];
            $juros  = $item['parcelas'] > 1 ? 1.03 : 1;
            $sub    = $p['preco'] * $qtde * $juros;

            $descricaoComprador[] = "{$p['nome']} x{$qtde} — R$ " . number_format($sub, 2, ',', '.');
            $totalComprador      += $sub;
            $itensPorVendedor[$p['usuario_id']][] = "{$p['nome']} x{$qtde} ({$item['parcelas']}x)";
        }

        // 2) Envia ao COMPRADOR
        $htmlComprador = "
            <h1>Obrigado pela sua compra, {$comprador['username']}!</h1>
            <p>Você adquiriu:</p>
            <ul><li>" . implode("</li><li>", $descricaoComprador) . "</li></ul>
            <p><strong>Total:</strong> R$ " . number_format($totalComprador, 2, ',', '.') . "</p>
        ";
        $this->sendResendEmail(
            $comprador['email'],
            'Compra confirmada com sucesso!',
            $htmlComprador
        );

        // 3) Envia a cada VENDEDOR
        foreach ($itensPorVendedor as $vendedorId => $lista) {
            $v = $this->usuarioModel->find($vendedorId);
            $htmlVendedor = "
                <h1>Olá {$v['username']}, você recebeu um novo pedido!</h1>
                <p>Detalhes:</p>
                <ul><li>" . implode("</li><li>", $lista) . "</li></ul>
            ";
            $this->sendResendEmail(
                $v['email'],
                'Você recebeu um novo pedido!',
                $htmlVendedor
            );
        }

        // 4) Limpa o carrinho
        $this->carrinhoModel
             ->where('usuario_id', $usuarioId)
             ->delete();


         foreach ($itens as $item) {
        $p      = $this->produtoModel->find($item['produto_id']);
        $qtde   = $item['quantidade'];
        $juros  = $item['parcelas'] > 1 ? 1.03 : 1;
        $sub    = $p['preco'] * $qtde * $juros;

        // Registra venda:
        $this->vendaModel->insert([
            'usuario_comprador_id' => $usuarioId,
            'usuario_vendedor_id'  => $p['usuario_id'],
            'produto_id'           => $p['id'],
            'quantidade'           => $qtde,
            'valor_total'          => $sub,
            'forma_pagamento'      => $item['forma_pagamento'],
            'parcelas'             => $item['parcelas'],
            'criado_em'            => date('Y-m-d H:i:s'),
        ]);
    }


        return redirect()->to('loja/carrinho')
                         ->with('success', 'Compra finalizada e e-mails enviados pelo Resend.');
    }

}
