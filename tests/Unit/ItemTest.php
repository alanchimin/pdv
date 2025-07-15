<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\Item;
use PDO;
use PDOStatement;

class ItemTest extends TestCase
{
    /**
     * Testa se allByOrder retorna corretamente os itens do pedido.
     */
    public function testAllByOrderReturnsItems()
    {
        $orderId = 42;

        $expected = [
            ['item_id' => 1, 'product_id' => 3, 'amount' => 2, 'product_name' => 'Produto A'],
            ['item_id' => 2, 'product_id' => 4, 'amount' => 1, 'product_name' => 'Produto B'],
        ];

        // Mock de PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with([$orderId]);
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->with(PDO::FETCH_ASSOC)
                 ->willReturn($expected);

        // Mock de PDO
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with($this->stringContains('FROM items i'))
                ->willReturn($stmtMock);

        $itemModel = new Item($pdoMock);
        $result = $itemModel->allByOrder($orderId);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Produto A', $result[0]['product_name']);
    }

    /**
     * Testa se o método create insere corretamente os dados no banco.
     */
    public function testCreateInsertsItem()
    {
        $data = [
            'amount' => 2,
            'discount' => 10,
            'unit_price' => 5.50,
            'total_price' => 9.90,
            'product_id' => 3,
            'order_id' => 1
        ];

        // Mock de PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with($data);

        // Mock de PDO
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with($this->stringContains('INSERT INTO items'))
                ->willReturn($stmtMock);

        $itemModel = new Item($pdoMock);
        $itemModel->create($data);

        // Nenhuma exceção significa sucesso.
        $this->assertTrue(true);
    }
}
