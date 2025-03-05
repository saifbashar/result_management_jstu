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
    <title>Student Login - Result Archive System</title>
    <link rel="stylesheet" href="../../boostrap/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <?php
    include('./favicon.php')

    ?>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: url('../../resources/loginSectionPage/campus.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        /* Navbar Styling (Matched with index.php) */
        .navbar {
            background: linear-gradient(90deg, #0288d1, #1976d2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8em;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: #e0f7fa !important;
            font-weight: 600;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav-link:hover {
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        /* Login Section Styling */
        .login-section {
            flex: 1;
            display: flex;
            align-items: center;
            /* Vertical centering */
            justify-content: center;
            /* Horizontal centering */
            min-height: calc(100vh - 112px);
            /* Adjust for navbar and footer */
        }

        .login-container {
            display: flex;
            justify-content: center;
            /* Ensure horizontal centering */
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .card-body {
            padding: 40px;
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 2em;
            color: #0288d1;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #37474f;
        }

        .form-control {
            border-radius: 8px;
            border: 2px solid #0288d1;
            padding: 12px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #1976d2;
            box-shadow: 0 0 10px rgba(25, 118, 210, 0.3);
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(90deg, #4caf50, #81c784);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            font-size: 1.1em;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #81c784, #4caf50);
            transform: scale(1.05);
        }

        /* Footer Styling */
        footer {
            background: linear-gradient(90deg, #0288d1, #1976d2);
            color: #ffffff;
            padding: 20px 0;
            font-family: 'Open Sans', sans-serif;
            transition: background 0.3s ease;
        }

        @media (max-width: 768px) {
            .card {
                max-width: 90%;
            }

            .card-body {
                padding: 20px;
            }

            .card-title {
                font-size: 1.5em;
            }

            .form-control {
                padding: 10px;
            }

            .btn-primary {
                padding: 10px;
                font-size: 1em;
            }

            .login-section {
                min-height: calc(100vh - 92px);
                /* Adjust for smaller navbar/footer on mobile */
            }
        }
    </style>
</head>

<body>
    <!-- Navbar (Matched with index.php) -->
    <nav class="navbar navbar-expand-lg navbar-dark" data-aos="fade-down" data-aos-duration="1000">
        <div class="container">
            <a class="navbar-brand" href="../../index.php">Result Archive System - JSTU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="../../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../about.php">About</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Section -->
    <section class="login-section">
        <div class="login-container">
            <div class="card" data-aos="fade-up" data-aos-duration="1000">
                <div class="card-body">
                    <h3 class="card-title">Student Login</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="student_id" class="form-label">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter your Student ID" required>
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
    </section>

    <!-- Footer -->
    <?php
    include("../../footer.php")
    ?>
    <!-- AOS Library JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../../boostrap/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize AOS
        AOS.init();
    </script>
</body>

</html>