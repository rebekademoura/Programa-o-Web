<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password_hash','role', 'created_at', 'update_at','reset_token','reset_token_date','foto_perfil'];
    protected $useTimestamps = false;
}
