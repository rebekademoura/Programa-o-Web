<?php
// Controller: app/Controllers/DashboardController.php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdutoModel;
use App\Models\UsuarioModel;
use App\Models\CategoriasModel;

class DashboardController extends BaseController
{
    protected $produtoModel;
    protected $usuarioModel;
    protected $categoriaModel;

    public function __construct()
    {
        $this->produtoModel    = new ProdutoModel();
        $this->usuarioModel    = new UsuarioModel();
        $this->categoriaModel  = new CategoriasModel();
    }

    public function index()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        // Produtos pendentes
        $produtos   = $this->produtoModel->where('status', 'reprovado')->findAll();
        // Usuários
        $users      = $this->usuarioModel->findAll();
        // Categorias
        $categorias = $this->categoriaModel->findAll();

        return view('dashboard/dashboardAdmin', compact('produtos', 'users', 'categorias'));
    }

    public function approve($id)
    {
        $this->produtoModel->update($id, ['status' => 'aprovado']);
        return redirect()->back()->with('success', 'Produto aprovado com sucesso.');
    }

    public function reject($id)
    {
        $this->produtoModel->update($id, ['status' => 'reprovado']);
        return redirect()->back()->with('success', 'Produto reprovado com sucesso.');
    }

    public function deleteUser($id)
{
    // Busca o usuário
    $user = $this->usuarioModel->find($id);
    if (! $user) {
        return redirect()->back()->with('error', 'Usuário não encontrado.');
    }

    // Impede exclusão do admin_geral
    if ($user['role'] === 'admin_geral') {
        return redirect()->back()->with('error', 'Não é possível excluir este usuário.');
    }

    // Exclui os demais
    $this->usuarioModel->delete($id);
    return redirect()->back()->with('success', 'Usuário excluído com sucesso.');
}

}
