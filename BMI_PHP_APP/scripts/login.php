<?php
// scripts/login.php
session_start();
require '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT AppUserID, Password FROM AppUsers WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($AppUserID, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Password is correct
            $_SESSION['username'] = $username;
            $_SESSION['AppUserID'] = $AppUserID;
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Incorrect password
            $error = "Invalid username or password.";
        }
    } else {
        // User doesn't exist
        $error = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - BMI PHP App</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h2>Login</h2>
    <?php
    if (isset($error)) {
        echo "<div class='message error'>$error</div>";
    }
    ?>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
 
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
 
        <input type="submit" value="Login">
    </form>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
