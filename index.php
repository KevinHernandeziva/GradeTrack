<?php
session_start();
include('db.php'); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

    
    if ($role == 'student') {
        $sql = "SELECT * FROM students WHERE username = :username AND password = :password";
    } elseif ($role == 'teacher') {
        $sql = "SELECT * FROM teachers WHERE username = :username AND password = :password";
    } else {
        $message = "Invalid role selected.";
    }

    if (isset($sql)) {
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password); 
            $stmt->execute();

            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $role;

                
                if ($role == 'student') {
                    header('Location: student_dashboard.php');
                } elseif ($role == 'teacher') {
                    header('Location: teacher_dashboard.php');
                }
            } else {
                $message = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GradeTrack</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="login-container">
        <h2>Login to GradeTrack</h2>

        
        <?php if (isset($message)) echo "<p class='error'>$message</p>"; ?>

        
        <form method="POST" action="index.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">I am a:</label>
            <select name="role" id="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <button type="submit">Login</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 GradeTrack </p>
    </footer>

</body>
</html>
