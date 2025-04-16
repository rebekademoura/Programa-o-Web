<?php

namespace App\Models;

use CodeIgniter\Model;

class ContatoModel extends Model
{
    protected $table      = 'contatos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome', 'email', 'mensagem', 'telefone', 'created_at'];
    protected $useTimestamps = false;
}
