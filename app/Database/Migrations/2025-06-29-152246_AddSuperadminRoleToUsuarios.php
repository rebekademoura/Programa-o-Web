<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuperadminRoleToUsuarios extends Migration
{
    public function up()
    {
        // Isso depende de como seu SGBD trata ENUMs;
        // Num MySQL típico, você precisa recriar a coluna:
        $this->forge->modifyColumn('usuarios', [
            'role' => [
                'name'       => 'role',
                'type'       => "ENUM('user','seller','admin','admin_geral')",
                'default'    => 'user',
            ],
        ]);
    }

    public function down()
    {
        // Reverte para o ENUM original
        $this->forge->modifyColumn('usuarios', [
            'role' => [
                'name'       => 'role',
                'type'       => "ENUM('user','seller','admin')",
                'default'    => 'user',
            ],
        ]);
    }
}
