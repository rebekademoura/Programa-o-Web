<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsuarioProdutos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('produtos', [
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
          $this->forge->dropForeignKey('produtos', 'produtos_usuario_id_foreign'); 

        $this->forge->dropColumn('produtos', 'usuario_id');
    }
}
