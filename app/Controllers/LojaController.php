<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdutoModel;
use App\Models\FotoProdutoModel;
use App\Models\UsuarioModel;

class LojaController extends BaseController
{
    protected $produtoModel;
    protected $fotoModel;
    protected $usuarioModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->fotoModel = new FotoProdutoModel();
        $this->usuarioModel = new UsuarioModel();
    }

     //visualização do produto para usuário comum
    public function publico()
{
    $produtoModel = new \App\Models\ProdutoModel();
    $categoriaModel = new \App\Models\CategoriasModel();

    $filtroNome = $this->request->getGet('filtroNome');
    $filtroPreco = $this->request->getGet('filtroPreco');
    $idCategoria = $this->request->getGet('id_categoria');

    $query = $produtoModel->getComCategoriaECapa(); // método já existente

    if (!empty($filtroNome)) {
        $query->like('produtos.nome', $filtroNome);
    }

    if (!empty($filtroPreco)) {
        if ($filtroPreco === 'baixo') {
            $query->where('produtos.preco <', 100);
        } elseif ($filtroPreco === 'medio') {
            $query->where('produtos.preco >=', 100)->where('produtos.preco <=', 500);
        } elseif ($filtroPreco === 'alto') {
            $query->where('produtos.preco >', 500);
        }
    }

    if (!empty($idCategoria)) {
        $query->where('produtos.id_categoria', $idCategoria);
    }

    $data = [
        'produtos' => $query->paginate(8),
        'pager' => $query->pager,
        'categorias' => $categoriaModel->findAll(),
        'filtroNome' => $filtroNome,
        'filtroPreco' => $filtroPreco,
        'idCategoria' => $idCategoria,
    ];

return view('publico/index', $data); // ← era 'publico/home'
}


    // Detalhes do produto
    public function detalhes($id)
{
    // Busca produto com informações do vendedor
    $produto = $this->produtoModel
        ->select('produtos.*, usuarios.username as nome_vendedor')
        ->join('usuarios', 'usuarios.id = produtos.usuario_id', 'left')
        ->find($id);

    if (!$produto) {
        return redirect()->to('/')->with('erro', 'Produto não encontrado');
    }

    // Busca imagens da galeria do produto (tabela fotos_produtos)
    $fotos = $this->fotoModel
        ->where('produto_id', $id)
        ->findAll();

    // Busca produtos relacionados (exceto o atual)
    $produtosRelacionados = $this->produtoModel
        ->getComCategoriaECapa()
        ->where('produtos.id !=', $produto['id'])
        ->findAll();

    return view('publico/detalhe', [
        'produto' => $produto,
        'fotos' => $fotos, // ← nome corrigido para bater com a view
        'produtos' => $produtosRelacionados
    ]);
}



    // Loja do vendedor (produtos de um usuário específico)
    public function loja($usuarioId)
    {
        $produtos = $this->produtoModel
            ->getProdutosPorUsuarioComCategoriaECapa((int) $usuarioId)
            ->findAll();

        $vendedor = $this->usuarioModel->find($usuarioId);

        if (!$vendedor) {
            return redirect()->to('/')->with('erro', 'Vendedor não encontrado');
        }

        return view('publico/loja', [
            'produtos' => $produtos,
            'vendedor' => $vendedor
        ]);
    }


}
