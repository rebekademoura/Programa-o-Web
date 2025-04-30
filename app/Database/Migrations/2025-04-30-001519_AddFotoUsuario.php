<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoUsuario extends Migration
{
    public function up()
    {
        $this->forge->addColumn('usuarios', [
            'foto_perfil' => ['type'=>'VARCHAR', 'constraint'=>255, 'null'=>true]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', 'foto_perfil');
    }
}
