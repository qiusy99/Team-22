#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ( $_SESSION['role'] !== 'employee') {
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
  <link rel="stylesheet" href="../styles/Library_Employee_Home.css">
</head>
<body>
  <!-- Header with title -->
   <span class="nav fr"><a href="../process/logoutProcess.php">Log Out</a></span>
  <header>
    <h1>Employee Home Page</h1>
  </header>

  <div class="container">
  <a href="AdminBook.php" class="book-box">Edit Book</a>
  <a href="AddEvent.html" class="event-box">Edit Event</a>
  </div>
</body>
</html>
