#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: Library_Login.php");
    exit();
}

$userRole = $_SESSION['role'];

// Ensure only admin and employee can access this page
if ($userRole !== 'admin' && $userRole !== 'employee') {
    die("Unauthorized access.");
}

$checkedOutBooks = [];
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch book checkout details along with user information
$sql = "SELECT c.idCheckout, b.BookName, b.Author, c.CheckoutDate, c.DueDate, l.username
        FROM Checkouts c
        JOIN Books b ON c.BookId = b.BookId
        JOIN Login l ON c.idLogin = l.idLogin";

$result = $mysqli->query($sql);
if ($result) {
    $checkedOutBooks = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}
$mysqli->close();
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
    <title>Return Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
    </style>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
    </div>

    <h1 align="center">Return Books</h1>

    <div id="bookTableContainer" align="center">
        <table id="returnTable">
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Checkout Date</th>
                    <th>Due Date</th>
                    <th>Checked Out By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($checkedOutBooks as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['BookName']) ?></td>
                        <td><?= htmlspecialchars($book['Author']) ?></td>
                        <td><?= htmlspecialchars($book['CheckoutDate']) ?></td>
                        <td><?= htmlspecialchars($book['DueDate']) ?></td>
                        <td><?= htmlspecialchars($book['username']) ?></td>
                        <td>
                            <button class="returnButton" data-id="<?= htmlspecialchars($book['idCheckout']) ?>">Return</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Handle return button click
        $(document).on('click', '.returnButton', function() {
            var checkoutId = $(this).data('id');

            $.ajax({
                url: '../process/returnBookProcess.php',
                method: 'POST',
                data: { checkoutId: checkoutId },
                success: function(response) {
                    alert("Successfully returned a book!");
                    location.reload(); // Reload page to update the list
                }
            });
        });
    </script>
</body>
</html>

