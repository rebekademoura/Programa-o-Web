<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTelefoneToContatos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'telefone' => ['type'=>'VARCHAR',  'constraint'=>255]
        ]);

        $this->forge->addColumn('contatos', [
            'telefone' => ['type' => 'VARCHAR', 'constraint' => 255]
        ]);
        
    }

    public function down()
    {
        $this->forge->dropColumn('contatos', 'telefone');
    }
}
