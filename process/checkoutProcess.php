#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(['message' => 'User not authenticated.']);
    exit();
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

if ($userRole !== 'employee' && $userRole !== 'admin') {
    echo json_encode(['message' => 'Permission denied.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['bookId'];
    $checkoutUserId = $_POST['userId']; // ID of the user for whom the book is being checked out
    $currentDate = date('Y-m-d');
    $dueDate = date('Y-m-d', strtotime('+2 weeks'));

    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $mysqli->begin_transaction();

    try {
        // Insert checkout record
        $stmt = $mysqli->prepare("INSERT INTO Checkouts (idLogin, BookId, CheckoutDate, DueDate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiss', $checkoutUserId, $bookId, $currentDate, $dueDate);
        $stmt->execute();

        // Update book copies
        $stmt = $mysqli->prepare("UPDATE Books SET BookCopies = BookCopies - 1 WHERE BookId = ?");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();

        $mysqli->commit();
        $response = ['message' => 'Checkout successful.'];
    } catch (Exception $e) {
        $mysqli->rollback();
        $response = ['message' => 'Checkout failed.'];
    }

    $stmt->close();
    $mysqli->close();

    echo json_encode($response);
}
?>

