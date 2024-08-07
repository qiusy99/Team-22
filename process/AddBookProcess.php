#!/usr/local/bin/php
<?php
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$bookName = $_POST['bookName'];
$author = $_POST['author'];
$bookDescription = $_POST['bookDescription'];
$bookCopies = (int)$_POST['bookCopies']; // Ensure this is an integer
$location = $_POST['location']; // Should be a string
$resourceType = $_POST['resourceType'];
$genres = implode(", ", $_POST['genre']); // Combine selected genres into a string

// Prepare the SQL statement with the correct number of placeholders
$insertBookQuery = "INSERT INTO Books (BookName, Author, bookDescription, BookCopies, location, resourceType, Genres) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($insertBookQuery);
if ($stmt === false) {
    echo "Error preparing statement: " . $mysqli->error;
    exit();
}

// Bind parameters (s = string, i = integer)
// Order of parameters should match the SQL query
$stmt->bind_param("sssisss", $bookName, $author, $bookDescription, $bookCopies, $location, $resourceType, $genres);

// Execute the statement
if (!$stmt->execute()) {
    echo "Error executing statement: " . $stmt->error;
    exit();
}

$stmt->close();
$mysqli->close();

// Redirect to AdminBook.php
header("Location: ../pages/AdminBook.php");
exit();
?>
