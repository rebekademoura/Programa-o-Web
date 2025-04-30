<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetTokenUsuarios extends Migration
{
    public function up()
    {
        //adicionar colunas em tabela

        $this->forge->addColumn('usuarios',
        [
            'reset_token'      => ['type'=>'VARCHAR', 'constraint'=>255, 'null'=>true],
            'reset_token_date' => ['type'=>'DATETIME', 'null'=>true]
        ]);
    }

    public function down()
    {
        $this->forge->drop('usuarios',['reset_token', 'reset_token_date']);
    }
}
