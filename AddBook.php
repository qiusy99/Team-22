#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
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

    <h1 align="center"> Add A Book </h1>
    <form action="../process/AddBookProcess.php" method="post">
        <!-- Q1 -->
        <h3>Enter the name of the book:</h3>
        <input type="text" name="bookName" required>

        <!-- Q2 -->
        <h3>Enter the author of the book:</h3>
        <input type="text" name="author" required>

        <!-- Q3 -->
        <h3>Enter the book description:</h3>
        <textarea name="bookDescription" required></textarea>

        <!-- Q4 -->
        <h3>Enter the number of copies:</h3>
        <input type="number" name="bookCopies" required>

        <!-- Q5 -->
        <h3>Enter the book location:</h3>
        <input type="text" name="location" required>

        <!-- Q6 -->
        <h3>Enter the resource type:</h3>
        <input type="text" name="resourceType" required>

        <!-- Q7 -->
        <h3>What is the book's genre(s)?</h3>
        <input type="checkbox" id="Scifi" name="genre[]" value="Scifi">
        <label for="Scifi">Scifi</label><br>
        <input type="checkbox" id="Adventure" name="genre[]" value="Adventure">
        <label for="Adventure"> Adventure </label><br>
        <input type="checkbox" id="Horror" name="genre[]" value="Horror">
        <label for="Horror"> Horror</label><br>
        <input type="checkbox" id="Historical" name="genre[]" value="Historical">
        <label for="Historical">Historical</label><br>
        <input type="checkbox" id="Fantasy" name="genre[]" value="Fantasy">
        <label for="Fantasy"> Fantasy</label><br>
        <input type="checkbox" id="Romance" name="genre[]" value="Romance">
        <label for="Romance"> Romance</label><br>
        <input type="checkbox" id="Non-Fiction" name="genre[]" value="Non-Fiction">
        <label for="Non-Fiction"> Non-Fiction</label><br>
        <input type="checkbox" id="Fiction" name="genre[]" value="Fiction">
        <label for="Fiction"> Fiction</label><br>

        <!-- Reset and Submit buttons-->
        <input type="reset">
        <input type="submit">
    </form>
</body>
</html>


        <!-- modified by Jingyi Fu, written by Cameron Moore,  Aug2 -->
         <!-- names and informations modified -->