#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Events</title>
    <link rel="stylesheet" href="../styles/Events.css">
</head>
<body>
    <!-- Log Out button -->
    <div class="top-right-buttons">
        <a href="Checkout.php">CheckOut</a>
        <a href="../process/logoutProcess.php">LogOut</a>
        <a href="Library_Member_Home.php">Home</a>
    </div>

    <div class="container">
        <div class="calendar-container">
            <div class="month-buttons">
                <button onclick="prevMonth()">Previous Month</button>
                <button onclick="nextMonth()">Next Month</button>
            </div>
            <h1 id="month-year">August 2024 Events</h1>
            <table id="calendar-table">
                <!-- Calendar will be generated here by JavaScript -->
            </table>
        </div>
        <div class="events-container">
            <ul class="events-list" id="events-list">
                <h2> Events </h2>
                <!-- Event items will be added here -->
            </ul>
        </div>
    </div>
</body>


    <script>
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let currentYear = 2024;
        let currentMonth = 7; // July (0-based index)
        let events = [];

        function generateCalendar(month, year) {
    const calendarTable = document.getElementById('calendar-table');
    calendarTable.innerHTML = '';
    const monthYear = document.getElementById('month-year');
    monthYear.textContent = `${monthNames[month]} ${year} Calendar`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let table = `<tr>
                    <th>Sun</th>
                    <th>Mon</th>
                    <th>Tue</th>
                    <th>Wed</th>
                    <th>Thu</th>
                    <th>Fri</th>
                    <th>Sat</th>
                 </tr><tr>`;
    
    for (let i = 0; i < firstDay; i++) {
        table += `<td></td>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
        if ((firstDay + day - 1) % 7 === 0 && day !== 1) {
            table += `</tr><tr>`;
        }

        const eventCount = events.filter(event => {
            const eventDate = new Date(event.date);
            return eventDate.getDate() === day && eventDate.getMonth() === month && eventDate.getFullYear() === year;
        }).length;

        table += `<td>${day}${eventCount > 0 ? `<div class="event-marker">${eventCount}</div>` : ''}</td>`;
    }

    const remainingCells = (firstDay + daysInMonth) % 7;
    if (remainingCells !== 0) {
        for (let i = remainingCells; i < 7; i++) {
            table += `<td></td>`;
        }
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

        function addEvent() {
            const date = document.getElementById('event-date').value;
            const time = document.getElementById('event-time').value;
            const description = document.getElementById('event-description').value;
            const image = document.getElementById('event-image').files[0];

            if (date && time && description && image) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    events.push({
                        date: new Date(`${date} ${time}`),
                        description: description,
                        image: e.target.result
                    });

                    const eventsList = document.getElementById('events-list');
                    const newEvent = document.createElement('li');
                    newEvent.className = 'event-item';
                    newEvent.innerHTML = `
                        ${date} at ${time}: ${description}<br>
                        <img src="${e.target.result}" alt="Event Image">
                        <button class="reserve-button" onclick="reserveEvent(this)">Reserve</button>
                    `;
                    eventsList.appendChild(newEvent);

                    generateCalendar(currentMonth, currentYear);
                };
                reader.readAsDataURL(image);

                document.getElementById('event-date').value = '';
                document.getElementById('event-time').value = '';
                document.getElementById('event-description').value = '';
                document.getElementById('event-image').value = '';
            } else {
                alert('Please enter a date, time, description, and image for the event.');
            }
        }

        function reserveEvent(button) {
            alert('Event Reserved Successfully!');
            button.disabled = true;
            button.textContent = 'Reserved';
        }

        document.addEventListener('DOMContentLoaded', () => {
            generateCalendar(currentMonth, currentYear);
        });
    </script>
</body>
</html>
