#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $eventId = $_POST['eventId'];

    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $stmt = $mysqli->prepare("INSERT INTO Reservations (idLogin, idEvent) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $eventId);

    if ($stmt->execute()) {
        echo "Reservation successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
} else {
    echo "Invalid request method.";
}
?>
