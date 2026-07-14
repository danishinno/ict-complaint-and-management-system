<?php
$dbFile = __DIR__ . '/complaint_management.sqlite';

try {
    $conn = new PDO('sqlite:' . $dbFile);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec('PRAGMA foreign_keys = ON');
} catch (PDOException $e) {
    die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
}

$sqlUsers = "CREATE TABLE IF NOT EXISTS users (
    user_id TEXT PRIMARY KEY,
    password TEXT NOT NULL,
    role TEXT NOT NULL,
    additional_info TEXT DEFAULT NULL
)";
$conn->exec($sqlUsers);

$sqlComplaints = "CREATE TABLE IF NOT EXISTS complaints (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id TEXT DEFAULT NULL,
    category TEXT NOT NULL,
    location TEXT NOT NULL,
    description TEXT NOT NULL,
    image TEXT DEFAULT NULL,
    status TEXT DEFAULT 'Unsolved',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
$conn->exec($sqlComplaints);

$adminCheck = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'admin'");
if ($adminCheck && $adminCheck->fetchColumn() == 0) {
    $adminPass = password_hash('admin123', PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (user_id, password, role, additional_info) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', $adminPass, 'admin', '{}']);
}
?>
