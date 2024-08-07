#!/usr/local/bin/php
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['eventImage']) && isset($_POST['eventId'])) {
    $eventId = $_POST['eventId'];
    $file = $_FILES['eventImage'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $fileName = basename($file['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
            $mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            $stmt = $mysqli->prepare("UPDATE Events SET image_path = ? WHERE id = ?");
            $stmt->bind_param('si', $fileName, $eventId);

            if ($stmt->execute()) {
                echo 'Image uploaded and updated successfully.';
            } else {
                echo 'Error updating image path in database.';
            }

            $stmt->close();
            $mysqli->close();
        } else {
            echo 'Error uploading file.';
        }
    } else {
        echo 'File upload error.';
    }
} else {
    echo 'Invalid request.';
}
?>
