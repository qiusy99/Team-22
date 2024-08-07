#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['message' => 'User not authenticated.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['bookId'];
    $userId = $_SESSION['user_id'];

    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Begin transaction
    $mysqli->begin_transaction();

    try {
        // Delete reservation
        $stmt = $mysqli->prepare("DELETE FROM BookReservations WHERE idLogin = ? AND BookId = ?");
        $stmt->bind_param('ii', $userId, $bookId);
        $stmt->execute();

        // Restore book copies
        $stmt = $mysqli->prepare("UPDATE Books SET BookCopies = BookCopies + 1 WHERE BookId = ?");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();

        $mysqli->commit();
        $response = ['message' => 'Unreservation successful.'];
    } catch (Exception $e) {
        $mysqli->rollback();
        $response = ['message' => 'Unreservation failed.'];
    }

    $stmt->close();
    $mysqli->close();

    echo json_encode($response);
}
?>
