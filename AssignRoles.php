#!/usr/local/bin/php
<?php
session_start(); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in and has the 'admin' role
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../pages/Library_Login.html");
    exit();
}

$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['assign'])) {
        if (isset($_POST['username']) && isset($_POST['role'])) {
            $username = htmlspecialchars($_POST['username']);
            $role = htmlspecialchars($_POST['role']);
            $restricted_username = 'admin1';

            // Validate role
            $valid_roles = ['admin', 'employee', 'member'];
            if (!in_array($role, $valid_roles)) {
                die("Invalid role selected.");
            }

            if ($username === $restricted_username) {
                die("The role of '$restricted_username' cannot be changed.");
            }

            $sql = "UPDATE Login SET role = ? WHERE username = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die("Error preparing the statement: " . $mysqli->error);
            }

            $stmt->bind_param("ss", $role, $username);
            if ($stmt->execute()) {
                echo "Role assigned successfully.";
            } else {
                die("Error executing the statement: " . $stmt->error);
            }

            $stmt->close();
        } else {
            die("Username or role not set.");
        }
    } elseif (isset($_POST['delete'])) {
        if (isset($_POST['delete_username'])) {
            $delete_username = htmlspecialchars($_POST['delete_username']);
            $restricted_username = 'admin1';

            if ($delete_username === $restricted_username) {
                die("The user '$restricted_username' cannot be deleted.");
            }

            $sql = "DELETE FROM Login WHERE username = ?";
            $stmt = $mysqli->prepare($sql);
            if ($stmt === false) {
                die("Error preparing the statement: " . $mysqli->error);
            }

            $stmt->bind_param("s", $delete_username);
            if ($stmt->execute()) {
                echo "User deleted successfully.";
            } else {
                die("Error executing the statement: " . $stmt->error);
            }

            $stmt->close();
        } else {
            die("Username for deletion not set.");
        }
    }
}

$sql = "SELECT username FROM Login"; 
$result = $mysqli->query($sql);

if ($result === false) {
    die("Error executing the query: " . $mysqli->error);
}

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row['username'];
}

$result->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Role</title>
    <link rel="stylesheet" href="../styles/Library_Admin_Home.css">
    <style>
        .top-right-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 10px;
        }
        .top-right-buttons a {
            background-color: whitesmoke;
            color: black;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            font-size: 18px;
        }
        .top-right-buttons a:hover {
            background-color: #c8f3e5;
        }
    </style>
</head>
<body>
    <!-- Top Right Buttons -->
    <div class="top-right-buttons">
        <a href="Library_Admin_Home.php">Home</a>
        <a href="../process/logoutProcess.php">LogOut</a>
    </div>

    <header>
        <h1>Assign Roles</h1>
    </header>

    <div class="container">
        <!-- assigning roles -->
        <form action="AssignRoles.php" method="post">
            <label for="username">Select User:</label>
            <select name="username" id="username" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user); ?>"><?php echo htmlspecialchars($user); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label for="role">Select Role:</label>
            <select name="role" id="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="admin">Admin</option>
                <option value="employee">Employee</option>
                <option value="member">Member</option>
            </select>
            <br><br>
            <input type="submit" name="assign" value="Assign Role">
        </form>

        <!-- deleting user -->
        <form action="AssignRoles.php" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
            <label for="delete_username">Select User to Delete:</label>
            <select name="delete_username" id="delete_username" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user); ?>"><?php echo htmlspecialchars($user); ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <input type="submit" name="delete" value="Delete User">
        </form>
    </div>
</body>
</html>
