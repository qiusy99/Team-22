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

// Handle the event update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editEvent'])) {
    $eventId = $_POST['eventId'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $scheduled_time = $_POST['scheduled_time'];
    $end_time = $_POST['end_time'];
    $eventDate = $_POST['eventDate'];

    $stmt = $mysqli->prepare("UPDATE Events SET name = ?, description = ?, scheduled_time = ?, end_time = ?, eventDate = ? WHERE id = ?");
    $stmt->bind_param('sssssi', $name, $description, $scheduled_time, $end_time, $eventDate, $eventId);

    if ($stmt->execute()) {
    } else {
        echo '<p>Error updating event.</p>';
    }

    $stmt->close();
}

// Handle the event delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteEvent'])) {
    $eventId = $_POST['eventId'];

    // Delete related reservations
    $stmt = $mysqli->prepare("DELETE FROM BookReservations WHERE idReservation = ?");
    $stmt->bind_param('i', $eventId);
    $stmt->execute();
    $stmt->close();

    // Delete the event
    $stmt = $mysqli->prepare("DELETE FROM Events WHERE id = ?");
    $stmt->bind_param('i', $eventId);
    if ($stmt->execute()) {
        echo '<p>Event deleted successfully.</p>';
    } else {
        echo '<p>Error deleting event.</p>';
    }

    $stmt->close();
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
    <link href="../styles/BookSearch.css" rel="stylesheet" type="text/css">
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
                    <td><?php echo htmlspecialchars($event['name']); ?></td>
                    <td><?php echo htmlspecialchars($event['description']); ?></td>
                    <td>
                        <img src="../uploads/<?php echo htmlspecialchars($event['image_path']); ?>" alt="Event Image" width="100">
                        <form action="../process/uploadImage.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="eventId" value="<?php echo htmlspecialchars($event['id']); ?>">
                            <input type="file" name="eventImage">
                            <button type="submit">Upload New Image</button>
                        </form>
                    </td>
                    <td><?php echo htmlspecialchars($event['scheduled_time']); ?></td>
                    <td><?php echo htmlspecialchars($event['end_time']); ?></td>
                    <td><?php echo htmlspecialchars($event['eventDate']); ?></td>
                    <td>
                        <button onclick="toggleApproval(<?php echo $event['id']; ?>, <?php echo $event['is_approved']; ?>)">
                            <?php echo $event['is_approved'] ? 'Disapprove' : 'Approve'; ?>
                        </button>
                    </td>
                    <td>
                        <button onclick="editEvent(<?php echo $event['id']; ?>)">Edit</button>
                        <button onclick="deleteEvent(<?php echo $event['id']; ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal for Editing Event -->
        <div id="editEventModal" style="display:none;">
            <h2>Edit Event</h2>
            <form id="editEventForm" method="post">
                <input type="hidden" name="editEvent" value="1">
                <input type="hidden" id="editEventId" name="eventId">
                <label for="editName">Name:</label>
                <input type="text" id="editName" name="name" required>
                <label for="editDescription">Description:</label>
                <textarea id="editDescription" name="description" required></textarea>
                <label for="editScheduledTime">Scheduled Time:</label>
                <input type="datetime-local" id="editScheduledTime" name="scheduled_time" required>
                <label for="editEndTime">End Time:</label>
                <input type="datetime-local" id="editEndTime" name="end_time" required>
                <label for="editEventDate">Event Date:</label>
                <input type="text" id="editEventDate" name="eventDate" required>
                <button type="submit">Save Changes</button>
                <button type="button" onclick="closeEditModal()">Cancel</button>
            </form>
        </div>
    </div>
    <script>
        function toggleApproval(eventId, currentStatus) {
            const newStatus = currentStatus ? 0 : 1;
            if (confirm(`Are you sure you want to ${newStatus ? 'approve' : 'disapprove'} this event?`)) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../process/toggleApproval.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        location.reload();
                    }
                }
                xhr.send("id=" + eventId + "&status=" + newStatus);
            }
        }

        function editEvent(eventId) {
            // Fetch event data and populate the form
            const xhr = new XMLHttpRequest();
            xhr.open("GET", `../process/getEvent.php?id=${eventId}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const event = JSON.parse(xhr.responseText);

                    document.getElementById('editEventId').value = event.id;
                    document.getElementById('editName').value = event.name;
                    document.getElementById('editDescription').value = event.description;
                    document.getElementById('editScheduledTime').value = event.scheduled_time.split('T').join('T');
                    document.getElementById('editEndTime').value = event.end_time.split('T').join('T');
                    document.getElementById('editEventDate').value = event.eventDate;

                    document.getElementById('editEventModal').style.display = 'block';
                }
            }
            xhr.send();
        }

        function closeEditModal() {
            document.getElementById('editEventModal').style.display = 'none';
        }

        function deleteEvent(eventId) {
    if (confirm('Are you sure you want to delete this event?')) {
        const form = new FormData();
        form.append('deleteEvent', '1');
        form.append('eventId', eventId);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '../process/deleteEventProcess.php', true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Delete response:', xhr.responseText);
                location.reload();
            } else {
                console.log('Delete error:', xhr.status, xhr.statusText);
            }
        };
        xhr.send(form);
    }
}

    </script>
</body>
</html>


