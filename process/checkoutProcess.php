#!/usr/local/bin/php
<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in

if (!isset($_SESSION['userId'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit();
}

$userId = $_SESSION['userId'];

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookIds = $_POST['bookIds'];

    // Begin a transaction
    $mysqli->begin_transaction();

    try {
        foreach ($bookIds as $bookId) {
            // Reduce the number of available copies of the book
            $updateBookSql = "UPDATE Books SET BookCopies = BookCopies - 1 WHERE BookId = ? AND BookCopies > 0";
            $updateBookStmt = $mysqli->prepare($updateBookSql);
            if ($updateBookStmt === false) {
                throw new Exception("Error preparing the statement: " . $mysqli->error);
            }

            $updateBookStmt->bind_param("i", $bookId);
            if (!$updateBookStmt->execute()) {
                throw new Exception("Error executing the statement: " . $updateBookStmt->error);
            }

            // Insert a new checkout record
            $insertCheckoutSql = "INSERT INTO Checkouts (BookId, idLogin) VALUES (?, ?)";
            $insertCheckoutStmt = $mysqli->prepare($insertCheckoutSql);
            if ($insertCheckoutStmt === false) {
                throw new Exception("Error preparing the statement: " . $mysqli->error);
            }

            $insertCheckoutStmt->bind_param("ii", $bookId, $userId);
            if (!$insertCheckoutStmt->execute()) {
                throw new Exception("Error executing the statement: " . $insertCheckoutStmt->error);
            }
        }

        // Commit the transaction
        $mysqli->commit();
        echo "Books checked out successfully.";

    } catch (Exception $e) {
        // Rollback the transaction on error
        $mysqli->rollback();
        echo "Failed to checkout the books: " . $e->getMessage();
    }

    $updateBookStmt->close();
    $insertCheckoutStmt->close();
}

$mysqli->close();
?>
