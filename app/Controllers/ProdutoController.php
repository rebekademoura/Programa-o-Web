<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProdutoModel;
use App\Models\CategoriasModel;
use App\Models\FotoProdutoModel;
use App\Models\UsuarioModel;


class ProdutoController extends Controller
{

    protected $fotoProdutoModel;


    // Dentro da classe ProdutoController
public function setProdutoModel($produtoModel)
{
    $this->produtoModel = $produtoModel;
}

public function setCategoriaModel($categoriaModel)
{
    $this->categoriaModel = $categoriaModel;
}

    public $produtoModel;
    public $categoriaModel;
    public $usuarioModel;


    public function __construct()
    {
        $this->produtoModel   = new ProdutoModel();
        $this->categoriaModel = new CategoriasModel();
        $this->usuarioModel   = new UsuarioModel();
    }

    
    
    public function index()
{        
    $usuarioId = session()->get('usuario_id');
    $busca = $this->request->getGet('filtroNome'); // corresponde ao name do input
    $preco = $this->request->getGet('filtroPreco');
    $idCategoria = $this->request->getGet('id_categoria');

    $query = $this->produtoModel->getProdutosPorUsuarioComCategoriaECapa($usuarioId);

    if (!empty($busca)) {
        $query->like('produtos.nome', $busca);
    }

    if (!empty($preco)) {
        if ($preco === 'baixo') {
            $query->where('produtos.preco <', 100);
        } elseif ($preco === 'medio') {
            $query->where('produtos.preco >=', 100)->where('produtos.preco <=', 500);
        } elseif ($preco === 'alto') {
            $query->where('produtos.preco >', 500);
        }
    }

    if (!empty($idCategoria)) {
        $query->where('produtos.id_categoria', $idCategoria);
    }


    $data = [
        'produtos'   => $query->paginate(5, 'default'),
        'pager'      => $query->pager,
        'filtroNome' => $busca,
        'preco'      => $preco,
        'categorias' => $this->categoriaModel->findAll(),
    ];

    return view('produtos/index', $data);
}


    public function create()
    {
        $data['categorias'] = $this->categoriaModel->findAll();

        return view('produtos/create', $data);
    }


    
    public function store()
    {

        $usuarioId = session()->get('usuario_id');

        $this->produtoModel->save([
            'usuario_id'  => $usuarioId,
            'nome'          => $this->request->getPost('nome'),
            'descricao'     => $this->request->getPost('descricao'),
            'preco'         => $this->request->getPost('preco'),
            'id_categoria'  => $this->request->getPost('id_categoria'),
        ]);
        return redirect()->to('/produtos');
    }


    public function edit($id)
    {
        $produto = $this->produtoModel->find($id);
        $usuarioId = session()->get('usuario_id');
        
        if ($produto['usuario_id'] == $usuarioId) {
            $data['produto'] = $this->produtoModel->find($id);
            $data['categorias'] = $this->categoriaModel->findAll();
            return view('produtos/edit', $data);
        }else{
            return redirect()->back()->with('error','Você não está autorizado a editar este produto');
        }     
    }

    public function update($id)
{
    $usuarioId = session()->get('usuario_id');
    $produto = $this->produtoModel->find($id);

    if (!$produto) {
        return redirect()->back()->with('error', 'Produto não encontrado.');
    }

    if ($produto['usuario_id'] == $usuarioId) {
        $this->produtoModel->update($id, [
        'nome'         => $this->request->getPost('nome'),
        'descricao'    => $this->request->getPost('descricao'),
        'preco'        => $this->request->getPost('preco'),
        'categoria_id' => $this->request->getPost('categoria_id')
    ]);

    return redirect()->to('/produtos')->with('success', 'Produto atualizado com sucesso.');
    }else{
        return redirect()->back()->with('error', 'Você não está autorizado a editar este produto.');

    }

    
}


    public function delete($id)
    {
        
        $fotoModel = new FotoProdutoModel();
        $produtoModel = new ProdutoModel();
        $produto = $this->produtoModel->find($id);
        $usuarioId = session()->get('usuario_id');

        if ($produto['usuario_id'] == $usuarioId) {
            $fotos = $fotoModel->where('produto_id', $id)->findAll();
            foreach ($fotos as $foto) {
                $arquivo = FCPATH . 'uploads/fotos/' . $foto['caminho'];
            if (file_exists($arquivo)) {
                unlink($arquivo);
            }
        }

        $fotoModel->where('produto_id', $id)->delete();

        $produtoModel->delete($id);

        return redirect()->to('/produtos');
        }else{
            return redirect()->back()->with('error','Você não pode deletar este produto');
        } 
        
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

}

