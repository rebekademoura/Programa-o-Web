<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Pedidos extends Migration
{
    public function up()
    {
        //id, usuario_id , total, status[pendente, pago], created_at

        $this->forge->addField([
            'id'          => [
                'type'           => 'INTEGER',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type'       => 'INTEGER',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'total'      => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status'     => [
                'type'       => 'ENUM',
                'constraint' => ['pendente', 'pago'],
                'default'    => 'pendente',
            ],
            'created_at' => [
                'type'       => 'DATETIME',
            ],
        ]);
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addKey('id', true
        );
        $this->forge->createTable('pedidos');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pedidos', 'pedidos_usuario_id_foreign');
        $this->forge->dropTable('pedidos');
    }
}
