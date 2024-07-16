#!/usr/local/bin/php
<?php
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "MooreAssign6");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

if (isset($_GET['MovieID'])) {
    $movieID = $_GET['MovieID'];

    $query = "SELECT Movie.MovieID, Movie.MovieName, Movie.Rating, Movie.Recommend, GROUP_CONCAT(Genre.GenreName SEPARATOR ', ') AS Genres
    FROM Movie
    INNER JOIN MovieGenre ON Movie.MovieID = MovieGenre.MovieID
    INNER JOIN Genre ON MovieGenre.GenreID = Genre.GenreID
    WHERE Movie.MovieID = $movieID
    GROUP BY Movie.MovieID";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $movieName = $row["MovieName"];
        $rating = $row["Rating"];
        $recommend = $row["Recommend"];
        $genres = $row["Genres"];

        $result->close();
    } 
} else {
    echo "MovieID parameter is missing.";
}
?>

<h1 align="center"> Edit Book</h1>
<form action="update.php" method="post">
    <input type="hidden" name="MovieID" value="<?php echo $movieID; // Keeps track of MovieID?>">
    <h3>Enter the name of the movie:</h3>
    <input type="text" name="movieName" value="<?php echo $movieName; ?>">
    <h3>Would you recommend this book?</h3>
    <!-- Every if statement below just makes it easier for the user to see what the current info is and change it accordingly -->
    <input type="radio" id="Yes" name="recommend" value="Yes" <?php if ($recommend === "Yes") echo "checked"; ?>>
    <label for="Yes"> Yes </label>
    <input type="radio" id="No" name="recommend" value="No" <?php if ($recommend === "No") echo "checked"; ?>>
    <label for="No"> No </label>

    <h3>What is the book's genre(s)?</h3>
    <input type="checkbox" id="Scifi" name="genre[]" value="Scifi" <?php if (strpos($genres, "Scifi") !== false) echo "checked"; ?>>
    <label for="Scifi"> Scifi</label><br>

    <input type="checkbox" id="Drama" name="genre[]" value="Drama" <?php if (strpos($genres, "Drama") !== false) echo "checked"; ?>>
    <label for="Drama"> Drama</label><br>


    <input type="checkbox" id="Horror" name="genre[]" value="Horror" <?php if (strpos($genres, "Horror") !== false) echo "checked"; ?>>
    <label for="Horror"> Horror</label><br>

    <input type="checkbox" id="Action" name="genre[]" value="Action" <?php if (strpos($genres, "Action") !== false) echo "checked"; ?>>
    <label for="Action"> Action</label><br>

    <input type="checkbox" id="Fantasy" name="genre[]" value="Fantasy" <?php if (strpos($genres, "Fantasy") !== false) echo "checked"; ?>>
    <label for="Fantasy"> Fantasy</label><br>

    <input type="checkbox" id="Romance" name="genre[]" value="Romance" <?php if (strpos($genres, "Romance") !== false) echo "checked"; ?>>
    <label for="Romance"> Romance</label><br>

    <input type="checkbox" id="Non-Fiction" name="genre[]" value="Non-Fiction" <?php if (strpos($genres, "Non-Fiction") !== false) echo "checked"; ?>>
    <label for="Non-Fiction"> Non-Fiction</label><br>

    <input type="checkbox" id="Fiction" name="genre[]" value="Fiction" <?php if (strpos($genres, "Fiction") !== false) echo "checked"; ?>>
    <label for="Fiction"> Fiction</label><br>

    <h3>How would you rate this book?</h3>
    <select name="rating">
    <option value="1" <?php if ($rating == 1) echo "selected"; ?>>1</option>
    <option value="2" <?php if ($rating == 2) echo "selected"; ?>>2</option>
    <option value="3" <?php if ($rating == 3) echo "selected"; ?>>3</option>
    <option value="4" <?php if ($rating == 4) echo "selected"; ?>>4</option>
    <option value="5" <?php if ($rating == 5) echo "selected"; ?>>5</option>
    <option value="6" <?php if ($rating == 6) echo "selected"; ?>>6</option>
    <option value="7" <?php if ($rating == 7) echo "selected"; ?>>7</option>
    <option value="8" <?php if ($rating == 8) echo "selected"; ?>>8</option>
    <option value="9" <?php if ($rating == 9) echo "selected"; ?>>9</option>
    <option value="10" <?php if ($rating == 10) echo "selected"; ?>>10</option>
    </select>
    <br>

    <!-- Submit button -->
    <input type="submit" value="Update Book Info">
</form>