#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image_path = $_POST['image_path'];
    $scheduled_time = $_POST['scheduled_time'];
    $end_time = $_POST['end_time'];
    $eventDate = $_POST['eventDate'];

    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("UPDATE Events SET name = ?, description = ?, image_path = ?, scheduled_time = ?, end_time = ?, eventDate = ? WHERE id = ?");
    $stmt->bind_param('ssssssi', $name, $description, $image_path, $scheduled_time, $end_time, $eventDate, $eventId);

    if ($stmt->execute()) {
        $response = ['message' => 'Event updated successfully.'];
    } else {
        $response = ['message' => 'Failed to update event.'];
    }

    $stmt->close();
    $mysqli->close();

    echo json_encode($response);
}
?>
