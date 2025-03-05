<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

if ($_SESSION['status'] == '') {
    echo "<script type='text/javascript'> document.location = 'login_as_sa.php'; </script>";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $session = isset($_POST['session']) ? trim($_POST['session']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    if (empty($id) || empty($name) || empty($session) || empty($password) || empty($status)) {
        header("Location: failed_page.php?error=" . urlencode("Invalid input. Please ensure all fields are filled."));
        exit;
    }

    try {
        $checkSql = "SELECT id FROM students WHERE id = :id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            header("Location: failed_page.php?error=" . urlencode("Student ID already exists. Please choose a different ID."));
            exit;
        }

        $sql = "INSERT INTO students (id, name, session, password, status) 
                VALUES (:id, :name, :session, :password, :status)";
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
            header("Location: failed_page.php?error=" . urlencode("Failed to add student."));
            exit;
        }
    } catch (PDOException $e) {
        header("Location: failed_page.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Add Student - RAS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <?php
    include('./favicon.php')

    ?>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #b3e5fc, #e8eaf6);
            color: #263238;
        }

        h1 {
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            color: #1976d2;
            font-size: 2.2em;
        }

        .results-view-main {
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            margin: 15px 20px;
            max-width: 1100px;
        }

        .results-view-card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            background: #ffffff;
            margin-bottom: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .results-view-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .results-view-card .card-header {
            background: linear-gradient(90deg, #0288d1, #42a5f5);
            color: #fff;
            border-radius: 15px 15px 0 0;
            padding: 15px 25px;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            font-size: 1.4em;
        }

        .results-view-card .card-body {
            padding: 25px;
        }

        .results-view-form .form-control,
        .results-view-form .form-select {
            border-radius: 8px;
            border: 2px solid #0288d1;
            padding: 12px;
            font-family: 'Open Sans', sans-serif;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background: #f5faff;
        }

        .results-view-form .form-control:focus,
        .results-view-form .form-select:focus {
            border-color: #1976d2;
            box-shadow: 0 0 10px rgba(25, 118, 210, 0.3);
        }

        .results-view-form .btn-primary {
            background: linear-gradient(90deg, #4caf50, #81c784);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            font-size: 1em;
            color: #fff;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .results-view-form .btn-primary:hover {
            background: linear-gradient(90deg, #81c784, #4caf50);
            transform: scale(1.05);
        }

        .breadcrumb {
            background: linear-gradient(90deg, #81d4fa, #b3e5fc);
            border-radius: 8px;
            padding: 10px;
        }

        .breadcrumb-item a {
            color: #1976d2;
            font-weight: 600;
        }

        .breadcrumb-item.active {
            color: #263238;
        }

        @media (max-width: 768px) {
            .results-view-main {
                padding: 20px;
                margin: 10px;
            }

            h1 {
                font-size: 1.8em;
            }

            .results-view-card .card-header {
                font-size: 1.2em;
                padding: 12px 20px;
            }

            .results-view-card .card-body {
                padding: 15px;
            }

            .results-view-form .form-control,
            .results-view-form .form-select {
                font-size: 0.9em;
                padding: 10px;
            }

            .results-view-form .btn-primary {
                padding: 8px 15px;
                font-size: 0.9em;
            }
        }

        @media (max-width: 576px) {
            .results-view-main {
                padding: 15px;
            }

            h1 {
                font-size: 1.5em;
            }

            .results-view-card .card-header {
                font-size: 1em;
            }

            .results-view-form .form-control,
            .results-view-form .form-select {
                font-size: 0.85em;
            }
        }
    </style>
</head>

<body>
    <?php include("header.php"); ?>
    <div id="layoutSidenav">
        <?php include("layout_nav.php"); ?>
        <div id="layoutSidenav_content">
            <main class="results-view-main">
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Add Student</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="./admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Student</li>
                    </ol>
                    <div class="results-view-card">
                        <div class="card-header">
                            <i class="fas fa-plus me-1"></i>
                            Add New Student
                        </div>
                        <div class="card-body results-view-form">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="id" class="form-label">Student ID:</label>
                                    <input type="number" class="form-control" id="id" name="id" required>
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="session" class="form-label">Session:</label>
                                    <select class="form-select" id="session" name="session" required>
                                        <option value="">-- Select Session --</option>
                                        <option value="2018-2019">2018-2019</option>
                                        <option value="2019-2020">2019-2020</option>
                                        <option value="2020-2021">2020-2021</option>
                                        <option value="2021-2022">2021-2022</option>
                                        <option value="2022-2023">2022-2023</option>
                                        <option value="2023-2024">2023-2024</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="text" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Add Student</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php
            include('./footer.php')
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>