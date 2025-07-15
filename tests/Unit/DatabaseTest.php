<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\config\Database;
use PDO;
use RuntimeException;

class DatabaseTest extends TestCase
{
    /**
     * Limpa a instância singleton antes de cada teste.
     */
    protected function setUp(): void
    {
        $reflection = new \ReflectionClass(Database::class);
        $prop = $reflection->getProperty('instance');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    /**
     * Testa exceção ao tentar obter instância sem arquivo .env presente.
     */
    public function testThrowsIfEnvNotFound()
    {
        $envPath = dirname(__DIR__, 2) . '/.env';

        // Renomeia para simular ausência do arquivo
        if (file_exists($envPath)) {
            rename($envPath, "$envPath.bak");
        }

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/\.env file not found/');

        try {
            Database::getInstance();
        } finally {
            // Restaura o arquivo .env após o teste
            if (file_exists("$envPath.bak")) {
                rename("$envPath.bak", $envPath);
            }
        }
    }

    /**
     * Testa a criação da instância PDO real.
     * Requer container MySQL acessível com .env configurado.
     */
    public function testCanCreatePdoInstance()
    {
        $envPath = dirname(__DIR__, 2) . '/.env';
        copy($envPath, "$envPath.bak");

        // Atenção: ajuste DB_HOST para hostname do seu container MySQL (ex: 'mysql')
        file_put_contents($envPath, <<<ENV
DB_HOST=mysql
DB_NAME=pdv
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
ENV);

        try {
            $pdo = Database::getInstance();
            $this->assertInstanceOf(PDO::class, $pdo);
        } finally {
            // Restaura .env original
            rename("$envPath.bak", $envPath);
        }
    }
}
