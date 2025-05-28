<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FotoProdutoModel;
use App\Models\ProdutoModel;
use App\Models\UsuarioModel;

class FotoProdutoController extends BaseController
{
    protected $fotoProdutoModel;
    public $usuarioModel;

    public function __construct()
    {
        $this->fotoProdutoModel = new FotoProdutoModel();
        $this->usuarioModel     = new UsuarioModel();
    }

    public function index($produtoId)
    {
        $usuarioId = session()->get('usuario_id');

        $produtoModel = new ProdutoModel();
        $produto = $produtoModel->getProdutoPorIdEUsuarioComCategoriaECapa($produtoId, $usuarioId);

        if (!$produto) {
            return redirect()->to('/produtos')->with('error', 'Acesso negado ou produto inexistente.');
        }

        $fotos = $this->fotoProdutoModel->where('produto_id', $produtoId)->findAll();

        return view('fotos/index', [
            'fotos' => $fotos,
            'produtoId' => $produtoId,
            'produtoNome' => $produto['nome']
        ]);
    }

    public function upload($produtoId)
    {
        $usuarioId = session()->get('usuario_id');

        $produtoModel = new ProdutoModel();
        $produto = $produtoModel->getProdutoPorIdEUsuarioComCategoriaECapa($produtoId, $usuarioId);

        if (!$produto) {
            return redirect()->to('/produtos')->with('error', 'Acesso negado.');
        }

        $arquivo = $this->request->getFile('foto');

        if ($arquivo && $arquivo->isValid() && !$arquivo->hasMoved()) {
            $nome = $arquivo->getRandomName();
            $arquivo->move('uploads/fotos', $nome);

            $this->fotoProdutoModel->save([
                'produto_id' => $produtoId,
                'caminho'    => $nome,
                'capa'       => false,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->back()->with('success', 'Foto enviada');
    }

    public function definirCapa($id)
    {
        $foto = $this->fotoProdutoModel->find($id);

        if (!$foto) {
            return redirect()->back()->with('error', 'Foto não encontrada');
        }

        $usuarioId = session()->get('usuario_id');
        $produtoModel = new ProdutoModel();
        $produto = $produtoModel->getProdutoPorIdEUsuarioComCategoriaECapa($foto['produto_id'], $usuarioId);

        if (!$produto) {
            return redirect()->to('/produtos')->with('error', 'Acesso negado.');
        }

        $this->fotoProdutoModel->where('produto_id', $foto['produto_id'])->set(['capa' => false])->update();
        $this->fotoProdutoModel->update($id, ['capa' => true]);

        return redirect()->back()->with('success', 'Foto definida como capa');
    }

    public function delete($id)
    {
        $foto = $this->fotoProdutoModel->find($id);

        if (!$foto) {
            return redirect()->back()->with('error', 'Foto não encontrada');
        }

        $usuarioId = session()->get('usuario_id');
        $produtoModel = new ProdutoModel();
        $produto = $produtoModel->getProdutoPorIdEUsuarioComCategoriaECapa($foto['produto_id'], $usuarioId);

        if (!$produto) {
            return redirect()->to('/produtos')->with('error', 'Acesso negado.');
        }

        $arquivo = FCPATH . 'uploads/fotos/' . $foto['caminho'];

        if (file_exists($arquivo)) {
            unlink($arquivo);
        }

        $this->fotoProdutoModel->delete($id);

        return redirect()->back()->with('success', 'Foto apagada');
    }

    public function uploadAjax($produtoId)
    {
        $usuarioId = session()->get('usuario_id');

        $produtoModel = new ProdutoModel();
        $produto = $produtoModel->getProdutoPorIdEUsuarioComCategoriaECapa($produtoId, $usuarioId);

        if (!$produto) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acesso negado.']);
        }

        $file = $this->request->getFile('foto');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $nome = $file->getRandomName();
            $file->move(FCPATH . 'uploads/fotos/', $nome);

            $this->fotoProdutoModel->insert([
                'produto_id' => $produtoId,
                'caminho'    => $nome,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->response->setJSON(['success' => true, 'file' => $nome]);
        }

        return $this->response->setStatusCode(400)->setJSON(['error' => 'Erro ao enviar o arquivo']);
    }
}
