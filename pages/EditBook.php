#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        .top-right-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        .top-right-buttons a {
            background-color: whitesmoke;
            color: black;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            font-size: 18px;
        }
        .top-right-buttons a:hover {
            background-color: #c8f3e5;
        }
    </style>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="../process/logoutProcess.php">LogOut</a>
    </div>

    <h1 align="center"> Edit Book </h1>

    <?php
    $bookId = isset($_GET['BookId']) ? intval($_GET['BookId']) : 0;

    // Database connection
    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    // Fetch book details
    $stmt = $mysqli->prepare("SELECT BookName, Author, bookDescription, BookCopies, location, resourceType, Genres FROM Books WHERE BookId = ?");
    $stmt->bind_param('i', $bookId);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if (!$book) {
        echo "<p>Book not found.</p>";
        exit();
    }

    $stmt->close();
    $mysqli->close();
    ?>

    <form action="../process/EditBookProcess.php" method="post">
        <input type="hidden" name="bookId" value="<?php echo htmlspecialchars($bookId); ?>">

        <h3>Enter the name of the book:</h3>
        <input type="text" name="bookName" value="<?php echo htmlspecialchars($book['BookName']); ?>" required>

        <h3>Enter the author of the book:</h3>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['Author']); ?>" required>

        <h3>Enter the book description:</h3>
        <textarea name="bookDescription" required><?php echo htmlspecialchars($book['bookDescription']); ?></textarea>

        <h3>Enter the number of copies:</h3>
        <input type="number" name="bookCopies" value="<?php echo htmlspecialchars($book['BookCopies']); ?>" required>

        <h3>Enter the book location:</h3>
        <input type="text" name="location" value="<?php echo htmlspecialchars($book['location']); ?>" required>

        <h3>Enter the resource type:</h3>
        <input type="text" name="resourceType" value="<?php echo htmlspecialchars($book['resourceType']); ?>" required>

        <h3>What is the book's genre(s)?</h3>
        <?php
        $genres = explode(", ", $book['Genres']);
        $availableGenres = ['Scifi', 'Adventure', 'Horror', 'Historical', 'Fantasy', 'Romance', 'Non-Fiction', 'Fiction'];
        foreach ($availableGenres as $genre) {
            $checked = in_array($genre, $genres) ? 'checked' : '';
            echo "<input type='checkbox' id='$genre' name='genre[]' value='$genre' $checked>";
            echo "<label for='$genre'> $genre </label><br>";
        }
        ?>

        <!-- Reset and Submit buttons -->
        <input type="reset">
        <input type="submit" value="Update Book">
    </form>
</body>
</html>

<!-- Jingyi Fu modified Aug 3rd -->
 <!--  names changed -->