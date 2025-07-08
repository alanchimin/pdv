<?php
namespace App\config;

use PDO;
use PDOException;
use RuntimeException;
use Dotenv\Dotenv;


class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $envPath = dirname(__DIR__, 2) . '/.env';

            if (!file_exists($envPath)) {
                throw new RuntimeException(".env file not found at: $envPath");
            }

            // Carrega as variáveis do .env
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
            $dotenv->load();

            $host    = getenv('DB_HOST') ?: 'localhost';
            $db      = getenv('DB_NAME') ?: 'pdv';
            $user    = getenv('DB_USER') ?: 'root';
            $pass    = getenv('DB_PASS') ?: '';
            $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset"
                ]);
            } catch (PDOException $e) {
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
