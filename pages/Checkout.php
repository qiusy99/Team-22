#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: Library_Member_Home.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

// Get users for admin/employee role
$users = [];
if ($userRole === 'admin' || $userRole === 'employee') {
    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }
    $result = $mysqli->query("SELECT idLogin, username FROM Login");
    if ($result) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }
    $mysqli->close();
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
    <link href="../styles/BookSearch.css" rel="stylesheet" type="text/css">
    <title>Checkout Books</title>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
    </div>

    <h1 align="center">Checkout Books</h1>

    <div align="center">
        <?php if ($userRole === 'admin' || $userRole === 'employee'): ?>
            <label for="userSelect">Select User:</label>
            <select id="userSelect" name="userSelect">
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['idLogin'] ?>"><?= $user['username'] ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </div>

    <div id="bookTableContainer" align="center">
        <table id="checkoutTable">
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

    <script>
        function updateCheckoutTable(data) {
            $('#checkoutTable tbody').empty();
            data.forEach(function(book) {
                var checkoutButton = book.BookCopies > 0 
                    ? `<button class="checkoutButton" data-id="${book.BookId}">Checkout</button>` 
                    : `<button class="checkoutButton" disabled>Out of Stock</button>`;

                var row = `
                    <tr>
                        <td>${book.BookName}</td>
                        <td>${book.Author}</td>
                        <td>${book.bookDescription}</td>
                        <td>${book.BookCopies}</td>
                        <td>${book.location}</td>
                        <td>${book.resourceType}</td>
                        <td>${book.Genres}</td>
                        <td>${checkoutButton}</td>
                    </tr>
                `;
                $('#checkoutTable tbody').append(row);
            });
        }

        function loadBooksForCheckout() {
            $.ajax({
                url: '../process/BookSearchProcess.php',
                method: 'GET',
                data: { query: ''},
                dataType: 'json',
                success: function(response) {
                    updateCheckoutTable(response);
                }
            });
        }

        $(document).ready(function() {
            loadBooksForCheckout();
        });

        // Handle checkout button click
        $(document).on('click', '.checkoutButton', function() {
            var bookId = $(this).data('id');
            var userId = $('#userSelect').val(); // Get selected user ID

            $.ajax({
                url: '../process/checkoutProcess.php',
                method: 'POST',
                data: { bookId: bookId, userId: userId },
                success: function(response) {
                    alert("Successfully checked out a book!");
                    loadBooksForCheckout(); // Reload book list to update availability
                }
            });
        });
    </script>
</body>
</html>
