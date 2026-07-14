<?php
session_start();
include 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image_path = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Generate a unique name for the image to prevent name collisions
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $ext;
        $image_path = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            die("Failed to upload image.");
        }
    }

    // Get current logged-in user if exists
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Insert complaint into the database
    $sql = "INSERT INTO complaints (user_id, category, location, description, image, status) VALUES (?, ?, ?, ?, ?, 'Unsolved')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $user_id, $category, $location, $description, $image_path);
    
    if ($stmt->execute() === TRUE) {
        // Redirect to Complaint.php (which we will create/map) or index.php
        header("Location: index.php?status=success");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>
