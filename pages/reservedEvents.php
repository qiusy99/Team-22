#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../process/loginProcess.php"); 
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Database connection
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Retrieve reserved events for the user
$sql = "SELECT Events.name 
        FROM Reservations 
        JOIN Events ON Reservations.idEvent = Events.id 
        WHERE Reservations.idLogin = ?";
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $mysqli->error);
}
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die("Error executing statement: " . $stmt->error);
}
$result = $stmt->get_result();
if ($result === false) {
    die("Error getting result: " . $stmt->error);
}

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

// Close the result set and database connection
$result->close();
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserved Events</title>
</head>
<body>
    <h1>Your Reserved Events</h1>
    <ul>
        <?php foreach ($events as $event): ?>
            <li><?php echo htmlspecialchars($event['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
