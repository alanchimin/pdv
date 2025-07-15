<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\Order;
use PDO;
use PDOStatement;

class OrderTest extends TestCase
{
    /**
     * Testa se o método create insere um pedido e retorna o ID.
     */
    public function testCreateShouldInsertAndReturnId()
    {
        $data = [
            'payment_method_id' => 2,
            'user_id' => 5
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with($data);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
               ->method('prepare')
               ->with($this->stringContains('INSERT INTO orders'))
               ->willReturn($stmtMock);

        $pdoMock->expects($this->once())
               ->method('lastInsertId')
               ->willReturn("99");

        $orderModel = new Order($pdoMock);
        $result = $orderModel->create($data);

        $this->assertEquals(99, (int) $result);
    }

    /**
     * Testa se o método find retorna pedido com dados e itens agregados.
     */
    public function testFindShouldReturnOrderWithItems()
    {
        $orderId = 1;

        // Simula resultado do pedido
        $orderData = [
            'order_id' => 1,
            'payment_method_id' => 2,
            'user_id' => 5,
            'payment_method' => 'Dinheiro',
            'user' => 'Admin'
        ];

        // Simula itens do pedido
        $items = [
            ['item_id' => 10, 'product_id' => 3, 'amount' => 2, 'name' => 'Produto A'],
            ['item_id' => 11, 'product_id' => 4, 'amount' => 1, 'name' => 'Produto B']
        ];

        // Mock da busca do pedido
        $stmtOrder = $this->createMock(PDOStatement::class);
        $stmtOrder->expects($this->once())
                  ->method('execute')
                  ->with([$orderId]);
        $stmtOrder->expects($this->once())
                  ->method('fetch')
                  ->with(PDO::FETCH_ASSOC)
                  ->willReturn($orderData);

        // Mock da busca dos itens
        $stmtItems = $this->createMock(PDOStatement::class);
        $stmtItems->expects($this->once())
                  ->method('execute')
                  ->with([$orderId]);
        $stmtItems->expects($this->once())
                  ->method('fetchAll')
                  ->with(PDO::FETCH_ASSOC)
                  ->willReturn($items);

        // Mock do PDO para retornar os dois statement mocks sequencialmente
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->exactly(2))
                ->method('prepare')
                ->with($this->callback(function ($sql) {
                    return str_contains($sql, 'FROM orders') || str_contains($sql, 'FROM items');
                }))
                ->willReturnCallback(function ($sql) use ($stmtOrder, $stmtItems) {
                    if (str_contains($sql, 'FROM orders')) {
                        return $stmtOrder;
                    }
                    if (str_contains($sql, 'FROM items')) {
                        return $stmtItems;
                    }
                    return null;
                });

        $orderModel = new Order($pdoMock);
        $result = $orderModel->find($orderId);

        $this->assertIsArray($result);
        $this->assertEquals('Dinheiro', $result['payment_method']);
        $this->assertCount(2, $result['items']);
        $this->assertEquals('Produto A', $result['items'][0]['name']);
    }

    /**
     * Testa se o método find retorna null quando o pedido não existe.
     */
    public function testFindShouldReturnNullIfNotFound()
    {
        $orderId = 999;

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with([$orderId]);
        $stmtMock->expects($this->once())
                 ->method('fetch')
                 ->with(PDO::FETCH_ASSOC)
                 ->willReturn(false);

        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with($this->stringContains('FROM orders'))
                ->willReturn($stmtMock);

        $orderModel = new Order($pdoMock);
        $result = $orderModel->find($orderId);

        $this->assertNull($result);
    }
}
