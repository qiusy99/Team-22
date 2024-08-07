#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$userRole = $_SESSION['role'];

// Ensure only admin and employee can access this page
if ($userRole !== 'admin' && $userRole !== 'employee') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $checkoutId = $_POST['checkoutId'];

    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Begin transaction
    $mysqli->begin_transaction();

    try {
        // Get the bookId from the Checkouts table
        $stmt = $mysqli->prepare("SELECT BookId FROM Checkouts WHERE idCheckout = ?");
        $stmt->bind_param('i', $checkoutId);
        $stmt->execute();
        $stmt->bind_result($bookId);
        $stmt->fetch();
        $stmt->close();

        // Remove the checkout record
        $stmt = $mysqli->prepare("DELETE FROM Checkouts WHERE idCheckout = ?");
        $stmt->bind_param('i', $checkoutId);
        $stmt->execute();
        $stmt->close();

        // Increment the BookCopies in Books table
        $stmt = $mysqli->prepare("UPDATE Books SET BookCopies = BookCopies + 1 WHERE BookId = ?");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $mysqli->commit();
        $response = ['message' => 'Book returned successfully.'];
    } catch (Exception $e) {
        // Rollback transaction on error
        $mysqli->rollback();
        $response = ['message' => 'An error occurred. Please try again.'];
    }

    $mysqli->close();

    echo json_encode($response);
}
?>
