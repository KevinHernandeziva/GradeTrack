<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: index.php");  
    exit();
}

include('db.php');

$courses = $pdo->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - GradeTrack</title>
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
            <h1>Teacher Dashboard</h1>
            <p>Welcome to your dashboard. You can submit attendance here and submit grades in the "Submit Grade" section.</p>
        </div>
    </section>

    <section class="dashboard-content">
        <h2>Manage Your Classes</h2>
        <p>Use this dashboard to submit attendance and submit grades.</p>
    </section>

</main>

<footer>
    <p>&copy; 2024 GradeTrack </p>
</footer>

</body>
</html>
