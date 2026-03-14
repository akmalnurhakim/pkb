<?php
// config/db.php
$DB_HOST = 'mysql';
$DB_NAME = 'mydb';
$DB_USER = 'user';
$DB_PASS = 'password';

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );

    // Set MySQL session timezone to Malaysia (UTC+8)
    $pdo->exec("SET time_zone = '+08:00'");
    
} catch (Exception $e) {
    die('DB connection error: ' . htmlspecialchars($e->getMessage()));
}
