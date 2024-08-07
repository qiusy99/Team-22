#!/usr/local/bin/php
<?php
$mysqli = new mysqli("mysql.cise.ufl.edu", "moore.cameron", "Sadie2012", "Team22");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['event-date'];
    $time = $_POST['event-time'];
    $description = $_POST['event-description'];
    $scheduled_time = $date . ' ' . $time;
    $end_time = date("Y-m-d H:i:s", strtotime($scheduled_time) + 3600); // Example: 1 hour duration
    $image_path = '';

    // Handle image upload
    if (isset($_FILES['event-image']) && $_FILES['event-image']['error'] == UPLOAD_ERR_OK) {
        $image_tmp_path = $_FILES['event-image']['tmp_name'];
        $image_name = basename($_FILES['event-image']['name']);
        $target_directory = '../uploads/';
        $target_file = $target_directory . $image_name;

        // Ensure the uploads directory exists
        if (!is_dir($target_directory)) {
            mkdir($target_directory, 0777, true);
        }

        if (move_uploaded_file($image_tmp_path, $target_file)) {
            $image_path = $target_file;
        } else {
            echo 'Error uploading image.';
        }
    }

    // Insert event into database
    $insertEventQuery = "INSERT INTO Events (name, description, image_path, scheduled_time, end_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insertEventQuery);
    $stmt->bind_param("sssss", $description, $description, $image_path, $scheduled_time, $end_time);
    $stmt->execute();

    $stmt->close();
    $mysqli->close();

    // Redirect to the same page to display updated events
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive HTML Calendar with Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://example.com/background.jpg'); /* Replace with your desired background image URL */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #fff;
            text-shadow: 1px 1px 2px #000;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }
        th {
            background-color: #333;
        }
        td {
            background-color: #555;
            position: relative;
        }
        .event-marker {
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            position: absolute;
            top: 5px;
            right: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        .calendar-container {
            text-align: center;
            margin-top: 20px;
        }
        .events-container {
            text-align: center;
            margin-top: 20px;
        }
        .events-list {
            list-style-type: none;
            padding: 0;
        }
        .event-item {
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 5px;
            background-color: #444;
            border-radius: 5px;
            position: relative;
        }
        .event-form {
            margin-top: 20px;
        }
        .event-form input {
            padding: 8px;
            margin: 5px;
            border: none;
            border-radius: 5px;
        }
        .event-form button {
            padding: 8px 16px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
        .event-form button:hover {
            background-color: #555;
        }
        .month-buttons {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .month-buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }
        .month-buttons button:hover {
            background-color: #555;
        }
        .reserve-button {
            position: absolute;
            right: 10px;
            bottom: 10px;
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .reserve-button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="calendar-container">
            <div class="month-buttons">
                <button onclick="prevMonth()">Previous Month</button>
                <button onclick="nextMonth()">Next Month</button>
            </div>
            <h1 id="month-year">July 2024 Calendar</h1>
            <table id="calendar-table">
                <!-- Calendar will be generated here by JavaScript -->
            </table>
        </div>
        <div class="events-container">
            <h2>Events</h2>
            <ul class="events-list" id="events-list">
                <!-- Event items will be added here -->
                <?php
                // Fetch events from the database and display them
                $result = $mysqli->query("SELECT * FROM Events ORDER BY scheduled_time");
                while ($row = $result->fetch_assoc()) {
                    echo '<li class="event-item">';
                    echo $row['name'] . ' at ' . $row['scheduled_time'] . ': ' . $row['description'];
                    if ($row['image_path']) {
                        echo '<br><img src="' . $row['image_path'] . '" alt="Event Image" style="max-width: 100px; margin-top: 10px;">';
                    }
                    echo '<button class="reserve-button" onclick="reserveEvent(this)">Reserve</button>';
                    echo '</li>';
                }
                $result->close();
                ?>
            </ul>
            <div class="event-form">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="text" name="event-date" placeholder="Date (e.g., July 4)" required>
                    <input type="time" name="event-time" placeholder="Time" required>
                    <input type="text" name="event-description" placeholder="Event Description" required>
                    <input type="file" name="event-image">
                    <button type="submit">Add Event</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let currentYear = 2024;
        let currentMonth = 6; // July (0-based index)
        let events = [];

        function generateCalendar(month, year) {
            const calendarTable = document.getElementById('calendar-table');
            calendarTable.innerHTML = '';
            const monthYear = document.getElementById('month-year');
            monthYear.textContent = `${monthNames[month]} ${year} Calendar`;

            const firstDay = new Date(year, month).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let table = `<tr>
                            <th>Sunday</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                         </tr><tr>`;
            
            for (let i = 0; i < firstDay; i++) {
                table += `<td></td>`;
            }

            for (let day = 1; day <= daysInMonth; day++) {
                if ((firstDay + day - 1) % 7 === 0 && day !== 1) {
                    table += `</tr><tr>`;
                }

                const eventCount = events.filter(event => new Date(event.date).getDate() === day && new Date(event.date).getMonth() === month && new Date(event.date).getFullYear() === year).length;
                table += `<td>${day}${eventCount > 0 ? `<div class="event-marker">${eventCount}</div>` : ''}</td>`;
            }

            while ((firstDay + daysInMonth) % 7 !== 0) {
                table += `<td></td>`;
                daysInMonth++;
            }

            table += `</tr>`;
            calendarTable.innerHTML = table;
        }

        function prevMonth() {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            generateCalendar(currentMonth, currentYear);
        }

        function nextMonth() {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            generateCalendar(currentMonth, currentYear);
        }

        function reserveEvent(button) {
            alert('Event reserved successfully!');
            button.disabled = true;
            button.textContent = 'Reserved';
        }

        document.addEventListener('DOMContentLoaded', () => {
            generateCalendar(currentMonth, currentYear);
        });
    </script>
</body>
</html>