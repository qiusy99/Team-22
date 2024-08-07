#!/usr/local/bin/php
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
    <style>
        /* Add styling for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
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
                    <th>Reserve</th> <!-- New column for reservation -->
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Reservation Modal -->
    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Reserve Book</h2>
            <form id="reserveForm">
                <input type="hidden" id="reserveBookId" name="bookId">
                <label for="reserveDate">Pick-Up Date:</label>
                <input type="date" id="reserveDate" name="reserveDate" required>
                <label for="reserveTime">Pick-Up Time:</label>
                <input type="time" id="reserveTime" name="reserveTime" required>
                <button type="submit">Reserve</button>
            </form>
        </div>
    </div>

    <script>
        function updateTable(data) {
            $('#bookTable tbody').empty();
            data.forEach(function(book) {
                var reserveButton = book.BookCopies > 0 
                    ? `<button class="reserveButton" data-id="${book.BookId}">Reserve</button>` 
                    : `<button class="reserveButton" disabled>Out of Stock</button>`;

                var row = `
                    <tr>
                        <td>${book.BookName}</td>
                        <td>${book.Author}</td>
                        <td>${book.bookDescription}</td>
                        <td>${book.BookCopies}</td>
                        <td>${book.location}</td>
                        <td>${book.resourceType}</td>
                        <td>${book.Genres}</td>
                        <td>${reserveButton}</td>
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
                error: function(xhr, status, error) {
                    console.error('Error fetching book list:', error);
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
                    error: function(xhr, status, error) {
                        console.error('Error searching books:', error);
                    }
                });
            } else {
                loadFullList();
            }
        });

        $(document).ready(function() {
            loadFullList();
        });

        // Handle reservation button click
        $(document).on('click', '.reserveButton', function() {
            var bookId = $(this).data('id');
            $('#reserveBookId').val(bookId);
            $('#reservationModal').show();
        });

        // Handle reservation form submission
        $('#reserveForm').on('submit', function(event) {
            event.preventDefault();
            console.log('Submitting form'); // Debugging line
            $.ajax({
                url: '../process/reserveBookProcess.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    console.log('Server response:', response); // Debugging line
                    alert(response.message);
                    $('#reservationModal').hide();
                    loadFullList(); // Reload book list to update availability
                },
                error: function(xhr, status, error) {
                    console.error('Error reserving book:', error);
                }
            });
        });

        // Handle modal close
        $('.close').on('click', function() {
            $('#reservationModal').hide();
        });

        $(window).on('click', function(event) {
            if ($(event.target).is('#reservationModal')) {
                $('#reservationModal').hide();
            }
        });
    </script>
</body>
</html>
