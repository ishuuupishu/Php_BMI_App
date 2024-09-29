<?php
// dashboard.php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: html/login.html");
    exit();
}

$username = $_SESSION['username'];
$AppUserID = $_SESSION['AppUserID'];

// Fetch all BMI records joined with BMIUsers
$sql = "SELECT BMIRecords.RecordID, BMIUsers.Name, BMIUsers.Age, BMIUsers.Gender, BMIRecords.Height, BMIRecords.Weight, BMIRecords.BMI, BMIRecords.RecordedAt
        FROM BMIRecords
        JOIN BMIUsers ON BMIRecords.BMIUserID = BMIUsers.BMIUserID
        ORDER BY BMIRecords.RecordedAt DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BMI PHP App</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <a href="html/bmi_calculator.html">Calculate BMI</a>
    <h3>Your BMI Records</h3>
    <div class="table-container">
        <table>
            <tr>
                <th>Record ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Height (cm)</th>
                <th>Weight (kg)</th>
                <th>BMI</th>
                <th>Recorded At</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['RecordID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Age']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Height']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Weight']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['BMI']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['RecordedAt']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No BMI records found.</td></tr>";
            }
            ?>
        </table>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
