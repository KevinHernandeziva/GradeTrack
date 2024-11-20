<?php
include('db.php');

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'teacher') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['attendance']) && isset($_POST['course_id']) && isset($_POST['student_name']) && !empty($_POST['attendance']) && !empty($_POST['course_id']) && !empty($_POST['student_name'])) {
        $attendance = $_POST['attendance'];
        $course_id = $_POST['course_id'];
        $student_name = $_POST['student_name'];
        
        try {
            $query = "INSERT INTO attendance (attendance, course_id, student_name) VALUES (:attendance, :course_id, :student_name)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':attendance', $attendance);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':student_name', $student_name);
            $stmt->execute();

            $message = "Attendance submitted successfully!";
        } catch (PDOException $e) {
            $message = "Error submitting attendance: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all the fields.";
    }
}

$courses = $pdo->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Attendance</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <nav>
        <ul>
            <li><a href="teacher_dashboard.php">Dashboard</a></li>
            <li><a href="submit_grade.php">Submit Grade</a></li>
            <li><a href="attendance.php">Submit Attendance</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="container">
        <h2>Submit Attendance</h2>

        <?php if (isset($message)) echo "<p>$message</p>"; ?>

        <form method="POST" action="">
            <label for="student_name">Student Name:</label>
            <select name="student_name" id="student_name" required>
                <option value="">Choose a student</option>
                <option value="Saidou">Saidou</option>
                <option value="Kevin">Kevin</option>
                <option value="Andres">Andres</option>
            </select>

            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id" required>
                <option value="">Choose a course</option>
                <?php
                foreach ($courses as $course) {
                    echo "<option value='{$course['course_id']}'>{$course['course_name']}</option>";
                }
                ?>
            </select>

            <label for="attendance">Attendance:</label>
            <select name="attendance" id="attendance" required>
                <option value="">Choose attendance status</option>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>

            <button type="submit">Submit Attendance</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 GradeTrack</p>
    </footer>

</body>
</html>
