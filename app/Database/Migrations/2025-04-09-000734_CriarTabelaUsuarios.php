<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaUsuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [ 'type' => 'INTEGER', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true ],
            'username' => [ 'type' => 'VARCHAR', 'constraint' => 80],
            'email' =>['type' => 'VARCHAR', 'constraint' => 80],
            'password_hash' =>[ 'type' => 'VARCHAR', 'constraint' => 255],
            'created_at' =>[ 'type' => 'DATETIME','null' => TRUE ],
            'update_at' =>[ 'type' => 'DATETIME','null' => TRUE]

        ]); 

        $this->forge->addKey('id', true);
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        //
    }
}
