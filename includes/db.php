<?php
$host = '127.0.0.1';
$db   = 'netflix_clone'; 
$user = 'root';
$port = '3307';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];



try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3307;dbname=netflix_clone",
        "root",
        "", // empty password
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>