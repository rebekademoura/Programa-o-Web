<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentFieldsToCarrinho extends Migration
{
    public function up()
    {
        $fields = [
            'forma_pagamento' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
                'default'    => '',
                'comment'    => 'Ex: boleto, cartao, pix, etc.'
            ],
            'parcelas' => [
                'type'       => 'INT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => false,
                'default'    => 1,
                'comment'    => 'Número de vezes/parcelas'
            ],
        ];

        // Adiciona as colunas à tabela 'carrinho'
        $this->forge->addColumn('carrinho', $fields);
    }

    public function down()
    {
        // Remove as colunas em caso de rollback
        $this->forge->dropColumn('carrinho', ['forma_pagamento', 'parcelas']);
    }
}
