<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsuarioModel;

class PerfilController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index()
    {
        return view('perfil/index');
    }

    public function update()
    {
        //salvar no banco de dados a atualização de nome e email
        $usuario = session()->get('usuario');
        $this->usuarioModel->update($usuario['id'],
            ['email'    => $this->request->getPost('email'),
             'username' => $this->request->getPost('username')
            ]    
        );

        //atualizar dados da sessão após alteração no banco
        $usuario = $this->usuarioModel->find($usuario['id']);

        session()->set('usuario', $usuario);

        return redirect()->back()->with('success','Perfil Atualizado');
    }

    public function updateSenha()
    {
        //salvar no banco de dados a atualização de senha
        $usuario = session()->get('usuario');

        $senhaAtual = $this->request->getPost('senhaAtual');
        $novaSenha = $this->request->getPost('novaSenha');
        $confirmeSenha = $this->request->getPost('confirmeSenha');

        //verifica se senha atual é igual a senha atual
        if(!password_verify($senhaAtual, $usuario['password_hash'])){
            return redirect()->back()->with('error', 'Senha Atual é inválida');
        }

        //verifica se a senha atual é igual a confirmação da senha atual
        if($novaSenha !== $confirmeSenha){
           return redirect()->back()->with('error', 'A senha e a confirmação da nova senha precisam ser iguais');
        }

        //atualizando a senha no banco de dados
        $this->usuarioModel->update($usuario['id'],
            [
                'password_hash' => password_hash($novaSenha, PASSWORD_DEFAULT)
            ]
        );

        //atualizando informação na sessão
        $usuario = $this->usuarioModel->find($usuario['id']);
        session()->set('usuario', $usuario);
        return redirect()->back()->with('success','Senha atualizada');


    }

    public function updateFoto(){

        $usuario = session()->get('usuario');

        $arquivo = $this->request->getFile('foto_perfil');

        if($arquivo->isValid() && !$arquivo->hasMoved()){
            //gerar novo nome para o arquivo
            $novoNome = $arquivo->getRandomName();

            //mover para uploads
            $arquivo->move(FCPATH . 'uploads', $novoNome);
            
            //atualizar no banco
            $this->usuarioModel->update($usuario['id'],[
                'foto_perfil' => $novoNome
            ]);

            //atualiza a sessão
            $usuario = $this->usuarioModel->find($usuario['id']);
            session()->set('usuario', $usuario);
            return redirect()->back()->with('success','Foto de perfil atualizada');
        }
        else{
            return redirect()->back()->with('success','Deu problema ao fazer o upload da sua imagem');
        }


    }

}
