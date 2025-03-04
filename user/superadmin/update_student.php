<?php
include('../../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $session = isset($_POST['session']) ? trim($_POST['session']) : null;
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : null;

    if (empty($id) || empty($name) || empty($session) || empty($password) || empty($status)) {
        header("Location: failed_page.php?error=Invalid input. Please ensure all fields are filled.");
        exit;
    }

    try {
        $sql = "UPDATE students 
                SET name = :name, session = :session, password = :password, status = :status 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':session', $session, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: success_page.php");
            exit;
        } else {
            header("Location: failed_page.php?error=Failed to update student.");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: failed_page.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: failed_page.php?error=Invalid request. Please submit the form properly.");
    exit;
}
