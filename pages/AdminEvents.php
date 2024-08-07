#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT * FROM Events";
$result = $mysqli->query($sql);
$events = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Events</title>
    <link rel="stylesheet" href="../styles/AdminEvents.css">
</head>
<body>
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
        <a href="../process/logoutProcess.php">LogOut</a>
    </div>
    <div class="container">
        <h1>All Events</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Scheduled Time</th>
                    <th>End Time</th>
                    <th>Event Date</th>
                    <th>Is Approved</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['id']); ?></td>
                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                    <td><?php echo htmlspecialchars($event['description']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" width="100"></td>
                    <td><?php echo htmlspecialchars($event['scheduled_time']); ?></td>
                    <td><?php echo htmlspecialchars($event['end_time']); ?></td>
                    <td><?php echo htmlspecialchars($event['eventDate']); ?></td>
                    <td><?php echo htmlspecialchars($event['is_approved']); ?></td>
                    <td>
                        <?php if ($event['is_approved'] == 0) : ?>
                        <button onclick="approveEvent(<?php echo $event['id']; ?>)">Approve</button>
                        <?php else : ?>
                        Approved
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function approveEvent(eventId) {
            if (confirm('Are you sure you want to approve this event?')) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../process/ApproveEventProcess.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        location.reload();
                    }
                }
                xhr.send("id=" + eventId);
            }
        }
    </script>
</body>
</html>