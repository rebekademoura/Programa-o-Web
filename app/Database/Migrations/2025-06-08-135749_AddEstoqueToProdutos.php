<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEstoqueToProdutos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('produtos', [
            'estoque' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'preco', // opcional: posiciona a coluna após 'preco'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('produtos', 'estoque');
    }
}
