<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    // Collect role-specific data
    $additional_info = [];
    foreach ($_POST as $key => $value) {
        if (!in_array($key, ['userId', 'password', 'role'])) {
            $additional_info[$key] = $value;
        }
    }

    $additional_info_json = json_encode($additional_info);

    // Insert user into the database
    $sql = "INSERT INTO users (user_id, password, role, additional_info) VALUES ('$userId', '$password', '$role', '$additional_info_json')";

    if ($conn->query($sql) === TRUE) {
        header("Location: Login.html");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
