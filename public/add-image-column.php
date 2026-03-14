<?php
$host = '127.0.0.1';
$dbname = 'flourish_pos';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->exec("ALTER TABLE products ADD COLUMN image VARCHAR(255)");
    echo "Column added successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
