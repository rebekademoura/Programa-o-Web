<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;

class PerfilController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    protected function resolveUsuarioId(): int
    {
        // 1) Tenta obter de um campo hidden 'user_id' no POST
        $id = $this->request->getPost('user_id');
        // 2) Se não vier no POST, tenta obter como parâmetro de rota
        if (! $id) {
            $id = $this->request->getGet('user_id');
        }
        // 3) Se ainda não tiver, cai no usuário da sessão
        if (! $id) {
            $sess = session()->get('usuario');
            $id   = $sess['id'];
        }
        return (int) $id;
    }

    public function index()
    {
        // Se quiser exibir o perfil de outro usuário, use ?user_id=123
        $userId = $this->resolveUsuarioId();
        $usuario = $this->usuarioModel->find($userId);
        return view('perfil/index', ['usuario' => $usuario]);
    }

    public function update()
    {
        $userId = $this->resolveUsuarioId();

        $this->usuarioModel->update($userId, [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ]);

        // Se for o próprio usuário, atualiza sessão
        $sess = session()->get('usuario');
        if ($sess['id'] === $userId) {
            $usuario = $this->usuarioModel->find($userId);
            session()->set('usuario', $usuario);
        }

        return redirect()->back()->with('success', 'Perfil atualizado.');
    }

    public function updateSenha()
    {
        $userId = $this->resolveUsuarioId();
        $usuario = $this->usuarioModel->find($userId);

        $senhaAtual   = $this->request->getPost('senhaAtual');
        $novaSenha    = $this->request->getPost('novaSenha');
        $confirmeSenha = $this->request->getPost('confirmeSenha');

        if (! password_verify($senhaAtual, $usuario['password_hash'])) {
            return redirect()->back()->with('error', 'Senha atual inválida.');
        }
        if ($novaSenha !== $confirmeSenha) {
            return redirect()->back()->with('error', 'Confirmação de senha não coincide.');
        }

        $this->usuarioModel->update($userId, [
            'password_hash' => password_hash($novaSenha, PASSWORD_DEFAULT),
        ]);

        // Se for o próprio, atualiza sessão
        $sess = session()->get('usuario');
        if ($sess['id'] === $userId) {
            $usuario = $this->usuarioModel->find($userId);
            session()->set('usuario', $usuario);
        }

        return redirect()->back()->with('success', 'Senha atualizada.');
    }

    public function updateFoto()
    {
        $userId  = $this->resolveUsuarioId();
        $arquivo = $this->request->getFile('foto_perfil');

        if (! $arquivo->isValid() || $arquivo->hasMoved()) {
            return redirect()->back()->with('error', 'Falha no upload da imagem.');
        }

        $novoNome = $arquivo->getRandomName();
        $arquivo->move(FCPATH . 'uploads', $novoNome);

        $this->usuarioModel->update($userId, [
            'foto_perfil' => $novoNome,
        ]);

        // Se for o próprio, atualiza sessão
        $sess = session()->get('usuario');
        if ($sess['id'] === $userId) {
            $usuario = $this->usuarioModel->find($userId);
            session()->set('usuario', $usuario);
        }

        return redirect()->back()->with('success', 'Foto atualizada.');
    }
}
