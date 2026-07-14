<?php
session_start();
include 'db_connect.php';

/** @var PDO $conn */
if (!isset($conn) || !($conn instanceof PDO)) {
    throw new RuntimeException('Database connection is not available.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = trim($_POST['userId'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($userId === '' || $password === '') {
        echo "Please provide both ID and password.";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        switch ($user['role']) {
            case 'student':
                header("Location: student_dashboard.php");
                exit();
            case 'lecturer':
                header("Location: lecturer_dashboard.php");
                exit();
            case 'technician':
                header("Location: technician_dashboard.php");
                exit();
            case 'admin':
                header("Location: admin_dashboard.php");
                exit();
            default:
                echo "Invalid role!";
                exit();
        }
    }

    echo "Invalid ID or password!";
}
?>

