<?php
// Start session at the top
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database credentials
$host = 'localhost';
$dbname = 'ras';
$dbuser = 'root';
$dbpass = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'database connected'; // Comment this out to avoid output before headers
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
