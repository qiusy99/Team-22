#!/usr/local/bin/php

<?php

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "MooreAssign6");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

if (isset($_POST['MovieID'])) {
    $movieID = $_POST['MovieID'];
    $movieName = $_POST['movieName'];
    $recommend = $_POST['recommend']; // Yes or No
    $genres = $_POST['genre']; // Array of selected genres
    $rating = $_POST['rating'];
    echo $movieID;


    $query = "UPDATE Movie SET MovieName = '$movieName', Rating = $rating, Recommend = '$recommend' WHERE MovieID = $movieID";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sis", $movieName, $rating, $recommend);  // Same as process, prevents SQL Injection
    $stmt->execute();
    $deleteMovieGenreQuery = "DELETE FROM MovieGenre WHERE MovieID = $movieID";
    $mysqli->query($deleteMovieGenreQuery);

    $genreIDs = [];
foreach ($genres as $genre) {
    $insertGenreQuery = "INSERT IGNORE INTO Genre (GenreName) VALUES ('$genre')";
    $mysqli->query($insertGenreQuery);
    
    $getGenreIDQuery = "SELECT GenreID FROM Genre WHERE GenreName = '$genre'";
    $result = $mysqli->query($getGenreIDQuery);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $genreIDs[] = $row['GenreID'];
    }
}

foreach ($genreIDs as $genreID) {
    $insertMovieGenreQuery = "INSERT INTO MovieGenre (MovieID, GenreID) VALUES ($movieID, $genreID)";
    $mysqli->query($insertMovieGenreQuery);
}
    if ($mysqli->query($query)) {
        echo "Movie updated successfully.";
   } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "Invalid request.";
}
    $mysqli->close();
header("Location: index.php");
exit();
?>
