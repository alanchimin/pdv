<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\PaymentMethod;
use PDO;
use PDOStatement;

class PaymentMethodTest extends TestCase
{
    /**
     * Testa se o método all() retorna todos os métodos de pagamento em ordem alfabética.
     */
    public function testAllReturnsPaymentMethods()
    {
        // Simula o resultado esperado
        $mockResults = [
            ['payment_method_id' => 1, 'name' => 'Cartão'],
            ['payment_method_id' => 2, 'name' => 'Dinheiro'],
        ];

        // Cria mock de PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->with(PDO::FETCH_ASSOC)
                 ->willReturn($mockResults);

        // Cria mock de PDO
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
                ->method('query')
                ->with($this->stringContains('SELECT * FROM payment_methods ORDER BY name'))
                ->willReturn($stmtMock);

        // Cria instância do model com PDO mockado
        $model = new PaymentMethod($pdoMock);
        $result = $model->all();

        // Verificações
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Cartão', $result[0]['name']);
        $this->assertEquals('Dinheiro', $result[1]['name']);
    }
}
