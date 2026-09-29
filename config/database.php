<?php

class Database
{
    private static $instance = null;
    private $connection;

    private $host = 'localhost';
    private $dbName = 'inventaris_db';
    private $username = 'root';
    private $password = '';

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $this->connection = new PDO(
            $dsn,
            $this->username,
            $this->password,
            $options
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}