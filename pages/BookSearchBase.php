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
    <link href="../../cis4930/style.css" rel="stylesheet" type="text/css">
    <title>Team 22 Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
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
        h1 {
            text-align: center;
            color: #fff;
            margin-top: 20px;
        }
        #searchInput {
            width: 50%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        #bookTableContainer {
            width: 80%;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
        }
        #bookTable {
            width: 100%;
            border-collapse: collapse;
        }
        #bookTable th, #bookTable td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        #bookTable th {
            background-color: #f8f8f8;
        }
    </style>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="../process/logoutProcess.php">Log Out</a>
    </div>

    <h1>Library Dashboard</h1>

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
                data: { query: '' },
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
