<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\FotoProdutoModel;
use App\Models\ProdutoModel;


class FotoProdutoController extends BaseController
{

    protected $fotoProdutoModel;

    public function __construct()
    {
        $this->fotoProdutoModel = new fotoProdutoModel();

    }

    public function index($produtoId){
        //busca as fotos relacionadas ao produto (pelo id do produto passado pela URL)
        $fotos = $this->fotoProdutoModel->ListarPorProduto($produtoId);

        //busca o produto pelo id
        $produtoModel = new produtoModel();
        $produto = $produtoModel->find($produtoId);

        //enviar informações que serão mostradas na view
        return view('fotos/index',['fotos'=>$fotos,'produtoId'=>$produtoId, 'produtoNome'=>$produto['nome']]);
    }

    public function upload($produtoId){

        $arquivo = $this->request->getFile('foto');

        if($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()){
            $nome = $arquivo->getRandomName();

            $arquivo->move('uploads/fotos', $nome);

            //salvar no banco
            $this->fotoProdutoModel->save([
                'produto_id' => $produtoId,
                'caminho'    => $nome,
                'capa'       => false,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    
        return redirect()->back()->with('success', 'Foto enviada');
    }

    public function definirCapa($id){
        //procurando a foto no banco
        $foto = $this->fotoProdutoModel->find($id);

        if($foto){
            $this->fotoProdutoModel->where('produto_id', $foto['produto_id'])->set(['capa'=>false])->update();

            //linha que atualiza e faz a foto ser uma capa
            $this->fotoProdutoModel->update($id,['capa'=>true]);
        }

        return redirect()->back()->with('success', 'Foto definida como capa');
    }

    public function delete($id){
        $foto = $this->fotoProdutoModel->find($id);

        if($id){
            $arquivo = FCPATH . 'uploads/fotos/' . $foto['caminho'];

            if(file_exists($arquivo)){
                unlink($arquivo);
            }
            $this->fotoProdutoModel->delete($id);
        }
        
        return redirect()->back()->with('success', 'Foto apagada');

    }
}
