<?php
session_start();
error_reporting(0);
include('../../includes/config.php');
$email = 'saifbashar2021@gmail.com';
$password = '123';
try {
    $stmt = $pdo->query("SELECT * FROM admin");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo $users;
    for ($i = 0; $i < count($users); $i++) {
        echo $users[$i]['id'];
    }
} catch (PDOException $e) {
    die("Failed to fetch users: " . $e->getMessage());
}
