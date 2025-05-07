<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAdmin implements FilterInterface {
    
    public function before(RequestInterface $request, $arguments = null) {
      //  var_dump(session()->get('usuario'));
      if(session()->get('usuario')){
        if(session()->get('usuario')['role']!=="admin")
         return redirect()->to('/login')->with('error', 'Você não tem autorização');
      }
       
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {

    }
}
