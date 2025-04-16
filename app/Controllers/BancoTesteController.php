<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;


class BancoTesteController extends BaseController
{
    public function index()
    {
        $sql = "INSERT INTO usuarios (nome, email) VALUES('Fulano', 'fulano@gmail.com')";

        $db = Database::connect();
        if ($db->simpleQuery($sql)) {
            echo "Registro inserido com sucesso";
        } else {
            echo 'Erro na Conexão!';
        }
    }

    
    public function buscar()
    {
        $db = Database::connect();

        $sql = "SELECT * FROM usuarios";
       
        $resutados = $db->query($sql);

        foreach ($resutados->getResult() as $row) {
            echo $row->nome;
            echo $row->email;
        }
    }
}
