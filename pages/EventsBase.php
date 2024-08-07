#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic HTML Calendar with Events</title>
    <style>
     body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #e0f7fa; /* Light cyan background color */
    color: #333;
}
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.55);
        }
        .calendar-container {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 10px;
            vertical-align: top;
        }
        th {
            background-color: #f4f4f4;
        }
        td {
            background-color: #fff;
            position: relative;
            height: 100px; /* Adjust as needed */
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
        .month-buttons {
            margin-bottom: 20px;
        }
        .month-buttons button {
            padding: 10px 20px;
            border: 1px solid #ddd;
            background-color: #f4f4f4;
            color: #333;
            cursor: pointer;
            border-radius: 5px;
        }
        .month-buttons button:hover {
            background-color: #ddd;
        }

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
    <!-- Log Out button -->
    <div class="top-right-buttons">
        <a href="Library_Home_Page.html">Home</a>
    </div>

    <div class="container">
        <div class="calendar-container">
            <div class="month-buttons">
                <button onclick="prevMonth()">Previous Month</button>
                <button onclick="nextMonth()">Next Month</button>
            </div>
            <h1 id="month-year">July 2024 Calendar</h1>
            <table id="calendar-table">
               
            </table>
        </div>
        <div class="events-container">
            <h2>Events</h2>
                <ul class="events-list" id="events-list">
                    <!-- Event items will be added here -->
                </ul>
            </div>
        </div>
    </div>
</body>


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
