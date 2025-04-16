<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{

    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new \App\Models\UsuarioModel();
    }

    // Mostrar formulário de cadastro - ok
    public function cadastrar()
    {
        return view("auth/cadastrar");
    }

    // Salvar novo usuário - ok
    public function salvarUsuario()
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash(
                $this->request->getPost('password'), PASSWORD_DEFAULT
            )
        ];
        
        $this->usuarioModel->insert($data);

        return redirect()->to('/login')->with('sucesso', 'Mensagem bonita');
    }


    // Mostrar formulário de login - ok
    public function login()
    {
        return view("auth/login");
    }

    // Autenticar usuário - ok
    public function autenticar()
    {
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password_hash');

    $user = $this->usuarioModel->where('email', $email)->first();

    if ($user && password_verify($password, $user['password_hash'])) {
        session()->set('logado', true);
        session()->set('usuario', $user);
        session()->set('role', $user['role']);                                                                          
        return redirect()->to('/produtos');
    } else {
        return redirect()->to('/login')->with('erro', 'Credenciais inválidas');
    }
    }


    // Encerrar sessão - sem botão para fazer o logout
    public function logout()
    {
       session()->destroy();
       return redirect()->to('/login');
    }
    public function erros()
{
    // Se desejar mostrar uma mensagem de erro, pode definir um valor na sessão
    return view('erros', ['error' => session()->getFlashdata('error')]);
}
}
