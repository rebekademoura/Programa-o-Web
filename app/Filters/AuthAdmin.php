<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAdmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user    = $session->get('usuario');
        $role    = $user['role'] ?? null;
        $path    = $request->getUri()->getPath();

        // 1) Rotas públicas livres (para não logados)
        $publicRoutes = [
            '',                        // "/"
            'produtos/detalhes/.*',    // detalhe
            'publico/lojaVendedor/.*', // loja do vendedor
        ];
        foreach ($publicRoutes as $route) {
            if (preg_match('#^' . $route . '$#', $path)) {
                return; // deixa passar
            }
        }

        // 2) Se não estiver logado, manda para login
        if (! $session->get('logado')) {
            return redirect()->to('/login')->with('error', 'Faça login para continuar.');
        }

        // 3) Se for admin ou admin_geral, passa em tudo
        if (in_array($role, ['admin', 'admin_geral'])) {
            return;
        }

        // 4) Se for user, só rotas permitidas
        $userAllowed = [
            'perfil',                  // ver edição de perfil
            'perfil/update',
            'perfil/updateSenha',
            'perfil/updateFoto',
            'loja/carrinho',
            'loja/adicionarAoCarrinho/.*',
            'loja/removerDoCarrinho/.*',
            'loja/removerSelecionados',
            'loja/finalizarCompra',
            'publico/lojaVendedor/.*',
        ];
        foreach ($userAllowed as $route) {
            if (preg_match('#^' . $route . '$#', $path)) {
                return; // rota permitida para user
            }
        }

        // 5) Caso contrário (user tentando acessar algo restrito)
        return redirect()->to('/')->with('error', 'Você não tem autorização para esta página.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // sem pós-processamento
    }
}
