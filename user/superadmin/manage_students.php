<?php

session_start();
error_reporting(0);
include('../../includes/config.php');

if ($_SESSION['status'] == '') {
    echo "<script type='text/javascript'> document.location = 'login_as_sa.php'; </script>";
}

try {
    $sql = "SELECT id, name, session, password, status FROM students";
    $stmt = $pdo->query($sql);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching data: " . htmlspecialchars($e->getMessage());
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
    <title>Manage Students - RAS Admin</title>
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
                    <h1 class="mt-4">Manage Students</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="./admin_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Students</li>
                    </ol>
                    <div class="results-view-card">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Student Records
                            <button id="printButton" class="btn btn-primary" style="float: right;">Print</button>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered results-view-table" id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Session</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Session</th>
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    if (isset($students) && !empty($students)) {
                                        foreach ($students as $row) {
                                            echo "<tr>
                                                <td>" . htmlspecialchars($row["id"]) . "</td>
                                                <td>" . htmlspecialchars($row["name"]) . "</td>
                                                <td>" . htmlspecialchars($row["session"]) . "</td>
                                                <td>" . htmlspecialchars($row["password"]) . "</td>
                                                <td>" . htmlspecialchars($row["status"]) . "</td>
                                                <td class='actions'>
                                                    <form action='modify_student.php' method='GET' style='display:inline;'>
                                                        <input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>
                                                        <button type='submit' class='btn btn-primary'>Modify</button>
                                                    </form>
                                                    <form action='delete_student.php' method='POST' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this student?\");'>
                                                        <input type='hidden' name='id' value='" . htmlspecialchars($row["id"]) . "'>
                                                        <button type='submit' class='btn btn-danger'>Delete</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>" . (isset($error) ? $error : "No data found") . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
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
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {
                searchable: true,
                fixedHeight: true,
                layout: {
                    topStart: {
                        buttons: ['print']
                    }
                }
            });
        });
    </script>
</body>

</html>