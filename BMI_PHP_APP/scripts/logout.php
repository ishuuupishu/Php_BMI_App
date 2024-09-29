<?php
// scripts/logout.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Destroy the session.
    session_unset();
    session_destroy();
    header("Location: ../html/login.html");
    exit();
}
?>
