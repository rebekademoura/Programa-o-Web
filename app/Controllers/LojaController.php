<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdutoModel;
use App\Models\FotoProdutoModel;
use App\Models\UsuarioModel;
use App\Models\CarrinhoModel;
use App\Models\VendaModel;
use CodeIgniter\Controller;
use RuntimeException;

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
        $this->resendApiKey   = getenv('RESEND_API_KEY'); // Defina no .env
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

        if ($filtroNome) {
            $query->like('produtos.nome', $filtroNome);
        }
        if ($filtroPreco) {
            switch ($filtroPreco) {
                case 'baixo':
                    $query->where('produtos.preco <', 100);
                    break;
                case 'medio':
                    $query->where('produtos.preco >=', 100)
                          ->where('produtos.preco <=', 500);
                    break;
                default:
                    $query->where('produtos.preco >', 500);
            }
        }
        if ($idCategoria) {
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
            return redirect()->to('/')->with('error', 'Produto não encontrado ou não aprovado');
        }

        $fotos = $this->fotoModel->where('produto_id', $id)->findAll();
        $relacionados = $this->produtoModel
            ->getComCategoriaECapa()
            ->where('produtos.status', 'aprovado')
            ->where('produtos.id !=', $produto['id'])
            ->findAll();

        $vendedor = $this->usuarioModel->find($produto['usuario_id']);

        return view('publico/detalhe', [
            'produto'  => $produto,
            'fotos'    => $fotos,
            'produtos' => $relacionados,
            'vendedor' => $vendedor,
        ]);
    }

    // 3) Loja de um vendedor
    public function lojaVendedor($usuarioId)
    {
        $produtos = $this->produtoModel
            ->getProdutosPorUsuarioComCategoriaECapa((int) $usuarioId)
            ->where('produtos.status', 'aprovado')
            ->findAll();

        $vendedor = $this->usuarioModel->find($usuarioId);
        if (! $vendedor) {
            return redirect()->to('/')->with('error', 'Vendedor não encontrado');
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
        if (! $usuarioId) {
            return redirect()->to('/login')
                             ->with('error', 'Você precisa estar logado para adicionar produtos ao carrinho');
        }

        if (! $produtoId) {
            return redirect()->back()->with('error', 'ID do produto não informado');
        }

        $produto = $this->produtoModel->find($produtoId);
        if (! $produto) {
            return redirect()->back()->with('error', 'Produto não encontrado');
        }

        $quantidade     = (int) $this->request->getPost('quantidade');
        $formaPagamento = $this->request->getPost('forma_pagamento') ?? 'cartao';
        $parcelas       = (int) $this->request->getPost('parcelas');

        if ($formaPagamento === 'pix') {
            $parcelas = 1;
        }

        if ($quantidade < 1) {
            return redirect()->back()->with('error', 'Quantidade inválida. Deve ser ao menos 1.');
        }
        if ($formaPagamento === 'cartao' && ($parcelas < 1 || $parcelas > 12)) {
            return redirect()->back()->with('error', 'Parcelas inválidas. Escolha entre 1 e 12.');
        }
        if ($produto['estoque'] < $quantidade) {
            return redirect()->back()->with('error', "Estoque insuficiente. Apenas {$produto['estoque']} disponível(is).");
        }

        $this->produtoModel->update($produtoId, ['estoque' => $produto['estoque'] - $quantidade]);

        $this->carrinhoModel->insert([
            'usuario_id'      => $usuarioId,
            'produto_id'      => $produtoId,
            'quantidade'      => $quantidade,
            'adicionado_em'   => date('Y-m-d H:i:s'),
            'forma_pagamento' => $formaPagamento,
            'parcelas'        => $parcelas,
        ]);

        return redirect()->to('/')->with('success', 'Produto adicionado ao carrinho!');
    }

    // 5) Exibe itens do carrinho
    public function carrinho()
    {
        $usuarioId = session()->get('usuario_id');
        if (! $usuarioId) {
            return redirect()->to('/login')->with('error', 'Você precisa estar logado para ver o carrinho.');
        }

        $itens = $this->carrinhoModel->where('usuario_id', $usuarioId)->findAll();
        foreach ($itens as &$item) {
            $produto = $this->produtoModel->find($item['produto_id']);
            $base    = $produto['preco'] * $item['quantidade'];
            $item['produto']     = $produto;
            $item['preco_total'] = $item['parcelas'] > 1 ? $base * 1.03 : $base;
        }

        return view('publico/carrinho', ['itens' => $itens]);
    }

    // 6) Remove um item
    public function removerDoCarrinho($id)
    {
        $this->carrinhoModel->delete($id);
        return redirect()->back()->with('success', 'Item removido do carrinho.');
    }

    // 7) Remove itens selecionados
    public function removerSelecionados()
    {
        $selecionados = $this->request->getPost('selecionados') ?? [];
        foreach ($selecionados as $id) {
            $this->carrinhoModel->delete((int) $id);
        }
        return redirect()->back()->with('success', 'Itens selecionados removidos!');
    }

    // 8) envio via Resend API, acumulando logs para console, com debug da chave
    protected function sendResendEmail(string $to, string $subject, string $html): void
{
    // 1) Debug da chave
    $this->logs[] = "[Debug] RESEND_API_KEY: " . ($this->resendApiKey ?: 'NULL');

    if (empty($this->resendApiKey)) {
        $this->logs[] = "[Error] Chave da API Resend não configurada.";
        return;
    }

    // 2) Monta payload
    $fromEmail = 'onboarding@resend.dev';
    $payload = json_encode([
        'from'    => $fromEmail,
        'to'      => [$to],
        'subject' => $subject,
        'html'    => $html,
    ], JSON_UNESCAPED_UNICODE);
    $this->logs[] = "[Resend] Payload JSON: {$payload}";

    // 3) Inicializa CURL
    $ch = curl_init('https://api.resend.com/emails');
    if (! $ch) {
        $this->logs[] = "[Error] curl_init retornou false.";
        return;
    }
    $this->logs[] = "[Resend] curl_init OK";

    // 4) Configurações de CURL
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            "Authorization: Bearer {$this->resendApiKey}",
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS     => $payload,
    ]);
    $this->logs[] = "[Resend] curl_setopt_array configurado";

    // 5) Executa e captura resposta/erro
    $response = curl_exec($ch);
    $curlErr  = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $this->logs[] = "[Resend] curl_exec resposta raw: " . var_export($response, true);
    if ($curlErr) {
        $this->logs[] = "[Resend] curl_error: {$curlErr}";
    }
    $this->logs[] = "[Resend] HTTP status code: {$httpCode}";

    // 6) Interpretação do código HTTP
    if ($response === false) {
        $this->logs[] = "[Resend] Execução falhou.";
    } elseif ($httpCode < 200 || $httpCode >= 300) {
        $this->logs[] = "[Resend] resposta HTTP não OK ({$httpCode}): {$response}";
    } else {
        $this->logs[] = "[Resend] E-mail enviado com sucesso. HTTP {$httpCode}";
    }

    curl_close($ch);
}


    // 9) finalizar compra, registrar vendas, enviar e-mail apenas ao comprador e preparar logs
    public function finalizarCompra()
{
    $uid = session()->get('usuario_id');
    $this->logs[] = "[Loja] iniciar finalizarCompra para user: {$uid}";
    if (! $uid) {
        session()->setFlashdata('logs', $this->logs);
        return redirect()->to('/login')->with('error','Login requerido');
    }

    // Recebe forma e parcelas do POST
    $formaPagamento = $this->request->getPost('forma_pagamento');
    $parcelas       = (int) $this->request->getPost('parcelas');
    if ($formaPagamento === 'pix') {
        $parcelas = 1;
    }

    $itens = $this->carrinhoModel->where('usuario_id', $uid)->findAll();
    $this->logs[] = "[Loja] itens no carrinho: " . count($itens);
    if (empty($itens)) {
        session()->setFlashdata('logs', $this->logs);
        return redirect()->back()->with('error','Carrinho vazio');
    }

    $comprador = $this->usuarioModel->find($uid);
    $this->logs[] = "[Loja] comprador: {$comprador['email']}";
    $descricao = [];
    $total     = 0;

    foreach ($itens as $item) {
        $p   = $this->produtoModel->find($item['produto_id']);
        $sub = $p['preco'] * $item['quantidade'] * ($parcelas > 1 ? 1.03 : 1);
        $descricao[] = "{$p['nome']} x{$item['quantidade']} => R$ " . number_format($sub,2,',','.');
        $total += $sub;

        $this->logs[] = "[Loja] registrando venda compr={$uid}, vend={$p['usuario_id']}, prod={$p['id']}, val={$sub}";
        $this->vendaModel->insert([
            'usuario_comprador_id'=> $uid,
            'usuario_vendedor_id' => $p['usuario_id'],
            'produto_id'          => $p['id'],
            'quantidade'          => $item['quantidade'],
            'valor_total'         => $sub,
            'forma_pagamento'     => $formaPagamento,
            'parcelas'            => $parcelas,
            'criado_em'           => date('Y-m-d H:i:s'),
        ]);
    }

    // Envia email apenas ao comprador
    $this->logs[] = '[Loja] enviando email comprador';
    $htmlCompr = '<h1>Obrigado, '.$comprador['username'].'</h1>'
      . '<ul><li>'.implode('</li><li>',$descricao).'</li></ul>';
    $this->sendResendEmail($comprador['email'], 'Compra Confirmada', $htmlCompr);

    // Limpa carrinho
    $this->carrinhoModel->where('usuario_id', $uid)->delete();
    $this->logs[] = '[Loja] carrinho limpo';
    $this->logs[] = '[Loja] finalizarCompra OK';

    session()->setFlashdata('logs', $this->logs);
    return redirect()->to('loja/carrinho')->with('success','Compra concluída');
}


}
