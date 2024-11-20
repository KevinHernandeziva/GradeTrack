<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");  
    exit();
}

include('db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['grade']) && isset($_POST['course_id']) && isset($_POST['student_name']) && !empty($_POST['grade']) && !empty($_POST['course_id']) && !empty($_POST['student_name'])) {
        $grade = $_POST['grade']; 
        $course_id = $_POST['course_id']; 
        $student_name = $_POST['student_name']; 
        
        try {
            $query = "INSERT INTO grades (grade, course_id, student_name) VALUES (:grade, :course_id, :student_name)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':grade', $grade);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->bindParam(':student_name', $student_name);
            $stmt->execute();

            $message = "Grade submitted successfully!";
        } catch (PDOException $e) {
            $message = "Error submitting grade: " . $e->getMessage();
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
    <title>Submit Grade - GradeTrack</title>
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

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Submit Grades</h1>
            <p>Use this form to submit student grades for the selected course.</p>
        </div>
    </section>

    <section class="grade-submission">
        <h2>Submit Grades</h2>
        
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
            
            <label for="grade">Grade:</label>
            <select name="grade" id="grade" required>
                <option value="">Choose a grade</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="F">F</option>
            </select>

            <button type="submit">Submit Grade</button>
        </form>
    </section>

</main>

<footer>
    <p>&copy; 2024 GradeTrack </p>
</footer>

</body>
</html>
