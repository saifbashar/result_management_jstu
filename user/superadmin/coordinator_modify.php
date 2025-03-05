<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

if ($_SESSION['status'] == '') {
    echo "<script type='text/javascript'> document.location = 'login_as_sa.php'; </script>";
}

$id = $name = $email = $password = $designation = $status = $phone = '';
$error = '';

if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
    try {
        $sql = "SELECT id, name, email, designation, status, phone FROM coordinator WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $name = htmlspecialchars($row['name']);
            $email = htmlspecialchars($row['email']);
            $designation = htmlspecialchars($row['designation']);
            $status = htmlspecialchars($row['status']);
            $phone = htmlspecialchars($row['phone']);
        } else {
            $error = "Coordinator not found.";
        }
    } catch (PDOException $e) {
        $error = "Error fetching coordinator details: " . $e->getMessage();
    }
} else {
    $error = "Invalid request. No ID provided.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $designation = isset($_POST['designation']) ? trim($_POST['designation']) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (empty($id) || empty($name) || empty($email) || empty($designation) || empty($status) || empty($phone)) {
        $error = "Invalid input. Please ensure all fields are filled.";
    } else {
        try {
            $sql = "UPDATE coordinator 
                    SET name = :name, email = :email, password = :password, designation = :designation, status = :status, phone = :phone 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':designation', $designation, PDO::PARAM_STR);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            if ($stmt->execute()) {
                header("Location: coordinator_modify_success.php");
                exit;
            } else {
                $error = "Failed to update coordinator.";
            }
        } catch (PDOException $e) {
            header("Location: coordinator_modify_failed.php?error=" . urlencode($e->getMessage()));
            exit;
        }
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
    <title>Modify Coordinator - RAS Admin</title>
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

        .results-view-alert {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background: linear-gradient(135deg, #b3e5fc, #e0f7fa);
            color: #0288d1;
            font-family: 'Open Sans', sans-serif;
            font-size: 1.1em;
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
                    <h1 class="mt-4">Modify Coordinator</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="./admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Modify Coordinator</li>
                    </ol>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger results-view-alert" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <div class="results-view-card">
                        <div class="card-header">
                            <i class="fas fa-edit me-1"></i>
                            Update Coordinator Details
                        </div>
                        <div class="card-body results-view-form">
                            <form action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="designation" class="form-label">Designation:</label>
                                    <select class="form-select" id="designation" name="designation" required>
                                        <option value="">-- Select Designation --</option>
                                        <option value="Lecturer" <?php echo ($designation == 'Lecturer') ? 'selected' : ''; ?>>Lecturer</option>
                                        <option value="Assistant Professor" <?php echo ($designation == 'Assistant Professor') ? 'selected' : ''; ?>>Assistant Professor</option>
                                        <option value="Associate Professor" <?php echo ($designation == 'Associate Professor') ? 'selected' : ''; ?>>Associate Professor</option>
                                        <option value="Professor" <?php echo ($designation == 'Professor') ? 'selected' : ''; ?>>Professor</option>
                                        <option value="Chairman" <?php echo ($designation == 'Chairman') ? 'selected' : ''; ?>>Chairman</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status:</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" <?php echo ($status == '1') ? 'selected' : ''; ?>>Active</option>
                                        <option value="0" <?php echo ($status == '0') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone:</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Update Coordinator</button>
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