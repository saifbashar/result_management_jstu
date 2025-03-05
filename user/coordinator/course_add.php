<?php
include('../../includes/config.php');

$semester = $course_code = $course_title = $credit = $mark = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester = isset($_POST['semester']) ? trim($_POST['semester']) : '';
    $course_code = isset($_POST['course_code']) ? trim($_POST['course_code']) : '';
    $course_title = isset($_POST['course_title']) ? trim($_POST['course_title']) : '';
    $credit = isset($_POST['credit']) ? trim($_POST['credit']) : '';
    $mark = isset($_POST['mark']) ? trim($_POST['mark']) : '';

    if (empty($semester) || empty($course_code) || empty($course_title) || empty($credit) || empty($mark)) {
        $error = "Invalid input. Please ensure all fields are filled.";
    } else {
        try {
            $sql_check = "SELECT COUNT(*) AS count FROM courses WHERE course_code = :course_code";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':course_code', $course_code, PDO::PARAM_STR);
            $stmt_check->execute();
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $error = "Error: Course Code '$course_code' already exists. Please use a unique Course Code.";
            } else {
                $sql = "INSERT INTO courses (semester, course_code, course_title, credit, mark) 
                        VALUES (:semester, :course_code, :course_title, :credit, :mark)";
                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
                $stmt->bindParam(':course_code', $course_code, PDO::PARAM_STR);
                $stmt->bindParam(':course_title', $course_title, PDO::PARAM_STR);
                $stmt->bindParam(':credit', $credit, PDO::PARAM_INT);
                $stmt->bindParam(':mark', $mark, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    header("Location: course_add_success.php");
                    exit;
                } else {
                    $error = "Failed to add course.";
                }
            }
        } catch (PDOException $e) {
            header("Location: course_add_failed.php?error=" . urlencode($e->getMessage()));
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
    <title>Add Course - RAS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
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

        .btn-danger {
            background: linear-gradient(90deg, #d32f2f, #f44336);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            font-size: 1em;
            color: #fff;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(90deg, #f44336, #d32f2f);
            transform: scale(1.05);
        }

        .result-container {
            opacity: 0;
            transition: opacity 0.6s ease-in-out;
        }

        .result-container.visible {
            opacity: 1;
        }

        .student-details {
            background: linear-gradient(135deg, #e8f5e9, #bbdefb);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 6px solid #0288d1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .student-details p {
            margin: 8px 0;
            font-size: 1.1em;
            color: #263238;
            font-family: 'Open Sans', sans-serif;
        }

        .gpa-display {
            background: linear-gradient(90deg, #42a5f5, #81d4fa);
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-family: 'Open Sans', sans-serif;
            font-weight: 700;
            font-size: 1.3em;
            color: #263238;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
        }

        .results-view-table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .results-view-table thead th {
            background: linear-gradient(90deg, #1976d2, #42a5f5);
            color: #fff;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            padding: 15px;
            font-size: 1em;
        }

        .results-view-table tbody tr {
            transition: background 0.3s ease;
        }

        .results-view-table tbody tr:hover {
            background: #e8f5e9;
        }

        .results-view-table tbody td {
            font-family: 'Open Sans', sans-serif;
            padding: 12px;
            color: #37474f;
            font-size: 1em;
        }

        .results-view-table .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: nowrap;
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

            .results-view-table thead th {
                font-size: 0.9em;
                padding: 10px;
            }

            .results-view-table tbody td {
                font-size: 0.9em;
                padding: 8px;
            }

            .results-view-table .actions {
                flex-direction: column;
                gap: 5px;
            }

            .btn-primary,
            .btn-danger {
                padding: 8px 15px;
                font-size: 0.9em;
            }
        }

        @media (max-width: 576px) {
            .results-view-table {
                display: block;
                overflow-x: auto;
            }

            .results-view-table thead th,
            .results-view-table tbody td {
                min-width: 100px;
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
                    <h1 class="mt-4">Add Course</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="./admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Course</li>
                    </ol>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger results-view-alert" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <div class="results-view-card">
                        <div class="card-header">
                            <i class="fas fa-plus me-1"></i>
                            Add New Course
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" class="results-view-form">
                                <div class="mb-3">
                                    <label for="semester" class="form-label">Semester:</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="">-- Select Semester --</option>
                                        <option value="11" <?php echo ($semester == '11') ? 'selected' : ''; ?>>11</option>
                                        <option value="12" <?php echo ($semester == '12') ? 'selected' : ''; ?>>12</option>
                                        <option value="21" <?php echo ($semester == '21') ? 'selected' : ''; ?>>21</option>
                                        <option value="22" <?php echo ($semester == '22') ? 'selected' : ''; ?>>22</option>
                                        <option value="31" <?php echo ($semester == '31') ? 'selected' : ''; ?>>31</option>
                                        <option value="32" <?php echo ($semester == '32') ? 'selected' : ''; ?>>32</option>
                                        <option value="41" <?php echo ($semester == '41') ? 'selected' : ''; ?>>41</option>
                                        <option value="42" <?php echo ($semester == '42') ? 'selected' : ''; ?>>42</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="course_code" class="form-label">Course Code:</label>
                                    <input type="text" class="form-control" id="course_code" name="course_code" value="<?php echo htmlspecialchars($course_code); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="course_title" class="form-label">Course Title:</label>
                                    <input type="text" class="form-control" id="course_title" name="course_title" value="<?php echo htmlspecialchars($course_title); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="credit" class="form-label">Credit:</label>
                                    <input type="number" class="form-control" id="credit" name="credit" value="<?php echo htmlspecialchars($credit); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="mark" class="form-label">Mark:</label>
                                    <input type="number" class="form-control" id="mark" name="mark" value="<?php echo htmlspecialchars($mark); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Course</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright © Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            · <a href="#">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>