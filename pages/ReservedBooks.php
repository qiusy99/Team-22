#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

$userId = $_SESSION['user_id'];
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
    <title>Reserved Books</title>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
    </div>

    <h1 align="center">Reserved Books</h1>

    <div id="reservedBooksTableContainer" align="center">
        <table id="reservedBooksTable">
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Reservation Date</th>
                    <th>Reservation Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script>
        function updateReservedBooksTable(data) {
            $('#reservedBooksTable tbody').empty();
            data.forEach(function(reservation) {
                var row = `
                    <tr>
                        <td>${reservation.BookName}</td>
                        <td>${reservation.Author}</td>
                        <td>${reservation.bookDescription}</td>
                        <td>${reservation.ReservationDate}</td>
                        <td>${reservation.ReservationTime}</td>
                        <td><button class="unreserveButton" data-id="${reservation.BookId}">Unreserve</button></td>
                    </tr>
                `;
                $('#reservedBooksTable tbody').append(row);
            });
        }

        function loadReservedBooks() {
            $.ajax({
                url: '../process/ReservedBooksProcess.php',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    updateReservedBooksTable(response);
                }
            });
        }

        $(document).ready(function() {
            loadReservedBooks();
        });

        // Handle unreserve button click
        $(document).on('click', '.unreserveButton', function() {
            var bookId = $(this).data('id');
            $.ajax({
                url: '../process/unreserveBook.php',
                method: 'POST',
                data: { bookId: bookId },
                success: function(response) {
                    alert(response.message);
                    loadReservedBooks(); // Reload reserved books list to reflect changes
                }
            });
        });
    </script>
</body>
</html>
