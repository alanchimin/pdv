<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\core\Model;
use PDO;
use PDOStatement;

class ModelTest extends TestCase
{
    /**
     * Cria uma instância anônima de Model com uma tabela de teste.
     */
    private function getTestModel(PDO $pdo): Model
    {
        return new class($pdo) extends Model {
            protected string $table = 'test_table';
            protected string $primaryKey = 'id';
            protected array $orderableColumns = ['id', 'name'];
        };
    }

    /**
     * Garante que o método findById retorna os dados corretos quando o ID existe.
     */
    public function testFindByIdReturnsRow()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(['id' => 1, 'name' => 'Teste']);
        $stmt->expects($this->once())->method('execute')->with(['id' => 1]);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())->method('prepare')->with('SELECT * FROM test_table WHERE id = :id')->willReturn($stmt);

        $model = $this->getTestModel($pdo);
        $result = $model->findById(1);

        $this->assertEquals('Teste', $result['name']);
    }

    /**
     * Garante que delete executa o comando SQL correto.
     */
    public function testDeleteRunsDeleteQuery()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with(['id' => 5]);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())->method('prepare')->with('DELETE FROM test_table WHERE id = :id')->willReturn($stmt);

        $model = $this->getTestModel($pdo);
        $model->delete(5);

        $this->assertTrue(true);
    }

    /**
     * Garante que upsert executa corretamente e retorna o lastInsertId.
     */
    public function testUpsertRunsInsertOrUpdate()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('execute')->with(['id' => null, 'name' => 'Teste']);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);
        $pdo->expects($this->once())->method('lastInsertId')->willReturn("99");

        $model = $this->getTestModel($pdo);
        $id = $model->upsert(['id' => null, 'name' => 'Teste']);

        $this->assertEquals(99, (int) $id);
    }

    /**
     * Garante que baseListQuery monta e executa corretamente a query com filtros.
     */
    public function testBaseListQueryBuildsCorrectSqlAndReturnsResults()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->any())->method('bindValue')->withAnyParameters();
        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'Item']
        ]);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = $this->getTestModel($pdo);
        $result = $model->baseListQuery(search: 'Item', limit: 1, offset: 0, orderBy: 'name');

        $this->assertCount(1, $result);
        $this->assertEquals('Item', $result[0]['name']);
    }

    /**
     * Garante que baseCount retorna a quantidade correta de registros encontrados.
     */
    public function testBaseCountReturnsCorrectTotal()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())->method('bindValue')->withAnyParameters();
        $stmt->expects($this->once())->method('execute');
        $stmt->expects($this->once())->method('fetchColumn')->willReturn(7);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = $this->getTestModel($pdo);
        $count = $model->baseCount('name', 'abc');

        $this->assertEquals(7, $count);
    }

    /**
     * Garante que findById retorna null caso nenhum registro seja encontrado.
     */
    public function testFindByIdReturnsNullIfNotFound()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('fetch')->willReturn(false);
        $stmt->expects($this->once())->method('execute')->with(['id' => 999]);

        $pdo = $this->createMock(PDO::class);
        $pdo->expects($this->once())->method('prepare')->willReturn($stmt);

        $model = $this->getTestModel($pdo);
        $result = $model->findById(999);

        $this->assertNull($result);
    }
}
