<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdCategoria extends Migration
{
    public function up()
    {
        // Adiciona a coluna id_categoria
    //     $this->forge->addColumn('produtos', [
    //         'id_categoria' => [
    //             'type'       => 'INT',
    //             'constraint' => 11,
    //             'null'       => true,
    //         ],
    //     ]);

    //     // Adiciona a chave estrangeira separadamente
    //     $this->db->query('ALTER TABLE produtos ADD CONSTRAINT fk_categoria FOREIGN KEY (id_categoria) REFERENCES categorias(id)');
    // }
    }

    public function down()
    {
        // Remove a foreign key e depois a coluna
        $this->db->query('ALTER TABLE produtos DROP FOREIGN KEY fk_categoria');
        $this->forge->dropColumn('produtos', 'id_categoria');
    }
}
