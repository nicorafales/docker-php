<?php

namespace App\Database;

use PDO;

class MariaDbConnection
{
    private static $instance = null;
    private PDO $pdo;

    private const OPTIONS = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    private function __construct()
    {
        $dsn = "mysql:host={$_ENV['MARIADB_HOST']};dbname={$_ENV['MARIADB_DATABASE']};port={$_ENV['MARIADB_PORT']}";

        $this->pdo = new PDO(
            dsn: $dsn, 
            username: $_ENV['MARIADB_USER'],
            password: $_ENV['MARIADB_PASSWORD'], 
            options: self::OPTIONS
        );
    }

    public static function make(): self
    {
        return self::$instance ??= new self();
    }

    public function connection(): PDO
    {
        return $this->pdo;
    }

    public static function refresh(): void
    {
        $pdo = self::make()->connection();

        $createOrDropAndRecreateDb = <<<SQL
            DROP DATABASE IF EXISTS `shophp`;
            CREATE DATABASE `shophp`
                CHARACTER SET `latin1`
                COLLATE `latin1_swedish_ci`
            ;
            USE `shophp`;
        SQL;

        $ordersTable = <<<SQL
            CREATE TABLE orders (  
                id int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary Key',
                create_time DATETIME COMMENT 'Create Time',
            )
        SQL;

        $orderItemsTable = <<<SQL
            
            CREATE TABLE `order_items` (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                order_id INT UNSIGNED NOT NULL,
                product_name VARCHAR(50) NOT NULL,
                quantity INT UNSIGNED DEFAULT 0,

                CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES orders(id)
            );
        SQL;


        $pdo->exec(''
            . $createOrDropAndRecreateDb
            . $ordersTable
            . $orderItemsTable
        );
    }
}