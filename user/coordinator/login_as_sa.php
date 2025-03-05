<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

if ($_SESSION['status']) {
    echo "<script type='text/javascript'> document.location = 'coordinator_dashboard.php'; </script>";
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    try {
        // email and pass check              
        $stmt = $pdo->prepare("SELECT * FROM coordinator WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $users = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($users) {
            // session set
            $_SESSION['status'] = '1';
            $_SESSION['name'] = $users['name'];
            $_SESSION['email'] = $users['email'];
            $_SESSION['designation'] = $users['designation'];

            // log in successfull hole coordinator_dashboard e pathau
            echo "<script type='text/javascript'> document.location = 'coordinator_dashboard.php'; </script>";
        } else {
            // if error
            echo "<script>alert('Invalid Details');</script>";
        }
    } catch (PDOException $e) {
        die("Failed to fetch users: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login as Coordinator</title>
    <link rel="stylesheet" href="../../boostrap/bootstrap.min.css">
</head>

<body>
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">Result Archive System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
    </nav> -->

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Coordinator - Login</h1>
            <p class="lead">Easily manage, view, and access academic results</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-center mb-4">Login as Coordinator</h3>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your Password" required>
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