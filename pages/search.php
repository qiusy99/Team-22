#!/usr/local/bin/php
<?php
// Connect to the database
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Failed to connect to MySQL: " . $mysqli->connect_error]);
    exit();
}

// Get the search query from the request
$query = $_GET['query'] ?? '';

$sql = "SELECT * FROM Books WHERE BookName LIKE ?";

// Prepare the statement
$stmt = $mysqli->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param('s', $searchTerm);
$stmt->execute();

$result = $stmt->get_result();
$books = [];

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

echo json_encode($books);

// Close the database connection
$stmt->close();
$mysqli->close();
?>