#!/usr/local/bin/php
<?php
session_start();

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['Username']);
    $password = htmlspecialchars($_POST['Password']);

    $sql = "SELECT idLogin, password, role FROM Login WHERE username = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        die("Error preparing the statement: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param("s", $username);
    if (!$stmt->execute()) {
        die("Error executing the statement: " . $stmt->error);
    }

    // Store result and bind result variables
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);
    // Fetch the result and verify password
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;

        switch ($role) {
            case 'admin':
                header("Location: ../pages/Library_Admin_Home.php");
                break;
            case 'employee':
                header("Location: ../pages/Library_Employee_Home.php");
                break;
            case 'member':
                header("Location: ../pages/Library_Member_Home.php");
                break;
            default:
                echo "Invalid role!";
                break;
        }
        exit();
    } else {
        echo "Invalid username or password!";
    }

    $stmt->close();
}

$mysqli->close();
?>