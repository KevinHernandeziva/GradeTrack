<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header('Location: index.php');
    exit();
}

include('db.php');

$student_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$grades_sql = "SELECT g.grade, c.course_name 
               FROM grades g 
               JOIN courses c ON g.course_id = c.course_id 
               WHERE g.student_id = :student_id";
$grades_stmt = $pdo->prepare($grades_sql);
$grades_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$grades_stmt->execute();
$grades = $grades_stmt->fetchAll(PDO::FETCH_ASSOC);

$attendance_sql = "SELECT a.attendance, c.course_name 
                   FROM attendance a 
                   JOIN courses c ON a.course_id = c.course_id 
                   WHERE a.student_name = :student_name";
$attendance_stmt = $pdo->prepare($attendance_sql);
$attendance_stmt->bindParam(':student_name', $username, PDO::PARAM_STR);
$attendance_stmt->execute();
$attendance = $attendance_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?> (Student)</h1>

        <h2>Your Grades:</h2>
        <?php if (!empty($grades)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($grade['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No grades available.</p>
        <?php endif; ?>

        <h2>Your Attendance:</h2>
        <?php if (!empty($attendance)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance as $att): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($att['course_name']); ?></td>
                            <td><?php echo htmlspecialchars($att['attendance'] == 'Present' ? 'Present' : 'Absent'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No attendance data available.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 GradeTrack</p>
    </footer>

</body>
</html>
