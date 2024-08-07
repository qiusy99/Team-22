#!/usr/local/bin/php

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in and has the 'admin' role
if (!isset($_SESSION['role']) || $_SESSION['role'] == 'member') {
    header("Location: ../pages/Library_Login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="../../cis4930/style.css" rel="stylesheet" type="text/css">
    <title>Team 22 Library</title>
    <style>
        body {
            background: linear-gradient(to bottom, #6092A6, #CEECF2);
            font-family: Arial, sans-serif;
            font-size: 16px;
            height: 612px;
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
            background-color: #CEECF2;
        }
    </style>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Employee_Home.php">Home</a>
        <a href="../process/logoutProcess.php">LogOut</a>
    </div>

    <h1 align="center">Admin/Employee Library Dashboard</h1>
    <h4 align="center">
        <a href="BookSearch.php">Go back to library dashboard</a>
    </h4>

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
                    <th>Copies</th>
                    <th>Location</th>
                    <th>Resource Type</th>
                    <th>Genres</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <a href="AddBook.php">Add Books</a><br>

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
                <td>
                    <a href='EditBook.php?BookId=${book.BookId}'>Edit</a>
                    <a href='../process/DeleteBookProcess.php?BookId=${book.BookId}'>Delete</a>
                </td>
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
