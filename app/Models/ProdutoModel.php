<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table      = 'produtos';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nome', 'descricao', 'preco','id_categoria'];

    protected $useTimestamps = true;

    public function getComCategoriaECapa()
    {
        return $this->select('produtos.*, categorias.nome as nome_categoria, fotos_produtos.caminho as foto_capa')
            ->join('categorias', 'categorias.id = produtos.id_categoria', 'left')
            ->join('fotos_produtos', 'fotos_produtos.produto_id = produtos.id AND fotos_produtos.capa = 1', 'left')
            ->orderBy('produtos.id', 'DESC');
    }
}