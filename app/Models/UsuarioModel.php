<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password_hash', 'created_at', 'update_at'];
    protected $useTimestamps = false;
}
