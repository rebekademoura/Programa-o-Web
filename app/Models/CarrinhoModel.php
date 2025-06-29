<?php namespace App\Models;

use CodeIgniter\Model;

class CarrinhoModel extends Model
{
    protected $table      = 'carrinho';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'usuario_id',
        'produto_id',
        'quantidade',
        'adicionado_em',
        'forma_pagamento',
        'parcelas',
    ];

    // não vamos usar timestamps automáticos
    protected $useTimestamps = false;
}
