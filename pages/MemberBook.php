#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="../../cis4930/style.css" rel="stylesheet" type="text/css">
    <link href="../styles/MemberBook.css" rel="stylesheet" type="text/css">
    <title>Team 22 Library</title>
</head>

<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="../pages/Library_Member_Home.php">Home</a>
        <a href="../process/logoutProcess.php">Log Out</a>
        <a href="../pages/checkedoutbooks.php">Checked Out Books</a>
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
                    <th>Copies</th>
                    <th>Location</th>
                    <th>Resource Type</th>
                    <th>Genres</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    
    <div align="center">
        <button id="addToWaitingList">Add Book to Waiting List</button>
        <button id="checkoutBooks">Checkout</button>
    </div>

    <script>
        let selectedBooks = [];

        function updateTable(data) {
            console.log('Data received:', data);  // Log the data received for inspection
            $('#bookTable tbody').empty();
            data.forEach(function(book) {
                var row = `
                    <tr data-book-id="${book.BookId}">
                        <td>${book.BookName}</td>
                        <td>${book.Author}</td>
                        <td>${book.bookDescription}</td>
                        <td>${book.BookCopies}</td>
                        <td>${book.location}</td>
                        <td>${book.resourceType}</td>
                        <td>${book.Genres}</td>
                        <td>
                            <button class="reserve-button" onclick="reserveBook(${book.BookId})">Reserve</button>
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
                        console.error('Failed to load the search results:', textStatus, errorThrown);
                        console.log('Response Text:', jqXHR.responseText);  // Inspect the raw response
                    }
                });
            } else {
                loadFullList();
            }
        });

        function reserveBook(bookId) {
            $.ajax({
                url: '../process/reserveBookProcess.php',
                method: 'POST',
                data: { bookId: bookId },
                success: function(response) {
                    alert(response);
                    loadFullList(); // Refresh the book list after reservation
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Failed to reserve book:', textStatus, errorThrown);
                    console.log('Response Text:', jqXHR.responseText);  // Inspect the raw response
                }
            });
        }

        $(document).ready(function() {
            loadFullList();

            $('#bookTable').on('click', 'tr', function() {
                $(this).toggleClass('selected');
                const bookId = $(this).data('book-id');
                console.log(`Selected Book ID: ${bookId}`);
                if ($(this).hasClass('selected')) {
                    selectedBooks.push(bookId);
                } else {
                    selectedBooks = selectedBooks.filter(id => id !== bookId);
                }
                console.log('Current Selected Books:', selectedBooks);
            });

            $('#addToWaitingList').on('click', function() {
                localStorage.setItem('waitingList', JSON.stringify(selectedBooks));
                alert('Books added to the waiting list.');
                console.log('Waiting List:', JSON.parse(localStorage.getItem('waitingList')));
            });

            $('#checkoutBooks').on('click', function() {
                const waitingList = JSON.parse(localStorage.getItem('waitingList')) || [];
                console.log('Checkout List:', waitingList);
                if (waitingList.length === 0) {
                    alert('No books in the waiting list.');
                    return;
                }

                $.ajax({
                    url: '../process/checkoutProcess.php',
                    method: 'POST',
                    data: { bookIds: waitingList },            
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Failed to checkout books:', textStatus, errorThrown);
                        console.log('Response Text:', jqXHR.responseText);  // Inspect the raw response
                    }
                });
            });
        });
    </script>
</body>
</html>

