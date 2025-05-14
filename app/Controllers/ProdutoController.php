<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProdutoModel;
use App\Models\CategoriasModel;


class ProdutoController extends Controller
{

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


    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->categoriaModel = new CategoriasModel();
    }

    
    
    public function index()
    {        
        $busca = $this->request->getGet('busca');
        $preco = $this->request->getGet('preco');

        $query = $this->produtoModel->getComCategoriaECapa($busca, $preco);

        if (!empty($busca)) {
            $query->like('produtos.nome', $busca);
        }
    
        if (!empty($preco)) {
            if ($preco === 'baixo') {
                $this->produtoModel->where('produtos.preco <', 100);
            } elseif ($preco === 'medio') {
                $this->produtoModel->where('produtos.preco >=', 100)->where('produtos.preco <=', 500);
            } elseif ($preco === 'alto') {
                $this->produtoModel->where('produtos.preco >', 500);
            }
        }
    
        $data = [
            'produtos' => $query->paginate(5, 'default'),
            'pager'    => $query->pager,
            'busca'    => $busca,
            'preco'    => $preco,
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
        $this->produtoModel->save([
            'nome'          => $this->request->getPost('nome'),
            'descricao'     => $this->request->getPost('descricao'),
            'preco'         => $this->request->getPost('preco'),
            'id_categoria'  => $this->request->getPost('id_categoria'),
        ]);
        return redirect()->to('/produtos');
    }


    public function edit($id)
    {
        
        $data['produto'] = $this->produtoModel->find($id);
        $data['categorias'] = $this->categoriaModel->findAll();
        return view('produtos/edit', $data);
    }



    public function update($id)
    {
        $this->produtoModel->update($id, [
            'nome'      => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'preco'     => $this->request->getPost('preco'),
            'categoria_id'=> $this->request->getPost('categoria_id')
        ]);
        return redirect()->to('/produtos');
    }

    public function delete($id)
    {
        $this->produtoModel->delete($id);
        return redirect()->to('/produtos');
    }





}

