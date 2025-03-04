<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

if ($_SESSION['status']) {
    echo "<script type='text/javascript'> document.location = 'student_dashboard.php'; </script>";
}

if (isset($_POST['login'])) {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :student_id AND password = :password AND status = 1");
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            $_SESSION['status'] = '1';
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['name'];
            $_SESSION['student_session'] = $student['session'];
            echo "<script type='text/javascript'> document.location = 'student_dashboard.php'; </script>";
        } else {
            echo "<script>alert('Invalid Details');</script>";
        }
    } catch (PDOException $e) {
        die("Failed to fetch student: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login as Student</title>
    <link rel="stylesheet" href="../../boostrap/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">Result Archive System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Student - Login</h1>
            <p class="lead">Access your academic results and details</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">Login as Student</h3>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="student_id" name="student_id"
                                        placeholder="Enter your Student ID" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter your Password" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2025 Result Archive System. All Rights Reserved.</p>
    </footer>

    <script src="../../boostrap/bootstrap.bundle.min.js"></script>

</body>

</html>