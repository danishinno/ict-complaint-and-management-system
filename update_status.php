<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['technician', 'admin'], true)) {
    header("Location: Login.html");
    exit();
}
include 'db_connect.php';

/** @var PDO $conn */
if (!isset($conn) || !($conn instanceof PDO)) {
    throw new RuntimeException('Database connection is not available.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = intval($_POST['complaint_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if ($complaint_id <= 0 || $status === '') {
        header("Location: Login.html");
        exit();
    }

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");

    if ($stmt->execute([$status, $complaint_id])) {
        $redirect = ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'technician_dashboard.php';
        header("Location: " . $redirect . "?status=updated");
        exit();
    }

    echo "Error: Unable to update complaint status.";
} else {
    header("Location: Login.html");
    exit();
}
?>
