#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$userId = $_SESSION['user_id'];

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$stmt = $mysqli->prepare("
    SELECT b.BookId, b.BookName, b.Author, b.bookDescription, r.ReservationDate, r.ReservationTime
    FROM BookReservations r
    JOIN Books b ON r.BookId = b.BookId
    WHERE r.idLogin = ?
");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$reservations = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$mysqli->close();

header('Content-Type: application/json');
echo json_encode($reservations);
?>
