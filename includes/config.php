<?php

// Database credentials
$host = 'localhost';
$dbname = 'ras';
$dbuser = 'root';
$dbpass = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo 'database connected';
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
