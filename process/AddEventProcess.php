#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SESSION['role'] !== 'employee' && $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $scheduled_time = $_POST['scheduled_time'];
    $end_time = $_POST['end_time'];
    $is_approved = 0; // Default value, adjust based on your requirements
    $eventDate = date('Y-m-d', strtotime($scheduled_time)); // Extract date from scheduled_time

    // Handle file upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = '../uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    // Prepare SQL statement
    $sql = "INSERT INTO Events (name, description, image_path, scheduled_time, is_approved, end_time, eventDate)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssssiss', $name, $description, $image_path, $scheduled_time, $is_approved, $end_time, $eventDate);

    if ($stmt->execute()) {
        echo "Event created successfully!";
        header("Location: ../pages/Events2.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../pages/AddEvent.php");
    exit();
}
?>
