<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaContato extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT', 'auto_increment'=> true],
            'email' => ['type'=>'VARCHAR',  'constraint'=>255],
            'nome' => ['type'=>'VARCHAR',  'constraint'=>255],
            'mensagem' => ['type'=>'TEXT'],
            'created_at' => ['type'=>'DATETIME', 'null'=> true],
        ]); 

        $this->forge->addKey('id',true);
        $this->forge->createTable('contatos');
    }

    public function down()
    {
        $this->forge->dropTable('contatos'); 
    }
}
