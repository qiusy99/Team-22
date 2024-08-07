#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$events = [];
$reservations = [];
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

// Fetch all approved events
$sql = "SELECT * FROM Events WHERE is_approved = 1";
if ($result = $mysqli->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $result->free();
}

// Fetch reservations for the logged-in user
if ($isLoggedIn) {
    $sql = "SELECT idEvent FROM Reservations WHERE idLogin = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row['idEvent'];
    }
    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Events</title>
    <link rel="stylesheet" href="../styles/Events.css">
</head>
<body>
    <!-- Log Out button -->
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
    </div>

    <div class="container">
        <div class="calendar-container">
            <div class="month-buttons">
                <button onclick="prevMonth()">Previous Month</button>
                <button onclick="nextMonth()">Next Month</button>
            </div>
            <h1 id="month-year">August 2024 Events</h1>
            <table id="calendar-table">
                <!-- Calendar will be generated here by JavaScript -->
            </table>
        </div>
        <div class="events-container">
            <h2>Events</h2>
            <ul class="events-list" id="events-list">
                <!-- Event items will be added here -->
            </ul>
        </div>
    </div>
    <script>
        const events = <?php echo json_encode($events); ?>;
        const reservations = <?php echo json_encode($reservations); ?>;
        const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
        const userId = <?php echo json_encode($userId); ?>;
    </script>
    <script src="../process/Events.js"></script>
</body>
</html>

