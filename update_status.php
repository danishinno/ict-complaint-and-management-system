<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['technician', 'admin'])) {
    header("Location: Login.html");
    exit();
}
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = intval($_POST['complaint_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $complaint_id);
    
    if ($stmt->execute()) {
        $redirect = ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'technician_dashboard.php';
        header("Location: " . $redirect . "?status=updated");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
} else {
    header("Location: Login.html");
    exit();
}
?>
