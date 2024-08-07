#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['bookId'];
    $reserveDate = $_POST['reserveDate'];
    $reserveTime = $_POST['reserveTime'];
    $userId = $_SESSION['user_id'];

    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO BookReservations (idLogin, BookId, ReservationDate, ReservationTime) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('iiss', $userId, $bookId, $reserveDate, $reserveTime);

    if ($stmt->execute()) {
        $stmt = $mysqli->prepare("UPDATE Books SET BookCopies = BookCopies - 1 WHERE BookId = ?");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $response = ['message' => 'Reservation successful.'];
    } else {
        $response = ['message' => 'Reservation failed.'];
    }

    $stmt->close();
    $mysqli->close();

    echo json_encode($response);
}
?>
