<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ProdutoModel;

class ProdutoController extends Controller
{
    protected $produtoModel;

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
    }

    
    public function index()
    {
        //recebe string de nome e preco do formulário
        $filtroNome = $this->request->getGet('filtroNome');
        $filtroPreco = $this->request->getGet('filtroPreco');

        $query = $this->produtoModel;
        
        if(!empty($filtroNome)){
            $query = $query->like('nome', $filtroNome);
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
        return view('produtos/create');
    }

    
    public function store()
    {
        $this->produtoModel->save([
            'nome'      => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'preco'     => $this->request->getPost('preco'),
        ]);
        return redirect()->to('/produtos');
    }


    public function edit($id)
    {
        $data['produto'] = $this->produtoModel->find($id);
        return view('produtos/edit', $data);
    }


    public function update($id)
    {

        $this->produtoModel->update($id, [
            'nome'      => $this->request->getPost('nome'),
            'descricao' => $this->request->getPost('descricao'),
            'preco'     => $this->request->getPost('preco'),
        ]);
        return redirect()->to('/produtos');
    }

    public function delete($id)
    {
        $this->produtoModel->delete($id);
        return redirect()->to('/produtos');
    }





}

