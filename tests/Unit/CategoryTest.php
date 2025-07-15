<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\Category;
use PDO;
use PDOStatement;

class CategoryTest extends TestCase
{
    /**
     * Testa se o método all retorna todas as categorias ordenadas por nome.
     */
    public function testAllReturnsAllCategoriesSortedByName()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->method('bindValue')->withAnyParameters();
        $stmt->method('execute');
        $stmt->method('fetchAll')->willReturn([
            ['category_id' => 1, 'name' => 'Açougue', 'icon' => 'fa-cow']
        ]);

        $pdo->method('prepare')->willReturn($stmt);

        $model = new Category($pdo);
        $result = $model->all();

        $this->assertCount(1, $result);
        $this->assertEquals('Açougue', $result[0]['name']);
    }

    /**
     * Testa se o método findById retorna os dados corretos de uma categoria existente.
     */
    public function testFindByIdReturnsCategory()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
             ->method('execute')
             ->with(['id' => 1]);

        $stmt->expects($this->once())
             ->method('fetch')
             ->willReturn([
                 'category_id' => 1,
                 'name' => 'Bebidas',
                 'icon' => 'fa-beer'
             ]);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM categories WHERE category_id = :id')
            ->willReturn($stmt);

        $model = new Category($pdo);
        $result = $model->findById(1);

        $this->assertIsArray($result);
        $this->assertEquals('Bebidas', $result['name']);
    }

    /**
     * Testa se o método delete executa corretamente a query de exclusão.
     */
    public function testDeleteExecutesCorrectStatement()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
             ->method('execute')
             ->with(['id' => 2]);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM categories WHERE category_id = :id')
            ->willReturn($stmt);

        $model = new Category($pdo);
        $model->delete(2);

        $this->assertTrue(true);
    }

    /**
     * Testa se o método upsert executa o insert/update corretamente e retorna o ID.
     */
    public function testUpsertInsertsOrUpdatesCategory()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO categories'))
            ->willReturn($stmt);

        $stmt->expects($this->once())
             ->method('execute')
             ->with([
                 'category_id' => null,
                 'name' => 'Doces',
                 'icon' => 'fa-candy-cane'
             ]);

        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn("10");

        $model = new Category($pdo);
        $id = $model->upsert([
            'category_id' => null,
            'name' => 'Doces',
            'icon' => 'fa-candy-cane'
        ]);

        $this->assertEquals(10, (int) $id);
    }

    /**
     * Testa se o método list retorna categorias filtradas corretamente.
     */
    public function testListReturnsFilteredResults()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->exactly(3))
             ->method('bindValue')
             ->willReturnCallback(function ($param, $value, $type = null) {
                if ($param === ':search') {
                    TestCase::assertEquals('%beb%', $value);
                    TestCase::assertEquals(PDO::PARAM_STR, $type);
                } elseif ($param === ':limit') {
                    TestCase::assertEquals(5, $value);
                    TestCase::assertEquals(PDO::PARAM_INT, $type);
                } elseif ($param === ':offset') {
                    TestCase::assertEquals(0, $value);
                    TestCase::assertEquals(PDO::PARAM_INT, $type);
                }
                return true;
            });

        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())
             ->method('fetchAll')
             ->willReturn([
                 ['category_id' => 1, 'name' => 'Bebidas', 'icon' => 'fa-beer']
             ]);

        $pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        $model = new Category($pdo);
        $results = $model->list('beb', 5);

        $this->assertCount(1, $results);
        $this->assertEquals('Bebidas', $results[0]['name']);
    }

    /**
     * Testa se o método count retorna o total correto com o filtro aplicado.
     */
    public function testCountReturnsCorrectValue()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
             ->method('bindValue')
             ->with(':search', '%bebidas%', PDO::PARAM_STR);

        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())->method('fetchColumn')->willReturn(7);

        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = new Category($pdo);
        $count = $model->count('bebidas');

        $this->assertEquals(7, $count);
    }
}
