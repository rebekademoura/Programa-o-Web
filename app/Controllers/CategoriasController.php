<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CategoriasModel;

class CategoriasController extends Controller
{
    public $categoriasModel;

    public function __construct()
    {
        $this->categoriasModel = new CategoriasModel();
    }

    
    public function index()
    {
        $data['categorias'] = $this->categoriasModel->findAll();
        return view('categorias/index', $data);    }

    public function create()
    {
        return view('categorias/create');
    }

    
    public function store()
    {
        $this->categoriasModel->save([
            'nome'      => $this->request->getPost('nome'),
        ]);
        return redirect()->to('/categorias');
    }


    public function edit($id)
    {
        $data['categorias'] = $this->categoriasModel->find($id);
        return view('categorias/edit', $data);
    }


    public function update($id)
    {

        $this->categoriasModel->update($id, [
            'nome' => $this->request->getPost('nome')
        ]);
        return redirect()->to('/categorias');
    }

    public function delete($id)
    {
        $this->categoriasModel->delete($id);
        return redirect()->to('/categorias');
    }
}

