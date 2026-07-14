<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to fetch user details
    $sql = "SELECT * FROM users WHERE user_id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user info in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            switch ($user['role']) {
                case 'student':
                    header("Location: student_dashboard.php");
                    break;
                case 'lecturer':
                    header("Location: lecturer_dashboard.php");
                    break;
                case 'technician':
                    header("Location: technician_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                default:
                    echo "Invalid role!";
            }
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid ID or password!";
    }
    $conn->close();
}
?>

