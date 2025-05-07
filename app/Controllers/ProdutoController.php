<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProdutoModel;
use App\Models\CategoriasModel;


class ProdutoController extends Controller
{
    protected $produtoModel;
    protected $categoriaModel;


    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
        $this->categoriaModel = new CategoriasModel();
    }

    
    
    public function index()
    {
        //recebe string de nome e preco do formulário
        $filtroNome = $this->request->getGet('filtroNome');
        $filtroPreco = $this->request->getGet('filtroPreco');

        $query = $this->produtoModel
        ->select('produtos.*, categorias.nome as nome_categoria')
        ->join('categorias', 'categorias.id = produtos.id_categoria', 'left');
    
        if(!empty($filtroNome)){
            $query->like('produtos.nome', $filtroNome); 
        }


        if(!empty($filtroPreco)){
            if($filtroPreco=='baixo'){
                $query = $query->where(' preco < 100');
            }
            if($filtroPreco=='medio'){
                $query = $query->where('preco > 100')->where('preco < 500');
            }
            if($filtroPreco=='alto'){
                $query = $query->where('preco > 500');
            }
            

        }

        $data = [
            'produtos' => $query->paginate(10),
            'pager' => $query->pager,
            'busca' => $filtroNome,
            'preco' => $filtroPreco
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

