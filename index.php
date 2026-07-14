<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: " . $_SESSION['role'] . "_dashboard.php");
    exit();
} else {
    header("Location: Login.html");
    exit();
}
?>

