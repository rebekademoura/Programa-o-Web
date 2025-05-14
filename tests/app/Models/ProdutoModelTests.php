<?php

namespace Tests\App\Models;

use App\Models\ProdutoModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class ProdutoModelTests extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $refresh = true; // recria tabelas entre os testes

    public function testInserirProduto()
    {
        $produtoModel = new ProdutoModel();

        $id = $produtoModel->insert([
            'nome'      => 'Bolo de Laranja',
            'preco'     => 19.90,
            'descricao' => 'Bolo caseiro sem glúten',
        ]);

        $this->assertIsInt($id);
        $produto = $produtoModel->find($id);
        $this->assertEquals('Bolo de Laranja', $produto['nome']);
    }

    public function testEditarProduto()
    {
        $produtoModel = new ProdutoModel();

        $id = $produtoModel->insert([
            'nome'      => 'Bolo Simples',
            'preco'     => 10.00,
            'descricao' => 'Descrição original',
        ]);

        $produtoModel->update($id, [
            'nome' => 'Bolo Simples Atualizado',
            'preco' => 12.50,
        ]);

        $produto = $produtoModel->find($id);
        $this->assertEquals('Bolo Simples Atualizado', $produto['nome']);
        $this->assertEquals(12.50, $produto['preco']);
    }

    public function testExcluirProduto()
    {
        $produtoModel = new ProdutoModel();

        $id = $produtoModel->insert([
            'nome'      => 'Produto Temp',
            'preco'     => 1.00,
            'descricao' => 'Será deletado',
        ]);

        $this->assertTrue($produtoModel->delete($id));
        $this->assertNull($produtoModel->find($id));
    }
}
