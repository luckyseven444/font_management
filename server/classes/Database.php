<?php
class Database {
    public $pdo;

    public function __construct() {
        $this->connectDB();
    }

    private function connectDB() {
        $host = 'localhost:3306';  // Your database host
        $db = 'zeepto';       // Your database name
        $user = 'test';  // Your database username
        $pass = 'test';  // Your database password
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function insertFile($fileName) {
        $sql = "INSERT INTO files (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $fileName]);
        return $this->pdo->lastInsertId(); // Return the ID of the inserted row
    }

    // New method to close the database connection
    public function close() {
        $this->pdo = null;  // This closes the connection
    }
}
