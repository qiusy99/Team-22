#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ( $_SESSION['role'] !== 'employee' && $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}
echo $_SESSION['user_id']
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="../styles/AddEvent.css">
    
</head>

    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Employee_Home.php">Home</a>
        <a href="../process/logoutProcess.php">LogOut</a>
    </div>

    <body>
        <div class="form-container">
            <h2>Create Event</h2>
            <form action="../process/AddEventProcess.php" method="post" enctype="multipart/form-data">
                <label for="name">Event Name:</label>
                <input type="text" id="name" name="name" required>
            
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" cols="50" required></textarea>
                
                <label for="image">Event Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
                
                <label for="scheduled_time">Scheduled Start Time:</label>
                <input type="datetime-local" id="scheduled_time" name="scheduled_time" required>
                
                <label for="end_time">Scheduled End Time:</label>
                <input type="datetime-local" id="end_time" name="end_time" required>
                
                <input type="submit" name="submit" value="Create Event">
            </form>
        </div>
    </body>
    
    