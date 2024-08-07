#!/usr/local/bin/php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check for empty input fields
    if (empty($username) || empty($password)) {
        die("Username and password cannot be empty.");
    } else {
        echo "Form data received: Username = $username, Password = $password.<br>";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Login (username, password, role) VALUES (?, ?, 'member')";
        $stmt = $mysqli->prepare($sql);

    if (!$stmt->bind_param("ss", $username, $hashed_password)) {
        die("Error binding parameters: " . $stmt->error);
    } if ($stmt === false) {
        die("Error preparing the statement: " . $mysqli->error);
    }

    // Bind parameters
    if (!$stmt->bind_param("ss", $username, $hashed_password)) {
        die("Error binding parameters: " . $stmt->error);
    }

    // Execute the statement and check for errors
    if (!$stmt->execute()) {
        die("Error executing the statement: " . $stmt->error);
    }
     $stmt->bind_param("ss", $username, $hashed_password);
                header("Location: ../pages/Library_Login.html"); // Redirect to login page
                exit();
            $stmt->close();
        }
    }

$mysqli->close();
# Jingyi Fu
?>