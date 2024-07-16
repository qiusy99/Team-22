#!/usr/local/bin/php
<h1 align="center"> Add A Book </h1>
<form action="process.php" method="post">
            <h3>
                Enter the name of the book:
            </h3>
            <input type="string" name="movieName">

            <!-- Question 2 -->
            <h3>
                Would you recommend this book?
            </h3>
            <input type="radio" id="Yes" name="recommend" value="Yes">
            <label for="Yes"> Yes </label> <br>
            <input type="radio" id="No" name="recommend" value="No">
            <label for="No"> No </label> <br>

            <!-- Question 3 -->
            <h3>
                What is the book's genre(s)?
            </h3>
            <input type="checkbox" id="Scifi" name="genre[]" value="Scifi">
            <label for="Scifi">Scifi</label><br>
            <input type="checkbox" id="Drama" name="genre[]" value="Drama">
            <label for="Drama"> Drama </label><br>

            <input type="checkbox" id="Horror" name="genre[]" value="Horror">
            <label for="Horror"> Horror</label><br>
            <input type="checkbox" id="Action" name="genre[]" value="Action">
            <label for="Action"> Action</label><br>
            <input type="checkbox" id="Fantasy" name="genre[]" value="Fantasy">
            <label for="Fantasy"> Fantasy</label><br>
            <input type="checkbox" id="Romance" name="genre[]" value="Romance">
            <label for="Romance"> Romance</label><br>
            <input type="checkbox" id="Non-Fiction" name="genre[]" value="Non-Fiction" <?php if (strpos($genres, "Non-Fiction") !== false) echo "checked"; ?>>
            <label for="Non-Fiction"> Non-Fiction</label><br>

            <input type="checkbox" id="Fiction" name="genre[]" value="Fiction" <?php if (strpos($genres, "Fiction") !== false) echo "checked"; ?>>
            <label for="Fiction"> Fiction</label><br>

            <!-- Question 4 -->
            <h3>
                How would you rate this book?
            </h3>
                <select name="rating">
                <option value=1>1</option>
                <option value=2>2</option>
                <option value=3>3</option>
                <option value=4>4</option>
                <option value=5>5</option>
                <option value=6>6</option>
                <option value=7>7</option>
                <option value=8>8</option>
                <option value=9>9</option>
                <option value=10>10</option>
                </select>
            <br>
            <!-- Reset and Submit buttons-->
            <input type="reset">
            <input type="submit">

        </form>