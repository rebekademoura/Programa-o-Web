<?php

namespace App\Models;

use CodeIgniter\Model;

class ItensPedidos extends Model
{
            //id, pedido_id, produto_id, quantidade, preco, created_at
    protected $table      = 'itens_pedidos';
    protected $primaryKey = 'id';       
    protected $allowedFields = ['pedido_id', 'produto_id', 'quantidade', 'preco', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
}
