#!/usr/local/bin/php

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Failed to connect to MySQL: " . $mysqli->connect_error]);
    exit();
}

// Get the search query from the request
$query = $_GET['query'] ?? '';

// Prepare the SQL statement
$sql = "SELECT BookId, BookName, Author, bookDescription, BookCopies, location, resourceType, Genres
        FROM Books
        WHERE BookName LIKE ?";

// Prepare the statement
$stmt = $mysqli->prepare($sql);

// Bind the parameter
$searchTerm = '%' . $query . '%';
$stmt->bind_param('s', $searchTerm);

// Execute the statement
if (!$stmt->execute()) {
    echo json_encode(["error" => "Failed to execute the statement: " . $stmt->error]);
    exit();
}

// Get the results
$result = $stmt->get_result();
$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

// Output the results in JSON format
echo json_encode($books);

// Close the statement and the database connection
$stmt->close();
$mysqli->close();
// modified by Jingyi Fu, written by Cameron Moore
// Aug 2nd, names are correct
?>