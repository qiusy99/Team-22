#!/usr/local/bin/php

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="../../cis4930/style.css" rel="stylesheet" type="text/css">
    <link href="../styles/BookSearch.css" rel="stylesheet" type="text/css">
    <title>Team 22 Library</title>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Home_Page.html">Home</a>
    </div>

    <h1 align="center">Library Dashboard</h1>

    <div align="center">
        <input type="text" id="searchInput" placeholder="Search by Book name...">
    </div>

    <div id="bookTableContainer" align="center">
        <table id="bookTable">
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Available Copies</th>
                    <th>Location</th>
                    <th>Resource Type</th>
                    <th>Genres</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script>
        function updateTable(data) {
    console.log('Data received:', data);  // Log the data received for inspection
    $('#bookTable tbody').empty();
    data.forEach(function(book) {
        var row = `
            <tr>
                <td>${book.BookName}</td>
                <td>${book.Author}</td>
                <td>${book.bookDescription}</td>
                <td>${book.BookCopies}</td>
                <td>${book.location}</td>
                <td>${book.resourceType}</td>
                <td>${book.Genres}</td>
            </tr>
        `;
        $('#bookTable tbody').append(row);
    });
}


        function loadFullList() {
    $.ajax({
        url: '../process/BookSearchProcess.php',
        method: 'GET',
        data: { query: ''},
        dataType: 'json',
        success: function(response) {
            updateTable(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
    console.error('Failed to load the full list of books:', textStatus, errorThrown);
    console.log('Response Text:', jqXHR.responseText);  // Inspect the raw response
}
    });
}

        $('#searchInput').on('input', function() {
            var query = $(this).val().toLowerCase();
            if (query.length > 0) {
                $.ajax({
                    url: '../process/BookSearchProcess.php',
                    method: 'GET',
                    data: { query: query },
                    dataType: 'json',
                    success: function(response) {
                        updateTable(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
    console.error('Failed to load the full list of books:', textStatus, errorThrown);
    console.log('Response Text:', jqXHR.responseText);  // Inspect the raw response
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

