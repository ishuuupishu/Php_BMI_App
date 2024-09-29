<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BMI PHP App</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>BMI PHP Application</h1>
        <nav>
            <a href="index.php">Home</a>
            <?php
            if (isset($_SESSION['username'])) {
                echo ' | <a href="dashboard.php">Dashboard</a>';
                echo ' | <a href="html/logout.html">Logout</a>';
            } else {
                echo ' | <a href="html/login.html">Login</a>';
            }
            ?>
        </nav>
    </header>
    <main>
