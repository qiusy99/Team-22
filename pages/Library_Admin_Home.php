#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in and has the 'admin' role
if ($_SESSION['role'] !== 'admin') {
    header("Location: Library_Employee_Home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Home Page</title>
  <link rel="stylesheet" href="../styles/Library_Admin_Home.css">
</head>
<body>
  <!-- Header with title -->
  <div class="top-right-buttons">
      <a href="Checkout.php">CheckOut</a>
      <a href="../process/logoutProcess.php">LogOut</a>
  </div>
  
  <header>
    <h1>Admin Home Page</h1>
  </header>

  <div class="container">
    <a href="AssignRoles.php" class="employee-box">Assign Roles</a>
    <a href="AdminBook.php" class="book-box">Edit Books</a>
    <a href="AdminEvents.php" class="event-box">Edit/Approve Events</a>
    <a href="Events.php" class="viewevent-box">View Events</a>
    <a href="Reservations.php" class="reserv-box">View Reservations</a>
    <a href="ReservedBooks.php" class="reserved-box">Reserved Books</a>
    <a href="ReturnBooks.php" class="reserved-box">Return Books</a>
    <a href="CheckedOutBooks.php" class="reserved-box">Checked Out Books</a>
  </div>
</body>
</html>

