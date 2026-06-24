<?php
// app/Core/Database.php
namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';

            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    $config['host'],
                    $config['database'],
                    $config['charset']
                );

                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options'] ?? $options // Cho phép ghi đè option từ config
                );
            } catch (PDOException $e) {
                logger("Database Connection Error: " . $e->getMessage(), "CRITICAL");
                
                throw new Exception("Không thể kết nối đến cơ sở dữ liệu.");
            }
        }

        return self::$instance;
    }
}