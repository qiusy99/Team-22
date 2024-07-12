#!/usr/local/bin/php
<?php
// Connect to the database
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "MooreAssign6");

if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Failed to connect to MySQL: " . $mysqli->connect_error]);
    exit();
}

// Get the search query from the request
$query = $_GET['query'] ?? '';

// SQL query to find movies that match the search query
$sql = "SELECT Movie.MovieID, Movie.MovieName, Movie.Rating, Movie.Recommend, GROUP_CONCAT(Genre.GenreName SEPARATOR ', ') AS Genres
        FROM Movie
        INNER JOIN MovieGenre ON Movie.MovieID = MovieGenre.MovieID
        INNER JOIN Genre ON MovieGenre.GenreID = Genre.GenreID
        WHERE Movie.MovieName LIKE ?
        GROUP BY Movie.MovieID";

// Prepare the statement
$stmt = $mysqli->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param('s', $searchTerm);
$stmt->execute();

// Fetch the results
$result = $stmt->get_result();
$movies = [];

while ($row = $result->fetch_assoc()) {
    // Store each movie in an array
    $movies[] = $row;
}

// Return the movies as JSON
echo json_encode($movies);

// Close the database connection
$stmt->close();
$mysqli->close();
?>