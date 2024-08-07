#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/Library_Login.html");
    exit();
}

$userId = $_SESSION['user_id'];

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to get checked out books for the current user
$sql = "SELECT c.BookId, b.BookName, b.Author, c.CheckoutDate, c.DueDate
        FROM Checkouts c
        JOIN Books b ON c.BookId = b.BookId
        WHERE c.idLogin = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$checkedOutBooks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $checkedOutBooks[] = $row;
    }
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checked Out Books</title>
    <link href="../../cis4930/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../styles/CheckedOutBooks.css">
    <link rel="stylesheet" href="../styles/BookSearch.css">
</head>
<body>
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
        <a href="../process/logoutProcess.php">LogOut</a>
    </div>
    <div class="container">
        <h1>Your Checked Out Books</h1>
        <?php if (empty($checkedOutBooks)) : ?>
            <p>You have no checked out books.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>Author</th>
                        <th>Checkout Date</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($checkedOutBooks as $book) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($book['BookName']); ?></td>
                            <td><?php echo htmlspecialchars($book['Author']); ?></td>
                            <td><?php echo htmlspecialchars($book['CheckoutDate']); ?></td>
                            <td><?php echo htmlspecialchars($book['DueDate']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

