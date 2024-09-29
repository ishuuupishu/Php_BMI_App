<?php
// scripts/bmi_calculate.php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../html/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $gender = $_POST['gender'];
    $height_cm = floatval($_POST['height']);
    $weight_kg = floatval($_POST['weight']);

    // Calculate BMI
    $height_m = $height_cm / 100;
    if ($height_m > 0) {
        $bmi = $weight_kg / ($height_m * $height_m);
        $bmi = round($bmi, 2);
    } else {
        $bmi = 0;
    }

    // Insert or get BMIUserID
    // Check if the user already exists
    $stmt = $conn->prepare("SELECT BMIUserID FROM BMIUsers WHERE Name = ? AND Age = ? AND Gender = ?");
    $stmt->bind_param("sis", $name, $age, $gender);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($BMIUserID);
        $stmt->fetch();
    } else {
        // Insert new BMI user
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO BMIUsers (Name, Age, Gender) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $age, $gender);
        if ($stmt->execute()) {
            $BMIUserID = $stmt->insert_id;
        } else {
            die("Error inserting BMI user: " . $stmt->error);
        }
    }
    $stmt->close();

    // Insert BMI record
    $stmt = $conn->prepare("INSERT INTO BMIRecords (BMIUserID, Height, Weight, BMI) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iddd", $BMIUserID, $height_cm, $weight_kg, $bmi);
    if (!$stmt->execute()) {
        die("Error inserting BMI record: " . $stmt->error);
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BMI Result - BMI PHP App</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h2>BMI Calculation Result</h2>
    <div class="message success">
        <p>Name: <?php echo htmlspecialchars($name); ?></p>
        <p>Age: <?php echo $age; ?></p>
        <p>Gender: <?php echo $gender; ?></p>
        <p>Height: <?php echo $height_cm; ?> cm</p>
        <p>Weight: <?php echo $weight_kg; ?> kg</p>
        <p><strong>BMI:</strong> <?php echo $bmi; ?></p>
    </div>
    <a href="../dashboard.php">Back to Dashboard</a>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
