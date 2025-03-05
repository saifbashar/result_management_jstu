<?php
session_start();
error_reporting(0);
include('../../includes/config.php');

if ($_SESSION['status'] != 1) {
    echo "<script type='text/javascript'> document.location = 'login_as_sa.php'; </script>";
}

try {
    $sqlStudents = "SELECT COUNT(*) as student_count FROM students";
    $stmtStudents = $pdo->query($sqlStudents);
    $studentCount = $stmtStudents->fetch(PDO::FETCH_ASSOC)['student_count'];

    $sqlCourses = "SELECT COUNT(*) as course_count FROM courses";
    $stmtCourses = $pdo->query($sqlCourses);
    $courseCount = $stmtCourses->fetch(PDO::FETCH_ASSOC)['course_count'];

    $sqlFaculty = "SELECT COUNT(*) as faculty_count FROM coordinator";
    $stmtFaculty = $pdo->query($sqlFaculty);
    $facultyCount = $stmtFaculty->fetch(PDO::FETCH_ASSOC)['faculty_count'];

    $sqlProjects = "SELECT COUNT(*) as project_count FROM results";
    $stmtProjects = $pdo->query($sqlProjects);
    $projectCount = $stmtProjects->fetch(PDO::FETCH_ASSOC)['project_count'];

    $sqlCseFaculty = "SELECT name, designation, email, phone FROM coordinator WHERE designation LIKE '%Computer Science%' LIMIT 5";
    $stmtCseFaculty = $pdo->query($sqlCseFaculty);
    $cseFaculty = $stmtCseFaculty->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Dashboard for Jamalpur Science and Technology University" />
    <meta name="author" content="JSTU Coordinator" />
    <title>JSTU Dashboard - Coordinator</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
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
            overflow-x: hidden;
        }

        h1 {
            font-weight: 700;
            color: #1976d2;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .results-view-main {
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            margin: 20px;
            max-width: 1400px;
            animation: fadeInUp 1s ease-in-out;
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
            font-weight: 600;
            font-size: 1.4em;
        }

        .results-view-card .card-body {
            padding: 25px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #4caf50, #81c784);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 1em;
            color: #fff;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #81c784, #4caf50);
            transform: scale(1.05);
        }

        .dashboard-widget {
            background: linear-gradient(135deg, #e8f5e9, #bbdefb);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 6px solid #0288d1;
            animation: slideInLeft 1s ease-in-out;
        }

        .university-info {
            background: linear-gradient(135deg, #fff3e0, #ffebee);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: fadeInRight 1s ease-in-out;
        }

        .university-info img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
        }

        .faculty-card {
            background: #fff;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .faculty-card:hover {
            transform: scale(1.05);
        }

        .faculty-card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }

        .results-view-table {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .results-view-table thead th {
            background: linear-gradient(90deg, #1976d2, #42a5f5);
            color: #fff;
            padding: 15px;
            font-weight: 600;
        }

        .results-view-table tbody tr:hover {
            background: #e8f5e9;
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

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
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
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- include header -->
    <?php include("header.php"); ?>
    <div id="layoutSidenav">
        <?php include("layout_nav.php"); ?>
        <div id="layoutSidenav_content">
            <main class="results-view-main">
                <div class="container-fluid px-4">
                    <h1 class="mt-4 animate__animated animate__bounceIn">Welcome <?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Unknown'; ?></h1>
                    <ol class="breadcrumb mb-4">
                        <!-- <li class="breadcrumb-item active">Dashboard</li> -->
                    </ol>
                    <div class="university-info mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="../../user/superadmin/assets/images/logo.png" alt="JSTU Campus" style="height: 250px;" class="animate__animated animate__fadeIn">
                            </div>
                            <div class="col-md-8">
                                <h3>About Jamalpur Science and Technology University</h3>
                                <p>Jamalpur Science and Technology University (JSTU), established in 2017, is a leading public university in Bangladesh, located in Melandaha Upazila, Jamalpur. With a focus on scientific innovation, JSTU offers programs across four faculties, including Computer Science, Electrical Engineering, and Fisheries, supported by advanced labs like the Genetic Analyzer Machine Lab. Since its inception with 136 students in 2019, it has grown into a hub of academic excellence.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="dashboard-widget">
                                <h4>Total Students</h4>
                                <p class="display-6"><?php echo $studentCount; ?></p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="dashboard-widget">
                                <h4>Courses Offered</h4>
                                <p class="display-6"><?php echo $courseCount; ?></p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="dashboard-widget">
                                <h4>Faculty Members</h4>
                                <p class="display-6"><?php echo $facultyCount; ?></p>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <!-- <div class="dashboard-widget">
                                <h4>Research Projects</h4>
                                <p class="display-6"><?php echo $projectCount; ?></p>
                            </div> -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="results-view-card">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Enrollment Trends
                                </div>
                                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="results-view-card">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Faculty Distribution
                                </div>
                                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <div class="results-view-card">
                        <div class="card-header">
                            <i class="fas fa-users me-1"></i>
                            CSE Department Faculty
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php
                                $sampleFaculty = [
                                    ['name' => 'Dr. Mahmudul Alam', 'designation' => 'Assistant Professor', 'email' => 'john@jstu.edu', 'phone' => '123-456-7890', 'photo' => 'https://via.placeholder.com/100.png?text=John'],
                                    ['name' => 'Md Hassan Mahmood ', 'designation' => 'Assistant Professor', 'email' => 'jane@jstu.edu', 'phone' => '123-456-7891', 'photo' => 'https://via.placeholder.com/100.png?text=Jane'],
                                    ['name' => 'Md. Khabir Uddhin Ahmed', 'designation' => 'Lecturer', 'email' => 'alex@jstu.edu', 'phone' => '123-456-7892', 'photo' => 'https://via.placeholder.com/100.png?text=Alex'],
                                    ['name' => 'Md Sydur Rahman', 'designation' => 'Lecturer', 'email' => 'emily@jstu.edu', 'phone' => '123-456-7893', 'photo' => 'https://via.placeholder.com/100.png?text=Emily'],
                                    ['name' => 'Sujit Roy', 'designation' => 'Chairman', 'email' => 'mike@jstu.edu', 'phone' => '123-456-7894', 'photo' => 'https://via.placeholder.com/100.png?text=Mike']
                                ];
                                foreach ($sampleFaculty as $faculty) {
                                    echo "<div class='col-md-4 mb-3'>
                                        <div class='faculty-card text-center'>
                                            <img src='{$faculty['photo']}' alt='{$faculty['name']}' class='mb-2'>
                                            <h5>{$faculty['name']}</h5>
                                            <p>{$faculty['designation']}</p>
                                            <p>Email: {$faculty['email']}</p>
                                            <p>Phone: {$faculty['phone']}</p>
                                        </div>
                                    </div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php
            include('footer.php')
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script>
        var ctxArea = document.getElementById("myAreaChart").getContext('2d');
        new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: ["2019", "2020", "2021", "2022", "2023"],
                datasets: [{
                    label: "Students",
                    lineTension: 0.3,
                    backgroundColor: "rgba(2, 136, 209, 0.2)",
                    borderColor: "rgba(2, 136, 209, 1)",
                    data: [136, 200, 300, 450, <?php echo $studentCount; ?>]
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var ctxBar = document.getElementById("myBarChart").getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ["CSE", "EEE", "Fisheries", "Others"],
                datasets: [{
                    label: "Faculty",
                    backgroundColor: "rgba(66, 165, 245, 0.7)",
                    data: [5, <?php echo $facultyCount - 5 > 0 ? ($facultyCount - 5) / 3 : 0; ?>, <?php echo $facultyCount - 5 > 0 ? ($facultyCount - 5) / 3 : 0; ?>, <?php echo $facultyCount - 5 > 0 ? ($facultyCount - 5) / 3 : 0; ?>]
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>

</html>