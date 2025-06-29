<?php

namespace App\Models;

use CodeIgniter\Model;

class VendaModel extends Model
{
    protected $table      = 'vendas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'usuario_comprador_id',
        'usuario_vendedor_id',
        'produto_id',
        'quantidade',
        'valor_total',
        'forma_pagamento',
        'parcelas',
        'criado_em',
    ];

    // Se quiser timestamps automáticos, pode usar:
    // protected $useTimestamps = true;
    // protected $createdField  = 'criado_em';
    // protected $updatedField  = ''; // não usamos
}
