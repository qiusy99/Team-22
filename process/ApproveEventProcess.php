#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}

if (isset($_POST['id'])) {
    $eventId = $_POST['id'];
    $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("UPDATE Events SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Success";
    } else {
        echo "Error";
    }

    $stmt->close();
    $mysqli->close();
}
?>
