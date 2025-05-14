<?php

namespace App\Models;

use CodeIgniter\Model;

class FotoPRodutoModel extends Model
{
    protected $table      = 'fotos_produtos';
    protected $primaryKey = 'id';

    protected $allowedFields = ['produto_id', 'caminho', 'capa', 'created_at'];

    protected $useTimestamps = false;

    public function ListarPorProduto($produtoId){
        return $this->where('produto_id', $produtoId)->orderBy('capa','DESC')->findAll();
    }

    public function getCapa($produtoId){
        return $this->where('produto_id', $produtoId)->where('capa',tue)->first();
    }

    
}