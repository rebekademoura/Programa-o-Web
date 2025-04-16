<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run()
    {   
        /*
        $data=[
            [
                'nome' => 'mouse',
                'descricao' => 'mouse top',
                'preco' => '50',
                'created_at' => date('Y-m-d h:i:s')
            ]
        ];

        $this->db->table('produtos')->insertBatch($data);
        */

        for($i=0; $i<100; $i++){
            $this->db->table('produtos')->insertBatch(
                [
                    'nome' => 'Produto' .$i,
                    'descricao' => 'Produto' .$i,
                    'preco' => rand(100, 1000),
                    'created_at' => date('Y-m-d h:i:s')
                ]
            );
        }
    }
}
