#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="../style.css" rel="stylesheet" type="text/css">
    <title>Movie Reviews</title>
</head>

<body>
    <h1 align="center">Movie Reviews</h1>
    <h4 align="center">
        <a href="../index.html">Back to assignment page</a>
    </h4>

    <div align="center">
        <input type="text" id="searchInput" placeholder="Search by movie name...">
    </div>

    <div id="movieTableContainer" align="center">
        <table id="movieTable">
            <thead>
                <tr>
                    <th>Movie</th>
                    <th>Rating</th>
                    <th>Recommend?</th>
                    <th>Genres</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <a href="adding.php">Add New Movie Review</a><br>
    <a href="ERD.html">Link to ERD</a>

    <script>
function updateTable(data) {
    $('#movieTable tbody').empty();

    data.forEach(function(movie) {
        var row = `
            <tr>
                <td>${movie.MovieName}</td>
                <td>${movie.Rating}</td>
                <td>${movie.Recommend}</td>
                <td>${movie.Genres}</td>
                <td>
                    <a href='edit.php?MovieID=${movie.MovieID}'>Edit</a>
                </td>
                <td>
                    <a href='delete.php?MovieID=${movie.MovieID}'>Delete</a>
                </td>
            </tr>
        `;
        $('#movieTable tbody').append(row);
    });
}

        function loadFullList() {
            $.ajax({
                url: 'search.php',
                method: 'GET',
                data: { query: '' },
                dataType: 'json',
                success: function(response) {
                    updateTable(response);
                },
                error: function() {
                    console.error('Failed to load the full list of movies');
                }
            });
        }

        $('#searchInput').on('input', function() {
            var query = $(this).val().toLowerCase();

            if (query.length > 0) {
                $.ajax({
                    url: 'search.php',
                    method: 'GET',
                    data: { query: query },
                    dataType: 'json',
                    success: function(response) {
                        updateTable(response);
                    },
                    error: function() {
                        console.error('Failed to fetch search results');
                    }
                });
            } else {
                loadFullList();
            }
        });
        $(document).ready(function() {
            loadFullList();
        });
    </script>
</body>
</html>
