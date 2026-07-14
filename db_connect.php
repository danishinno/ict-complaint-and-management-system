<?php
$dbname = "complaint_management"; 

// List of common local MySQL configurations to try (using 127.0.0.1 forces TCP/IP to respect the port number on macOS)
$configs = [
    ['host' => '127.0.0.1', 'port' => 88, 'user' => 'root', 'pass' => 'abc123'],   // User's custom port via TCP
    ['host' => 'localhost', 'port' => null, 'user' => 'root', 'pass' => 'abc123'], // User's password via socket
    ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => 'abc123'], // User's password via default TCP
    ['host' => '127.0.0.1', 'port' => 8889, 'user' => 'root', 'pass' => 'root'],   // MAMP default TCP
    ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => 'root'],   // MAMP alternative TCP
    ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => ''],       // XAMPP/WAMP default TCP
    ['host' => 'localhost', 'port' => null, 'user' => 'root', 'pass' => ''],       // General socket default
    ['host' => '127.0.0.1', 'port' => null, 'user' => 'root', 'pass' => '']
];

$conn = null;
$connected = false;
$errors = [];

foreach ($configs as $cfg) {
    if ($cfg['port']) {
        $conn = @new mysqli($cfg['host'], $cfg['user'], $cfg['pass'], "", $cfg['port']);
    } else {
        $conn = @new mysqli($cfg['host'], $cfg['user'], $cfg['pass']);
    }

    if ($conn && !$conn->connect_error) {
        $connected = true;
        break;
    } else {
        $errors[] = "Host {$cfg['host']}:" . ($cfg['port'] ?? 'default') . " user={$cfg['user']} - " . ($conn->connect_error ?? 'Connection failed');
    }
}

if (!$connected) {
    die("Database Connection failed. Tried multiple local configurations:<br><br>" . implode("<br>", $errors));
}

// Create database if not exists
$sql_db = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if ($conn->query($sql_db) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db($dbname);

// Create users table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    user_id VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    additional_info TEXT DEFAULT NULL
)";
if ($conn->query($sql_users) !== TRUE) {
    die("Error creating users table: " . $conn->error);
}

// Create complaints table
$sql_complaints = "CREATE TABLE IF NOT EXISTS complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) DEFAULT NULL,
    category VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'Unsolved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql_complaints) !== TRUE) {
    die("Error creating complaints table: " . $conn->error);
}

// Seed default admin if none exists
$admin_check = $conn->query("SELECT * FROM users WHERE role = 'admin'");
if ($admin_check && $admin_check->num_rows == 0) {
    $admin_pass = password_hash('admin123', PASSWORD_BCRYPT);
    $conn->query("INSERT INTO users (user_id, password, role, additional_info) VALUES ('admin', '$admin_pass', 'admin', '{}')");
}
?>
