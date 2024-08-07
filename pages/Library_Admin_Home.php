#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in and has the 'admin' role
if ( $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Three Boxes Layout</title>
  <link rel="stylesheet" href="../styles/Library_Admin_Home.css">
</head>
<body>
  <!-- Header with title -->
   <span class="nav fr"><a href="../process/logoutProcess.php">Log Out</a></span>
  
  <header>
    <h1>Admin Home Page</h1>
  </header>

  <div class="container">
    <a href="AssignRoles.php" class="employee-box">Employee</a>
    <a href="AdminBook.php" class="book-box">Edit Book</a>
    <a href="AddEvent.php" class="event-box">Edit Event</a>
  </div>
</body>
</html>
