<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaVendas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'                   => ['type'=>'INT','unsigned'=>true,'auto_increment'=>true],
            'usuario_comprador_id' => ['type'=>'INT','unsigned'=>true],
            'usuario_vendedor_id'  => ['type'=>'INT','unsigned'=>true],
            'produto_id'           => ['type'=>'INT','unsigned'=>true],
            'quantidade'           => ['type'=>'INT','default'=>1],
            'valor_total'          => ['type'=>'DECIMAL','constraint'=>'10,2','default'=>'0.00'],
            'forma_pagamento'      => ['type'=>"ENUM('cartao','pix')",'default'=>'cartao'],
            'parcelas'             => ['type'=>'TINYINT','constraint'=>3,'default'=>1],
            'criado_em'            => ['type'=>'DATETIME','null'=>false,'default'=>'CURRENT_TIMESTAMP'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('vendas', true);
    }

    public function down()
    {
        $this->forge->dropTable('vendas');
    }
}
