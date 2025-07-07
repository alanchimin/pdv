<?php
namespace App\config;

use PDO;

class Database {
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance === null) {
            $dotenv = parse_ini_file(__DIR__ . '/../../.env');
            $dsn = "mysql:host={$dotenv['DB_HOST']};dbname={$dotenv['DB_NAME']};charset=utf8mb4";
            self::$instance = new PDO($dsn, $dotenv['DB_USER'], $dotenv['DB_PASS']);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
