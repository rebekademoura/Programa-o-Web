<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PainelController extends Controller
{
    public function index()
    {
        $tipo = session()->get('tipo');

        if ($tipo === 'admin') {
            return view('produtos/admin'); // <- alterado para apontar para a pasta "produtos"
        } elseif ($tipo === 'user') {
            return view('produtos/user'); // <- alterado para apontar para a pasta "produtos"
        } else {
            return redirect()->to('/login')->with('erro', 'Acesso não autorizado.');
        }
    }
}
