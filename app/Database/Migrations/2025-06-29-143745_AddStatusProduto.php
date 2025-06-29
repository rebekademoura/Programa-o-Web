<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusProdutos extends Migration
{
    public function up()
    {
        $this->forge->addColumn('produtos', [
            'status' => [
                'type'       => "ENUM('aprovado','reprovado')",
                'default'    => 'reprovado',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('produtos', 'status');
    }
}
