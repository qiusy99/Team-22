#!/usr/local/bin/php
<?php
$mysqli = new mysqli("mysql.cise.ufl.edu","moore.cameron","Sadie2012","Team22");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

if (isset($_GET['BookId'])) {
    $bookID = $_GET["BookId"];
    $deleteBookQuery = "DELETE FROM Books WHERE BookId = $bookID";
    if ($mysqli->query($deleteBookQuery)) {
        echo "Book deleted successfully.";
   } else {
        echo "Error: " . $mysqli->error;
    }
} else {
    echo "Invalid request.";
}

$mysqli->close();
header("Location: ../pages/AdminBook.php");
exit();

# modified by Jingyi Fu, written by Cameron Moore
# finished Aug 2nd
# names are formatted

?>

