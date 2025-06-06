<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ItensPedidos extends Migration
{
    public function up()
    {
        //id, pedido_id, produto_id, quantidade, preco, created_at
        $this->forge->addField([
            'id'          => [
                'type'           => 'INTEGER',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pedido_id'  => [
                'type'       => 'INTEGER',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'produto_id' => [
                'type'       => 'INTEGER',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'quantidade' => [
                'type'       => 'INTEGER',
                'constraint' => 5,
            ],
            'preco'      => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addForeignKey('pedido_id', 'pedidos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('produto_id', 'produtos', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('id', true
        );
        $this->forge->createTable('itens_pedidos'); 
    }

    public function down()
    {
        $this->forge->dropForeignKey('itens_pedidos', 'itens_pedidos_pedido_id_foreign');
        $this->forge->dropForeignKey('itens_pedidos', 'itens_pedidos_produto_id_foreign');
        $this->forge->dropTable('itens_pedidos');
    }
}
