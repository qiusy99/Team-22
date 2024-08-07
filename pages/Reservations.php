#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Get the user ID from the session
$userId = $_SESSION['user_id'];

// Fetch user's reservations
$sql = "
    SELECT e.*
    FROM Events e
    JOIN Reservations r ON e.id = r.idEvent
    WHERE r.idLogin = ?
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link rel="stylesheet" href="../styles/Reservations.css">
    <style>
        body {
            background: linear-gradient(to bottom, #6092A6, #CEECF2);
            font-family: Arial, sans-serif;
            font-size: 16px;
            height: 791px;
        }
        
        .top-right-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        .top-right-buttons a {
            background-color: whitesmoke;
            color: black;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            font-size: 18px;
        }
        .top-right-buttons a:hover {
            background-color: #CEECF2;
        }
    </style>
</head>
<body>
    <div class="top-right-buttons">
        <a href="Checkout.php">CheckOut</a>
        <a href="../process/logoutProcess.php">LogOut</a>
        <a href="Library_Admin_Home.php">Home</a>
    </div>

    <div class="container">
        <h1>My Reservations</h1>
        <div class="reservations-container">
            <ul class="reservations-list" id="reservations-list">
                <!-- Reserved events will be listed here -->
                <?php if (empty($events)): ?>
                    <li>No reservations found.</li>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <li id="event-<?php echo $event['id']; ?>" class="reservation-item">
                            <h2><?php echo htmlspecialchars($event['name']); ?></h2>
                            <p><?php echo htmlspecialchars($event['description']); ?></p>
                            <p>Scheduled Time: <?php echo date("F j, Y, g:i a", strtotime($event['scheduled_time'])); ?></p>
                            <p>End Time: <?php echo date("F j, Y, g:i a", strtotime($event['end_time'])); ?></p>
                            <?php if (!empty($event['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" width="200">
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</body>
</html>
