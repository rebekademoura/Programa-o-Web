<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table      = 'produtos';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nome','usuario_id', 'descricao', 'preco','id_categoria', 'estoque', 'ativo', 'status'];

    protected $useTimestamps = true;

    public function getComCategoriaECapa()
    {
        return $this->select('produtos.*, categorias.nome as nome_categoria, fotos_produtos.caminho as foto_capa')
            ->join('categorias', 'categorias.id = produtos.id_categoria', 'left')
            ->join('fotos_produtos', 'fotos_produtos.produto_id = produtos.id AND fotos_produtos.capa = 1', 'left')
            ->orderBy('produtos.id', 'DESC');
    }

     public function getProdutosPorUsuarioComCategoriaECapa(int $usuarioId)
    {
        return $this->select('produtos.*, categorias.nome as nome_categoria, fotos_produtos.caminho as foto_capa')
            ->join('categorias', 'categorias.id = produtos.id_categoria', 'left')
            ->join('fotos_produtos', 'fotos_produtos.produto_id = produtos.id AND fotos_produtos.capa = 1', 'left')
            ->where('produtos.usuario_id', $usuarioId) 
            ->orderBy('produtos.id', 'DESC');
    }

    public function getProdutoPorIdEUsuarioComCategoriaECapa(int $produtoId, int $usuarioId)
    {
        return $this->select('produtos.*, categorias.nome as nome_categoria, fotos_produtos.caminho as foto_capa')
            ->join('categorias', 'categorias.id = produtos.id_categoria', 'left')
            ->join('fotos_produtos', 'fotos_produtos.produto_id = produtos.id AND fotos_produtos.capa = 1', 'left')
            ->where('produtos.id', $produtoId)
            ->where('produtos.usuario_id', $usuarioId)
            ->first();
    }

}