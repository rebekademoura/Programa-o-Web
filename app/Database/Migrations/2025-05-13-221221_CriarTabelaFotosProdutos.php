<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaFotosProdutos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT',      'auto_increment'=>true, 'unsigned'=>true],
            'produto_id' => ['type' => 'INT',      'unsigned'=>true,       'null'=>false],
            'caminho'    => ['type' => 'VARCHAR',  'constraint' => 255],
            'capa'       => ['type' => 'BOOLEAN',  'default' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true]
        ]);

        $this->forge->addKey('id');
        $this->forge->addForeignKey('produto_id','produtos','id', 'CASCADE', 'CASCATE');

        $this->forge->createTable('fotos_produtos');
    }

    public function down()
    {
        $this->forge->dropTable('fotos_produtos');
    }
}
