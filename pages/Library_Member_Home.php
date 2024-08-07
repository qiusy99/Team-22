#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ( $_SESSION['role'] !== 'member') {
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
  <link rel="stylesheet" href="../styles/Library_Member_Home.css">
</head>
<body>
  <!-- Header with title -->
  <span class="nav fr"><a href="../process/logoutProcess.php">Log Out</a></span>
  
  <header>
    <h1>Member Home Page</h1>
  </header>

  <div class="container">
    <a href="MemberBook.php" class="book-box">Search Book</a>
    <a href="Events.php" class="event-box">View Event</a>
    <a href="Checkout.php" class="checkedout-box">Checked Out</a>
  </div>
</body>
</html>
