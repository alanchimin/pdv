<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\models\Screen;
use PDOStatement;
use PDO;

class ScreenTest extends TestCase
{
    /**
     * Testa se getScreensByUser retorna corretamente os dados simulados.
     */
    public function testGetScreensByUserReturnsScreens()
    {
        // Simula dados retornados do banco
        $mockScreens = [
            ['screen_id' => 1, 'name' => 'Dashboard'],
            ['screen_id' => 2, 'name' => 'Relatórios'],
        ];

        // Mock de PDOStatement
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->expects($this->once())
                 ->method('execute')
                 ->with([123]);

        $stmtMock->expects($this->once())
                 ->method('fetchAll')
                 ->with(PDO::FETCH_ASSOC)
                 ->willReturn($mockScreens);

        // Mock de PDO
        $pdoMock = $this->createMock(PDO::class);
        $pdoMock->expects($this->once())
                ->method('prepare')
                ->with($this->stringContains('FROM users u'))
                ->willReturn($stmtMock);

        // Cria instância de Screen com PDO mockado
        $screenModel = new Screen($pdoMock);
        $result = $screenModel->getScreensByUser(123);

        // Verifica retorno
        $this->assertEquals($mockScreens, $result);
    }
}
