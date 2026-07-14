<?php
session_start();
include 'db_connect.php'; // Include database connection

/** @var PDO $conn */
if (!isset($conn) || !($conn instanceof PDO)) {
    throw new RuntimeException('Database connection is not available.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_path = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $ext;
        $image_path = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            die("Failed to upload image.");
        }
    }

    $user_id = $_SESSION['user_id'] ?? null;

    $sql = "INSERT INTO complaints (user_id, category, location, description, image, status) VALUES (?, ?, ?, ?, ?, 'Unsolved')";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$user_id, $category, $location, $description, $image_path])) {
        header("Location: index.php?status=success");
        exit();
    }

    echo "Error: Unable to submit complaint.";
} else {
    header("Location: index.php");
    exit();
}
?>
