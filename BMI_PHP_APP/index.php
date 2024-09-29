<?php
// index.php
session_start();
require 'includes/db.php';

if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: html/login.html");
    exit();
}
?>
