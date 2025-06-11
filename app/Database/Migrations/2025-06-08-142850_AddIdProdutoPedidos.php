<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdProdutoPedidos extends Migration
{
    public function up()
    {
        // Adiciona a coluna id_produto na tabela pedidos
        $this->forge->addColumn('pedidos', [
            'id_produto' => [
                'type'       => 'INTEGER',
                'constraint' => 5,
                'unsigned'   => true,
                'after'      => 'usuario_id', // opcional: posiciona a coluna após 'usuario_id'
            ],
        ]);
        // Adiciona a chave estrangeira para a coluna id_produto
        $this->forge->addForeignKey('id_produto', 'produtos', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        //
    }
}
