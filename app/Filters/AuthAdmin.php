<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAdmin implements FilterInterface {
    
    public function before(RequestInterface $request, $arguments = null) { 
        
        if (!session()->get('logado')) {
            return redirect()->to('/login')->with('error', 'Você não tem autorização');
        }
        

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/erros')->with('error', 'Você não tem permissão de administrador');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {

    }
}
