<?php
include('../../includes/config.php');

if (isset($_GET['action'])) {
    if ($_GET['action'] === 'get_students' && isset($_GET['session'])) {
        $session = $_GET['session'];
        try {
            $stmt = $pdo->prepare("SELECT id, name FROM students WHERE session = :session ORDER BY id");
            $stmt->bindParam(':session', $session, PDO::PARAM_STR);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($students ?: []);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    } elseif ($_GET['action'] === 'get_courses' && isset($_GET['semester'])) {
        $semester = $_GET['semester'];
        try {
            $stmt = $pdo->prepare("SELECT course_code, course_title, credit FROM courses WHERE semester = :semester ORDER BY course_code");
            $stmt->bindParam(':semester', $semester, PDO::PARAM_STR);
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            header('Content-Type: application/json');
            echo json_encode($courses ?: []);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
}

$error = $success = '';
$courses = [];

try {
    $sessionStmt = $pdo->query("SELECT DISTINCT session FROM students ORDER BY session");
    $sessions = $sessionStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error = "Error fetching data: " . $e->getMessage();
}

function calculateGradePoint($marks, $credit)
{
    if ($credit == 3) {
        if ($marks >= 80 && $marks <= 100) return 4.00;
        elseif ($marks >= 75) return 3.75;
        elseif ($marks >= 70) return 3.50;
        elseif ($marks >= 65) return 3.25;
        elseif ($marks >= 60) return 3.00;
        elseif ($marks >= 55) return 2.75;
        elseif ($marks >= 50) return 2.50;
        elseif ($marks >= 45) return 2.25;
        elseif ($marks >= 40) return 2.00;
        elseif ($marks >= 0) return 0.00;
        else return 0.00;
    } else {
        if ($marks >= 40 && $marks <= 50) return 4.00;
        elseif ($marks >= 37.5) return 3.75;
        elseif ($marks >= 35) return 3.50;
        elseif ($marks >= 32.5) return 3.25;
        elseif ($marks >= 30) return 3.00;
        elseif ($marks >= 27.5) return 2.75;
        elseif ($marks >= 25) return 2.50;
        elseif ($marks >= 22.5) return 2.25;
        elseif ($marks >= 20) return 2.00;
        elseif ($marks >= 0) return 0.00;
        else return 0.00;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $session = isset($_POST['session']) ? trim($_POST['session']) : '';
    $semester = isset($_POST['semester']) ? trim($_POST['semester']) : '';
    $marks_obtained = isset($_POST['marks_obtained']) ? $_POST['marks_obtained'] : [];

    if (empty($student_id) || empty($session) || empty($semester) || empty($marks_obtained)) {
        $error = "All fields are required.";
    } else {
        try {
            $courseStmt = $pdo->prepare("SELECT course_code, credit FROM courses WHERE semester = :semester");
            $courseStmt->bindParam(':semester', $semester, PDO::PARAM_STR);
            $courseStmt->execute();
            $courses_data = $courseStmt->fetchAll(PDO::FETCH_ASSOC);
            $course_credits = array_column($courses_data, 'credit', 'course_code');
            $course_codes = array_column($courses_data, 'course_code');

            foreach ($marks_obtained as $course_code => $mark) {
                if (!in_array($course_code, $course_codes)) {
                    $error = "Invalid course code: $course_code.";
                    break;
                }
                $credit = $course_credits[$course_code];
                if ($credit == 3) {
                    if (!is_numeric($mark) || $mark < 0 || $mark > 100) {
                        $error = "Marks for $course_code (3 credits) must be between 0 and 100.";
                        break;
                    }
                } else {
                    if (!is_numeric($mark) || $mark < 0 || $mark > 50) {
                        $error = "Marks for $course_code ($credit credits) must be between 0 and 50.";
                        break;
                    }
                }
            }

            if (empty($error)) {
                $checkSql = "SELECT course_code FROM results WHERE student_id = :student_id AND semester = :semester";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                $checkStmt->bindParam(':semester', $semester, PDO::PARAM_STR);
                $checkStmt->execute();
                $existing_courses = array_column($checkStmt->fetchAll(PDO::FETCH_ASSOC), 'course_code');

                $duplicates = array_intersect(array_keys($marks_obtained), $existing_courses);
                if (!empty($duplicates)) {
                    $error = "Results already added for courses: " . implode(", ", $duplicates);
                } else {
                    $insertSql = "INSERT INTO results (student_id, course_code, session, semester, marks_obtained, grade) 
                                  VALUES (:student_id, :course_code, :session, :semester, :marks_obtained, :grade)";
                    $stmt = $pdo->prepare($insertSql);

                    foreach ($marks_obtained as $course_code => $mark) {
                        $credit = $course_credits[$course_code];
                        $grade_point = calculateGradePoint((float)$mark, $credit);
                        $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
                        $stmt->bindValue(':course_code', $course_code, PDO::PARAM_STR);
                        $stmt->bindValue(':session', $session, PDO::PARAM_STR);
                        $stmt->bindValue(':semester', $semester, PDO::PARAM_STR);
                        $stmt->bindValue(':marks_obtained', (int)$mark, PDO::PARAM_INT);
                        $stmt->bindValue(':grade', $grade_point, PDO::PARAM_STR);
                        $stmt->execute();
                    }
                    $success = "Results for all courses in semester '$semester' have been successfully added!";
                }
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
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
    <title>Add Result - RAS Cordinator</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
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

        .alert-success .btn-primary {
            background: linear-gradient(90deg, #4caf50, #81c784);
            margin-left: 10px;
        }

        .alert-success .btn-primary:hover {
            background: linear-gradient(90deg, #81c784, #4caf50);
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

        .marks-input,
        .grade-display {
            width: 100%;
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

            .row.mb-3 {
                flex-direction: column;
            }

            .row.mb-3>div {
                margin-bottom: 10px;
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
                    <h1 class="mt-4">Add Result</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="./coordinator_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Add Result</li>
                    </ol>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success results-view-alert" role="alert">
                            <?php echo htmlspecialchars($success); ?>
                            <a href="view_results.php" class="btn btn-primary">View Results</a>
                        </div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger results-view-alert" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="results-view-card">
                        <div class="card-header">
                            <i class="fas fa-plus me-1"></i>
                            Add New Result
                        </div>
                        <div class="card-body results-view-form">
                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="session" class="form-label">Session:</label>
                                    <select class="form-select" id="session" name="session" required>
                                        <option value="">-- Select Session --</option>
                                        <?php foreach ($sessions as $session_option): ?>
                                            <option value="<?php echo htmlspecialchars($session_option); ?>">
                                                <?php echo htmlspecialchars($session_option); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="student_id" class="form-label">Student:</label>
                                    <select class="form-select" id="student_id" name="student_id" required>
                                        <option value="">-- Select Student --</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="semester" class="form-label">Semester:</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="">-- Select Semester --</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="31">31</option>
                                        <option value="32">32</option>
                                        <option value="41">41</option>
                                        <option value="42">42</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="courses_container">
                                </div>

                                <button type="submit" class="btn btn-primary">Add All Results</button>
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
    <script>
        $(document).ready(function() {
            const sessionSelect = $('#session');
            const studentSelect = $('#student_id');
            const semesterSelect = $('#semester');
            const coursesContainer = $('#courses_container');

            sessionSelect.on('change', function() {
                const selectedSession = $(this).val();
                if (selectedSession) {
                    studentSelect.empty().append('<option value="">Loading...</option>');
                    $.ajax({
                        url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                        type: 'GET',
                        data: {
                            action: 'get_students',
                            session: selectedSession
                        },
                        dataType: 'json',
                        success: function(data) {
                            studentSelect.empty().append('<option value="">-- Select Student --</option>');
                            if (data.length > 0) {
                                $.each(data, function(index, student) {
                                    studentSelect.append(
                                        `<option value="${student.id}">${student.id} - ${student.name}</option>`
                                    );
                                });
                            } else {
                                studentSelect.append('<option value="">No students found</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            studentSelect.empty().append('<option value="">Error loading students</option>');
                        }
                    });
                } else {
                    studentSelect.empty().append('<option value="">-- Select Student --</option>');
                }
            });

            semesterSelect.on('change', function() {
                const selectedSemester = $(this).val();
                if (selectedSemester) {
                    coursesContainer.empty().html('<p>Loading courses...</p>');
                    $.ajax({
                        url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                        type: 'GET',
                        data: {
                            action: 'get_courses',
                            semester: selectedSemester
                        },
                        dataType: 'json',
                        success: function(data) {
                            coursesContainer.empty();
                            if (data.length > 0) {
                                $.each(data, function(index, course) {
                                    const maxMarks = course.credit == 3 ? 100 : 50;
                                    const courseHtml = `
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">${course.course_code} - ${course.course_title} (${course.credit} credits)</label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control marks-input" 
                                                       name="marks_obtained[${course.course_code}]" 
                                                       min="0" max="${maxMarks}" required>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control grade-display" 
                                                       readonly value="-- Grade --" data-credit="${course.credit}">
                                            </div>
                                        </div>`;
                                    coursesContainer.append(courseHtml);
                                });

                                $('.marks-input').on('input', function() {
                                    const marks = parseFloat($(this).val());
                                    const gradeDisplay = $(this).closest('.row').find('.grade-display');
                                    const credit = parseFloat(gradeDisplay.data('credit'));
                                    let grade = '';

                                    if (credit == 3) {
                                        if (marks >= 80 && marks <= 100) grade = 'A+';
                                        else if (marks >= 75) grade = 'A';
                                        else if (marks >= 70) grade = 'A-';
                                        else if (marks >= 65) grade = 'B+';
                                        else if (marks >= 60) grade = 'B';
                                        else if (marks >= 55) grade = 'B-';
                                        else if (marks >= 50) grade = 'C+';
                                        else if (marks >= 45) grade = 'C';
                                        else if (marks >= 40) grade = 'D';
                                        else if (marks >= 0) grade = 'F';
                                        else grade = '-- Grade --';
                                    } else {
                                        if (marks >= 40 && marks <= 50) grade = 'A+';
                                        else if (marks >= 37.5) grade = 'A';
                                        else if (marks >= 35) grade = 'A-';
                                        else if (marks >= 32.5) grade = 'B+';
                                        else if (marks >= 30) grade = 'B';
                                        else if (marks >= 27.5) grade = 'B-';
                                        else if (marks >= 25) grade = 'C+';
                                        else if (marks >= 22.5) grade = 'C';
                                        else if (marks >= 20) grade = 'D';
                                        else if (marks >= 0) grade = 'F';
                                        else grade = '-- Grade --';
                                    }

                                    gradeDisplay.val(grade);
                                });
                            } else {
                                coursesContainer.html('<p>No courses found for this semester.</p>');
                            }
                        },
                        error: function(xhr, status, error) {
                            coursesContainer.html('<p>Error loading courses.</p>');
                        }
                    });
                } else {
                    coursesContainer.empty();
                }
            });
        });
    </script>
</body>

</html>