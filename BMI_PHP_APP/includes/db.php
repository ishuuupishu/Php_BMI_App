<?php
// includes/db.php

$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = "";     // Replace with your DB password
$dbname = "BMI_PHP_APP";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Create AppUsers table
$sql = "CREATE TABLE IF NOT EXISTS AppUsers (
    AppUserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,  
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === FALSE) {
    die("Error creating AppUsers table: " . $conn->error);
}

// Create BMIUsers table
$sql = "CREATE TABLE IF NOT EXISTS BMIUsers (
    BMIUserID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Age INT,
    Gender ENUM('Male', 'Female', 'Other'),
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === FALSE) {
    die("Error creating BMIUsers table: " . $conn->error);
}

// Create BMIRecords table
$sql = "CREATE TABLE IF NOT EXISTS BMIRecords (
    RecordID INT AUTO_INCREMENT PRIMARY KEY,
    BMIUserID INT,
    Height FLOAT NOT NULL,
    Weight FLOAT NOT NULL,
    BMI FLOAT NOT NULL,
    RecordedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (BMIUserID) REFERENCES BMIUsers(BMIUserID) ON DELETE CASCADE
)";
if ($conn->query($sql) === FALSE) {
    die("Error creating BMIRecords table: " . $conn->error);
}

// Insert a default user if no users exist
$sql = "SELECT * FROM AppUsers";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    $default_username = "admin";
    $default_password = password_hash("password", PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO AppUsers (Username, Password) VALUES (?, ?)");
    $stmt->bind_param("ss", $default_username, $default_password);
    if ($stmt->execute() === FALSE) {
        die("Error inserting default user: " . $stmt->error);
    }
    $stmt->close();
}

?>
