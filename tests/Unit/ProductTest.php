<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\Product;
use PDO;
use PDOStatement;

class ProductTest extends TestCase
{
    /**
     * Cria uma instância do model Product com PDO mockado.
     */
    private function getProductModel(PDO $pdo): Product
    {
        return new Product($pdo);
    }

    /**
     * Testa se o método all retorna todos os produtos ordenados por nome.
     */
    public function testAllReturnsAllProductsSortedByName()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        // Simula resultado do fetchAll
        $stmt->method('bindValue')->withAnyParameters();
        $stmt->method('execute');
        $stmt->method('fetchAll')->willReturn([
            ['product_id' => 1, 'name' => 'Produto A']
        ]);

        $pdo->method('prepare')->willReturn($stmt);

        $model = $this->getProductModel($pdo);
        $result = $model->all();

        $this->assertCount(1, $result);
        $this->assertEquals('Produto A', $result[0]['name']);
    }

    /**
     * Testa se o método findById retorna os dados corretos de um produto.
     */
    public function testFindByIdReturnsProduct()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())->method('execute')->with(['id' => 5]);
        $stmt->expects($this->once())->method('fetch')->willReturn([
            'product_id' => 5,
            'name' => 'Produto X',
            'unit_name' => 'Un',
            'symbol' => 'kg',
            'category_name' => 'Categoria Y'
        ]);

        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = $this->getProductModel($pdo);
        $result = $model->findById(5);

        $this->assertIsArray($result);
        $this->assertEquals('Produto X', $result['name']);
    }

    /**
     * Testa se o método delete remove a imagem do disco
     * quando image_type for 'upload'.
     */
    public function testDeleteRemovesImageIfUploadType()
    {
        $pdo = $this->createMock(PDO::class);

        // Mock SELECT da imagem
        $selectStmt = $this->createMock(PDOStatement::class);
        $selectStmt->expects($this->once())->method('execute')->with(['id' => 99]);
        $selectStmt->expects($this->once())->method('fetch')->willReturn([
            'image' => 'imagem.jpg',
            'image_type' => 'upload'
        ]);

        // Mock DELETE
        $deleteStmt = $this->createMock(PDOStatement::class);
        $deleteStmt->expects($this->once())->method('execute')->with(['id' => 99]);

        $pdo->expects($this->exactly(2))->method('prepare')->willReturnOnConsecutiveCalls($selectStmt, $deleteStmt);

        // Cria arquivo fictício para simular imagem
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/upload';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $file = $dir . '/imagem.jpg';
        file_put_contents($file, 'fake image content');

        $model = $this->getProductModel($pdo);
        $model->delete(99);

        $this->assertFileDoesNotExist($file);
    }

    /**
     * Testa se o método upsert insere ou atualiza um produto
     * e retorna o ID corretamente.
     */
    public function testUpsertInsertsOrUpdatesProduct()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);
        $stmt->expects($this->once())->method('execute')->with([
            'product_id' => null,
            'name' => 'Novo Produto',
            'image' => 'img.jpg',
            'image_type' => 'url',
            'unit_id' => 1,
            'unit_price' => 10.99,
            'discount' => null,
            'category_id' => 2
        ]);
        $pdo->expects($this->once())->method('lastInsertId')->willReturn("123");

        $model = $this->getProductModel($pdo);
        $id = $model->upsert([
            'product_id' => null,
            'name' => 'Novo Produto',
            'image' => 'img.jpg',
            'image_type' => 'url',
            'unit_id' => 1,
            'unit_price' => 10.99,
            'discount' => null,
            'category_id' => 2
        ]);

        $this->assertEquals(123, (int)$id);
    }

    /**
     * Testa se o método list retorna produtos filtrados por categoria e busca.
     */
    public function testListWithFilters()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->any())->method('bindValue')->withAnyParameters();
        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())->method('fetchAll')->willReturn([
            ['product_id' => 1, 'name' => 'Produto Y']
        ]);

        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = $this->getProductModel($pdo);
        $result = $model->list('prod', 10, 0, 'name', 'asc', ['category_id' => 2]);

        $this->assertCount(1, $result);
        $this->assertEquals('Produto Y', $result[0]['name']);
    }

    /**
     * Testa se o método count retorna o total correto considerando filtros.
     */
    public function testCountWithFilters()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->any())->method('bindValue')->withAnyParameters();
        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())->method('fetchColumn')->willReturn(8);

        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = $this->getProductModel($pdo);
        $count = $model->count('prod', ['category_id' => 2]);

        $this->assertEquals(8, $count);
    }
}
