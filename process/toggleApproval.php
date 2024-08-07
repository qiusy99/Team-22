#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $eventId = $_POST['id'];
    $status = $_POST['status'];

    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("UPDATE Events SET is_approved = ? WHERE id = ?");
    $stmt->bind_param('ii', $status, $eventId);

    if ($stmt->execute()) {
        echo 'Event approval status updated successfully.';
    } else {
        echo 'Error updating event approval status.';
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo 'Invalid request.';
}
?>
