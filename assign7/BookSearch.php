#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="../../cis4930/style.css" rel="stylesheet" type="text/css">
    <title>Team 22 Library</title>
</head>

<body>
    <h1 align="center">Library</h1>
    <h4 align="center">
        Are you an admin?
        <a href="index.php">Click here</a>
        to edit the books!
    </h4>
    <h4 align="center">
        Not logged in?
    <a href = "../Library_Login.html"> Log in </a>
    </h4>

    <div align="center">
        <input type="text" id="searchInput" placeholder="Search by Book name...">
    </div>

    <div id="movieTableContainer" align="center">
        <table id="movieTable">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Rating</th>
                    <th>Recommend?</th>
                    <th>Genre</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
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