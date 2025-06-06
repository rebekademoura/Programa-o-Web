<?php

namespace App\Models;

use CodeIgniter\Model;

class Pedidos extends Model
{
    //        //id, usuario_id , total, status[pendente, pago], created_at
    protected $table      = 'itens_pedidos';
    protected $primaryKey = 'id';   
    protected $allowedFields = ['pedido_id', 'produto_id', 'quantidade', 'preco', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $validationRules = [
        'pedido_id'  => 'required|integer',
        'produto_id' => 'required|integer',
        'quantidade' => 'required|integer',
        'preco'      => 'required|decimal',
    ];
}
