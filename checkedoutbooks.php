#!/usr/local/bin/php
<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!isset($_SESSION['userId'])) {
    header("Location: ../pages/Library_Login.html");
    exit();
}
$userId = $_SESSION['userId'];

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

$sql = "SELECT Books.BookName, Books.Author, Checkouts.CheckoutDate 
        FROM Checkouts 
        JOIN Books ON Checkouts.BookId = Books.BookId 
        WHERE Checkouts.idLogin = ?";
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die("Error preparing the statement: " . $mysqli->error);
}

$stmt->bind_param("i", $userId);
if (!$stmt->execute()) {
    die("Error executing the statement: " . $stmt->error);
}

$result = $stmt->get_result();
$checkedOutBooks = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$mysqli->close();
# To be continued
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checked Out Books</title>
</head>
<body>
    <h1>Checked Out Books</h1>

    <div id="checkedOutBooksContainer" align="center">
        <table id="checkedOutBooksTable">
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Checkout Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($checkedOutBooks as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['BookName']); ?></td>
                        <td><?php echo htmlspecialchars($book['Author']); ?></td>
                        <td><?php echo htmlspecialchars($book['CheckoutDate']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

