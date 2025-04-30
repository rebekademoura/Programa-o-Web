<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Libraries\EmailService;


class AuthController extends BaseController
{

    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }


    public function cadastrar()
    {
        return view('auth/cadastrar');
    }

    public function salvarUsuario()
    {

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' =>
            password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role' => 'user',
        ];

        $this->usuarioModel->insert($data);

        return redirect()->to('/login')->with('sucesso', 'Mensagem bonita');
    }

    //retorna a view para realizar o login
    public function login()
    {
        return view('auth/login');
    }

    //realizar o login (busca no banco)
    public function autenticar()
    {

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('senha');
        $user = $this->usuarioModel->where('email', $email)->first();



        if ($user && password_verify($password, $user['password_hash'])) {
            session()->set('logado', true);
            session()->set('usuario', $user);
           return redirect()->to('/produtos');
        } else {
          
           return redirect()->to('/login')->with('error', 'Credenciais inválidas');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function esqueciASenha()
    {
        return view('auth/esqueci_senha');
    }

    public function enviarTokenSenha()
    {

        $email = $this->request->getPost('email');

        $usuario = $this->usuarioModel->where('email', $email)->first();

        if ($usuario) {

            $token = bin2hex(random_bytes(50));

            $dataExpiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Atualizar o usuário com o token e expiração
            $this->usuarioModel->update($usuario['id'], [
                'reset_token' => $token,
                'reset_token_date' => $dataExpiracao
            ]);

            // Enviar o e-mail de redefinição (aqui apenas um exemplo simples)
            $resetLink = base_url("redefinirsenha/$token");

            $emailService = new EmailService();
            $dados = [
                'from'      => $usuario['email'],
                'fromName'      => 'Sistema - Recupere sua senha',
                'to'      => $usuario['email'],
                'subject' => 'Alteração de senha',
                'message' => 'Para trocar sua senha, clique aqui: '.$resetLink
            ];
           
           
            if ($emailService->enviar($dados)) {
                return redirect()->back()->with('success', 'E-mail enviado com sucesso.');
            } else {
              echo $emailService->debug(); // útil para testar
            //     //return redirect()->back()->with('error', 'Erro ao enviar o e-mail.');
            }

           return redirect()->to('/login')->with('success', 'Link de redefinição enviado ao seu e-mail.' . $resetLink);
        }

        return redirect()->back()->with('error', 'E-mail não encontrado.');
    }

    public function alterarSenha($token)
    {
        $usuario = $this->usuarioModel->where('reset_token', $token)
            ->where('reset_token_date >=', date('Y-m-d H:i:s'))
            ->first();

        if ($usuario) {
            return view('auth/redefinir_senha', ['token' => $token]);
        }

        return redirect()->to('/login')->with('error', 'Token inválido ou expirado.');
    }

    public function salvarSenha($token)
    {
        $usuario = $this->usuarioModel->where('reset_token', $token)
            ->where('reset_token_date >=', date('Y-m-d H:i:s'))
            ->first();

        if ($usuario) {
            $password = $this->request->getPost('password');
            $confirmPassword = $this->request->getPost('confirm_password');

            if ($password === $confirmPassword) {
                // Atualizar a senha e limpar o token
                $this->usuarioModel->update($usuario['id'], [
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'reset_token' => null,
                    'reset_token_date' => null
                ]);

                return redirect()->to('/login')->with('success', 'Senha redefinida com sucesso!');
            } else {
                return redirect()->back()->with('error', 'As senhas não coincidem.');
            }
        }

        return redirect()->to('/login')->with('error', 'Token inválido ou expirado.');
    }
}
