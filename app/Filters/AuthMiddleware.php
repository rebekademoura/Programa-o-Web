<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthMiddleware implements FilterInterface {
    
    public function before(RequestInterface $request, $arguments = null) {
        if (!session()->get('logado')) {
            return redirect()->to('/erros')->with('error', 'Você não tem autorização');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
    }
}
