#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check user role
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}

// Database connection
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle the delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteEvent'])) {
    $eventId = $_POST['eventId'];

    // Start transaction
    $mysqli->begin_transaction();

    try {
        // Delete related reservations
        $stmt = $mysqli->prepare("DELETE FROM Reservations WHERE idEvent = ?");
        $stmt->bind_param('i', $eventId);
        $stmt->execute();
        $stmt->close();

        // Delete the event
        $stmt = $mysqli->prepare("DELETE FROM Events WHERE id = ?");
        $stmt->bind_param('i', $eventId);
        if ($stmt->execute()) {
            $mysqli->commit();
            echo 'Event deleted successfully.';
        } else {
            throw new Exception('Error deleting event.');
        }
        $stmt->close();
    } catch (Exception $e) {
        $mysqli->rollback();
        echo 'Failed to delete event: ' . $e->getMessage();
    }

    $mysqli->close();
} else {
    echo 'Invalid request.';
}
?>
