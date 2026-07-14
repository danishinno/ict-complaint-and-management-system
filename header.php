<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <header class="Main-header">
        <nav>
            <div class="logo">
                <img src="images/Logo-FTMK.png" alt="FTMK Logo" width="70" height="70">
                <h2>ICT Complaint Management and Centralization System</h2>
            </div>
            <div class="nav-menu">
                <ul>
                    <li><a href="Complaint.php">Complaint</a></li>
                    <li><a href="Contact.php">Contact</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?php echo $_SESSION['role']; ?>_dashboard.php">Dashboard</a></li>
                        <li><a href="logout.php" class="logout-btn">Logout</a></li>
                    <?php else: ?>
                        <li><a href="Login.html">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>