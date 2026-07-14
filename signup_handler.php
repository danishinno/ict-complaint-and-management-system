<?php
include 'db_connect.php';

/** @var PDO $conn */
if (!isset($conn) || !($conn instanceof PDO)) {
    throw new RuntimeException('Database connection is not available.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = trim($_POST['userId'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = trim($_POST['role'] ?? '');

    if ($userId === '' || $password === '' || $role === '') {
        echo "Please provide an ID, password, and role.";
        exit();
    }

    $additional_info = [];
    foreach ($_POST as $key => $value) {
        if (!in_array($key, ['userId', 'password', 'role'], true)) {
            $additional_info[$key] = $value;
        }
    }

    $additional_info_json = json_encode($additional_info);
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (user_id, password, role, additional_info) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $passwordHash, $role, $additional_info_json]);
        header("Location: Login.html");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>
