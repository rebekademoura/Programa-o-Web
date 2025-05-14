<?php
namespace App\Tests;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\HTTP\ResponseInterface;
use App\Controllers\ProdutoController;
use App\Models\ProdutoModel;
use App\Models\CategoriasModel;
use CodeIgniter\Test\Mock\MockResponse;
use PHPUnit\Framework\MockObject\MockObject;

class ProdutoControllerTest extends CIUnitTestCase
{
    /** @var ProdutoController|MockObject */
    protected $controller;

    /** @var ProdutoModel|MockObject */
    protected $produtoModel;

    /** @var CategoriasModel|MockObject */
    protected $categoriaModel;

    protected function setUp(): void
    {
        parent::setUp();

        $this->produtoModel = $this->createMock(ProdutoModel::class);
        $this->categoriaModel = $this->createMock(CategoriasModel::class);

        $this->controller = new ProdutoController();

        // Usando setters públicos para injetar os mocks
        $this->controller->setProdutoModel($this->produtoModel);
        $this->controller->setCategoriaModel($this->categoriaModel);
    }

    public function testIndexWithFilters()
    {
        $this->request->setGet('filtroNome', 'Produto 1');
        $this->request->setGet('filtroPreco', 'medio');

        $this->produtoModel->expects($this->once())
            ->method('select')
            ->willReturnSelf();

        $this->produtoModel->expects($this->once())
            ->method('join')
            ->willReturnSelf();

        $this->produtoModel->expects($this->once())
            ->method('like')
            ->willReturnSelf();

        $this->produtoModel->expects($this->once())
            ->method('where')
            ->willReturnSelf();

        $this->produtoModel->expects($this->once())
            ->method('paginate')
            ->willReturn(['product1', 'product2']);

        $this->produtoModel->expects($this->once())
            ->method('pager')
            ->willReturn('pager_instance');

        $response = $this->controller->index();
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testCreate()
    {
        $this->categoriaModel->expects($this->once())
            ->method('findAll')
            ->willReturn([['id' => 1, 'nome' => 'Categoria 1'], ['id' => 2, 'nome' => 'Categoria 2']]);

        ob_start(); // Previne erro de output buffer
        $response = $this->controller->create();
        ob_end_clean();

        $this->assertIsString($response);
        $this->assertStringContainsString('Categoria 1', $response);
        $this->assertStringContainsString('Categoria 2', $response);
    }

    public function testStore()
    {
        $this->request->setPost('nome', 'Produto Teste');
        $this->request->setPost('descricao', 'Descrição Teste');
        $this->request->setPost('preco', 200);
        $this->request->setPost('id_categoria', 1);

        $this->produtoModel->expects($this->once())
            ->method('save')
            ->with([
                'nome' => 'Produto Teste',
                'descricao' => 'Descrição Teste',
                'preco' => 200,
                'id_categoria' => 1,
            ]);

        $response = $this->controller->store();
        $this->assertInstanceOf(MockResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
{
    $this->produtoModel->expects($this->once())
        ->method('find')
        ->with(1)
        ->willReturn(['id' => 1, 'nome' => 'Produto 1']);

    $this->categoriaModel->expects($this->once())
        ->method('findAll')
        ->willReturn([['id' => 1, 'nome' => 'Categoria 1']]);

    // Inicia e finaliza o buffer corretamente
    ob_start();
    $this->controller->edit(1);
    $output = ob_get_clean();

    $this->assertIsString($output);
    $this->assertStringContainsString('Produto 1', $output);
}


    public function testUpdate()
    {
        $this->request->setPost('nome', 'Produto Atualizado');
        $this->request->setPost('descricao', 'Descrição Atualizada');
        $this->request->setPost('preco', 300);
        $this->request->setPost('categoria_id', 2);

        $this->produtoModel->expects($this->once())
            ->method('update')
            ->with(1, [
                'nome' => 'Produto Atualizado',
                'descricao' => 'Descrição Atualizada',
                'preco' => 300,
                'categoria_id' => 2,
            ]);

        $response = $this->controller->update(1);
        $this->assertInstanceOf(MockResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->produtoModel->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->delete(1);
        $this->assertInstanceOf(MockResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
    }
}
