<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

if ($_SESSION['status'] == '') {
    echo "<script type='text/javascript'> document.location = 'login_as_sa.php'; </script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo $_POST['id'];
    $id = isset($_POST['id']) ? trim($_POST['id']) : null;

    if (empty($id)) {
        header("Location: failed_page.php?error=" . urlencode("Invalid input. Student ID is missing."));
        exit;
    }

    try {
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: delete_success_page.php");
            exit;
        } else {
            header("Location: delete_failed_page.php?error=" . urlencode("Failed to delete student."));
            exit;
        }
    } catch (PDOException $e) {
        header("Location: failed_page.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: failed_page.php?error=" . urlencode("Invalid request. Please submit the form properly."));
    exit;
}
