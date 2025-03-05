<?php
session_start();
include('../../includes/config.php');

require_once __DIR__ . '/../../vendor/autoload.php';

if (isset($_GET['action']) && $_GET['action'] === 'get_student_suggestions' && isset($_GET['term'])) {
    $term = '%' . $_GET['term'] . '%';
    try {
        $stmt = $pdo->prepare("SELECT id, name FROM students WHERE id LIKE :term ORDER BY id LIMIT 10");
        $stmt->bindParam(':term', $term, PDO::PARAM_STR);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $suggestions = array_map(function ($student) {
            return ['label' => $student['id'] . ' - ' . $student['name'], 'value' => $student['id']];
        }, $students);
        header('Content-Type: application/json');
        echo json_encode($suggestions);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$error = $success = '';
$results = [];
$student_details = [];
$missing_courses = [];
$gpa = 0.0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
    $semester = isset($_POST['semester']) ? trim($_POST['semester']) : '';

    if (empty($student_id) || empty($semester)) {
        $error = "Both Student ID and Semester are required.";
    } else {
        try {
            $studentStmt = $pdo->prepare("SELECT id, name, session FROM students WHERE id = :student_id");
            $studentStmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $studentStmt->execute();
            $student = $studentStmt->fetch(PDO::FETCH_ASSOC);
            if (!$student) {
                $error = "Student ID '$student_id' does not exist.";
            } else {
                $student_details = $student;

                $courseStmt = $pdo->prepare("SELECT course_code, course_title, credit FROM courses WHERE semester = :semester");
                $courseStmt->bindParam(':semester', $semester, PDO::PARAM_STR);
                $courseStmt->execute();
                $courses = $courseStmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($courses)) {
                    $error = "No courses found for semester '$semester'.";
                } else {
                    $resultStmt = $pdo->prepare("
                        SELECT r.course_code, c.course_title, r.marks_obtained, r.grade, c.credit
                        FROM results r
                        JOIN courses c ON r.course_code = c.course_code
                        WHERE r.student_id = :student_id AND r.semester = :semester
                    ");
                    $resultStmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
                    $resultStmt->bindParam(':semester', $semester, PDO::PARAM_STR);
                    $resultStmt->execute();
                    $results = $resultStmt->fetchAll(PDO::FETCH_ASSOC);

                    $result_course_codes = array_column($results, 'course_code');
                    $all_course_codes = array_column($courses, 'course_code');
                    $missing_course_codes = array_diff($all_course_codes, $result_course_codes);

                    if (!empty($missing_course_codes)) {
                        foreach ($courses as $course) {
                            if (in_array($course['course_code'], $missing_course_codes)) {
                                $missing_courses[] = $course['course_code'] . " - " . $course['course_title'];
                            }
                        }
                        $error = "Results incomplete. Marks missing for: " . implode(", ", $missing_courses);
                    } else {
                        $success = "Results for Student ID '$student_id' in Semester '$semester':";
                        $total_credits = 0;
                        $total_grade_points = 0;
                        foreach ($results as $result) {
                            $credit = (float)$result['credit'];
                            $grade = (float)$result['grade'];
                            $total_credits += $credit;
                            $total_grade_points += $credit * $grade;
                        }
                        $gpa = $total_credits > 0 ? round($total_grade_points / $total_credits, 2) : 0.0;
                    }
                }
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }

    if (isset($_POST['print']) && !empty($success)) {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('RAS Admin');
        $pdf->SetTitle('Semester Result Sheet');
        $pdf->SetSubject('Student Result');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(false);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetXY(15, 5);
        $pdf->Cell(0, 5, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'L');

        $pdf->Image('../../resources/logo/logo.png', 90, 10, 30, 30, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetY(40);
        $pdf->Cell(0, 10, 'Jamalpur Science & Technology University', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, 'Department of Computer Science and Engineering', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'I', 11);
        $pdf->Cell(0, 8, "Semester $semester Result Sheet", 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Student Details', 0, 1);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell(50, 8, "Student ID:", 0, 0);
        $pdf->Cell(0, 8, $student_details['id'], 0, 1);
        $pdf->Cell(50, 8, "Name:", 0, 0);
        $pdf->Cell(0, 8, $student_details['name'], 0, 1);
        $pdf->Cell(50, 8, "Session:", 0, 0);
        $pdf->Cell(0, 8, $student_details['session'], 0, 1);
        $pdf->Cell(50, 8, "GPA:", 0, 0);
        $pdf->Cell(0, 8, $gpa, 0, 1);
        $pdf->Ln(10);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, 'Results', 0, 1);

        $table_width = 160;
        $pdf->SetX(($pdf->GetPageWidth() - $table_width) / 2);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetFillColor(200, 220, 255);
        $pdf->Cell(25, 8, 'Course Code', 1, 0, 'C', 1);
        $pdf->Cell(70, 8, 'Course Title', 1, 0, 'C', 1);
        $pdf->Cell(25, 8, 'Marks', 1, 0, 'C', 1);
        $pdf->Cell(20, 8, 'Grade', 1, 0, 'C', 1);
        $pdf->Cell(20, 8, 'Credit', 1, 1, 'C', 1);

        $pdf->SetFont('helvetica', '', 9);
        foreach ($results as $result) {
            $pdf->SetX(($pdf->GetPageWidth() - $table_width) / 2);
            $pdf->Cell(25, 8, $result['course_code'], 1, 0, 'C');
            $pdf->MultiCell(70, 8, $result['course_title'], 1, 'L', false, 0);
            $pdf->Cell(25, 8, $result['marks_obtained'], 1, 0, 'C');
            $pdf->Cell(20, 8, $result['grade'], 1, 0, 'C');
            $pdf->Cell(20, 8, $result['credit'], 1, 1, 'C');
        }

        // $pdf->SetY(250);
        // $pdf->SetFont('helvetica', 'B', 10);
        // $pdf->Cell(90, 8, '_______________________', 0, 0, 'L');
        // $pdf->Cell(0, 8, '_______________________', 0, 1, 'R');
        // $pdf->SetFont('helvetica', '', 9);
        // $pdf->Cell(90, 8, 'Department Chairman', 0, 0, 'L');
        // $pdf->Cell(0, 8, 'Course Coordinator', 0, 1, 'R');

        $pdf->SetY(270);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 5, 'This document is electronically generated and does not require a physical signature for validation.', 0, 1, 'C');

        $pdf->Output("Result_Sheet_{$student_id}_Semester_{$semester}.pdf", 'D');
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
    <title>View Results - RAS Cordinator</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" crossorigin="anonymous"></script>
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
            margin: 15px auto;
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
            padding: 12px 25px;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            font-size: 1.1em;
            color: #fff;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .results-view-form .btn-primary:hover {
            background: linear-gradient(90deg, #81c784, #4caf50);
            transform: scale(1.05);
        }

        .btn-print {
            background: linear-gradient(90deg, #0288d1, #42a5f5);
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-family: 'Open Sans', sans-serif;
            font-weight: 600;
            font-size: 1.1em;
            color: #fff;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-print:hover {
            background: linear-gradient(90deg, #42a5f5, #0288d1);
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
    </style>
</head>

<body>
    <?php include("header.php"); ?>
    <div id="layoutSidenav">
        <?php include("layout_nav.php"); ?>
        <div id="layoutSidenav_content">
            <main class="results-view-main">
                <div class="container-fluid px-4">
                    <h1 class="mt-4">View Results</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="./coordinator_dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">View Results</li>
                    </ol>

                    <div class="results-view-card">
                        <div class="card-header">
                            <i class="fas fa-search me-2"></i>
                            Search Student Results
                        </div>
                        <div class="card-body results-view-form">
                            <form action="" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="student_id" class="form-label">Student ID:</label>
                                        <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter Student ID" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
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
                                </div>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>

                    <?php if (!empty($success)): ?>
                        <div class="result-container" id="resultContainer">
                            <div class="student-details">
                                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($student_details['id']); ?></p>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($student_details['name']); ?></p>
                                <p><strong>Session:</strong> <?php echo htmlspecialchars($student_details['session']); ?></p>
                                <p><strong>Semester:</strong> <?php echo htmlspecialchars($semester); ?></p>
                            </div>
                            <div class="gpa-display">
                                GPA: <?php echo htmlspecialchars($gpa); ?>
                            </div>
                            <div class="results-view-card">
                                <div class="card-header">
                                    <i class="fas fa-table me-2"></i>
                                    <?php echo htmlspecialchars($success); ?>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered results-view-table">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Title</th>
                                                <th>Marks Obtained</th>
                                                <th>Grade</th>
                                                <th>Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($results as $result): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($result['course_code']); ?></td>
                                                    <td><?php echo htmlspecialchars($result['course_title']); ?></td>
                                                    <td><?php echo htmlspecialchars($result['marks_obtained']); ?></td>
                                                    <td><?php echo htmlspecialchars($result['grade']); ?></td>
                                                    <td><?php echo htmlspecialchars($result['credit']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <form action="" method="POST" style="margin-top: 20px; text-align: center;">
                                        <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>">
                                        <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">
                                        <button type="submit" name="print" class="btn btn-print">Print Result Sheet</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger results-view-alert" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
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
            $('#student_id').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                        type: 'GET',
                        data: {
                            action: 'get_student_suggestions',
                            term: request.term
                        },
                        dataType: 'json',
                        success: function(data) {
                            response(data);
                        },
                        error: function(xhr, status, error) {
                            console.error('Autocomplete Error:', status, error);
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    $('#student_id').val(ui.item.value);
                    return false;
                }
            });

            if ($('#resultContainer').length) {
                setTimeout(function() {
                    $('#resultContainer').addClass('visible');
                }, 100);
            }
        });
    </script>
</body>

</html>