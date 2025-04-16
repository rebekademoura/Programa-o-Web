<?php

namespace App\Controllers;
use Config\Database;
use App\Models\ContatoModel;


class HomeController extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function sobre():string{
        return view('sobre');
    }

    public function contato():string{
        return view('contato');
    }

    public function submitContact()
    {
        $model = new ContatoModel();
        $db = Database::connect();


        $request = service('request');


        $rules = [
            'name'    => 'required|min_length[3]',
            'email'   => 'required|valid_email',
            'message' => 'required|min_length[10]'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dados = [
            'nome'       => $this->request->getPost('name') ?? '',  // Evita valores NULL
            'email'      => $this->request->getPost('email') ?? '',
            'telefone' => $this->request->getPost('telefone')?? '',
            'mensagem'   => $this->request->getPost('message') ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($model->save($dados)) {
            return redirect()->back()->with('success', 'Mensagem enviada com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Erro ao salvar a mensagem.');
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

    public function Listar(){
        $db = Database::connect();

        $sql = "SELECT * FROM contatos";

        $resultado = $db->query($sql);

        /*
        foreach($resultado->getResult() as $linha){
            echo $linha->nome;
            echo $linha->email;
            echo $linha->mensagem;
        }*/

        return view('listarBanco', ['mensagens'=>$resultado->getResult()]);

    }

}
