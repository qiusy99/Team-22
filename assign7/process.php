#!/usr/local/bin/php
<?php
$mysqli = new mysqli("mysql.cise.ufl.edu","moore.cameron","Sadie2012","MooreAssign6");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$insertMovieQuery = "INSERT INTO Movie (MovieName, Rating, Recommend) VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($insertMovieQuery);
$stmt->bind_param("sis", $movieName, $rating, $recommend);  // Binds parameters to prevent SQL Injection
$movieName = $_POST['movieName'];
$recommend = $_POST['recommend']; // Yes or No
$genres = $_POST['genre']; // Array of selected genres
$rating = $_POST['rating'];
$stmt->execute();


$lastInsertedMovieID = $mysqli->insert_id;

foreach ($genres as $genre) {
    $insertGenreQuery = "INSERT IGNORE INTO Genre (GenreName) VALUES ('$genre')";
    $mysqli->query($insertGenreQuery);
}

foreach ($genres as $genre) {
    $getGenreIDQuery = "SELECT GenreID FROM Genre WHERE GenreName = '$genre'";
    $result = $mysqli->query($getGenreIDQuery);
    $row = $result->fetch_assoc();
    $genreID = $row['GenreID'];

    $insertMovieGenreQuery = "INSERT INTO MovieGenre (MovieID, GenreID) VALUES ($lastInsertedMovieID, $genreID)";
    $mysqli->query($insertMovieGenreQuery);
}
$mysqli->close();

header("Location: index.php");
exit();
?>