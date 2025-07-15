<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\Unit;
use PDO;
use PDOStatement;

class UnitTest extends TestCase
{
    /**
     * Verifica se o método all retorna todas as unidades ordenadas por nome.
     */
    public function testAllReturnsAllUnitsSortedByName()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->method('bindValue')->withAnyParameters();
        $stmt->method('execute');
        $stmt->method('fetchAll')->willReturn([
            ['unit_id' => 1, 'name' => 'Litro', 'symbol' => 'L']
        ]);

        $pdo->method('prepare')->willReturn($stmt);

        $model = new Unit($pdo);
        $result = $model->all();

        $this->assertCount(1, $result);
        $this->assertEquals('Litro', $result[0]['name']);
    }

    /**
     * Verifica se o método findById retorna corretamente os dados de uma unidade existente.
     */
    public function testFindByIdReturnsUnit()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
             ->method('execute')
             ->with(['id' => 1]);

        $stmt->expects($this->once())
             ->method('fetch')
             ->willReturn([
                 'unit_id' => 1,
                 'name' => 'Quilo',
                 'symbol' => 'kg'
             ]);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM units WHERE unit_id = :id')
            ->willReturn($stmt);

        $model = new Unit($pdo);
        $result = $model->findById(1);

        $this->assertIsArray($result);
        $this->assertEquals('Quilo', $result['name']);
    }

    /**
     * Garante que o método delete executa corretamente a instrução SQL de exclusão.
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
            ->with('DELETE FROM units WHERE unit_id = :id')
            ->willReturn($stmt);

        $model = new Unit($pdo);
        $model->delete(2);

        $this->assertTrue(true);
    }

    /**
     * Verifica se o método upsert executa corretamente a operação de insert/update e retorna o ID.
     */
    public function testUpsertInsertsOrUpdatesUnit()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $pdo->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('INSERT INTO units'))
            ->willReturn($stmt);

        $stmt->expects($this->once())
             ->method('execute')
             ->with([
                 'unit_id' => null,
                 'name' => 'Caixa',
                 'symbol' => 'cx'
             ]);

        $pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn("5");

        $model = new Unit($pdo);
        $id = $model->upsert([
            'unit_id' => null,
            'name' => 'Caixa',
            'symbol' => 'cx'
        ]);

        $this->assertEquals(5, (int) $id);
    }

    /**
     * Testa se o método list retorna corretamente os resultados filtrados com base no termo de busca.
     */
    public function testListReturnsFilteredResults()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->exactly(3))
             ->method('bindValue')
             ->willReturnCallback(function ($param, $value, $type = null) {
                if ($param === ':search') {
                    TestCase::assertEquals('%lit%', $value);
                    TestCase::assertEquals(PDO::PARAM_STR, $type);
                } elseif ($param === ':limit') {
                    TestCase::assertEquals(10, $value);
                    TestCase::assertEquals(PDO::PARAM_INT, $type);
                } elseif ($param === ':offset') {
                    TestCase::assertEquals(0, $value);
                    TestCase::assertEquals(PDO::PARAM_INT, $type);
                }
                return true;
             });

        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())->method('fetchAll')->willReturn([
            ['unit_id' => 1, 'name' => 'Litro', 'symbol' => 'L']
        ]);

        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = new Unit($pdo);
        $results = $model->list('lit');

        $this->assertCount(1, $results);
        $this->assertEquals('Litro', $results[0]['name']);
    }

    /**
     * Verifica se o método count retorna corretamente o total de unidades com base no termo de busca.
     */
    public function testCountReturnsCorrectValue()
    {
        $pdo = $this->createMock(PDO::class);
        $stmt = $this->createMock(PDOStatement::class);

        $stmt->expects($this->once())
             ->method('bindValue')
             ->with(':search', '%cx%', PDO::PARAM_STR);

        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())->method('fetchColumn')->willReturn(3);

        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = new Unit($pdo);
        $count = $model->count('cx');

        $this->assertEquals(3, $count);
    }
}
