const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
let currentYear = 2024;
let currentMonth = 7; // August (0-based index)

function generateCalendar(month, year) {
    const calendarTable = document.getElementById('calendar-table');
    calendarTable.innerHTML = '';
    const monthYear = document.getElementById('month-year');
    monthYear.textContent = `${monthNames[month]} ${year} Events`;

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

        const eventsForDay = events.filter(event => {
            const eventDate = new Date(event.scheduled_time);
            return eventDate.getDate() === day && eventDate.getMonth() === month && eventDate.getFullYear() === year;
        });

        table += `<td>${day}`;
        eventsForDay.forEach(event => {
            table += `<div><a href="#event-${event.id}">${event.name}</a></div>`;
        });
        table += `</td>`;
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

function displayEvents() {
    const eventsList = document.getElementById('events-list');
    eventsList.innerHTML = '';
    events.forEach(event => {
        const eventItem = document.createElement('li');
        eventItem.className = 'event-item';
        eventItem.id = `event-${event.id}`;

        const isReserved = reservations.includes(event.id);
        const buttonText = isReserved ? "Reserved" : isLoggedIn ? "Reserve" : "Log in to Reserve";
        const buttonDisabled = isReserved || !isLoggedIn ? "disabled" : "";
        const buttonOnClick = isLoggedIn && !isReserved ? `onclick="reserveEvent(${event.id})"` : "";

        eventItem.innerHTML = `
            <h3>${event.name}</h3>
            <p>${event.description}</p>
            <p>Scheduled Time: ${new Date(event.scheduled_time).toLocaleString()}</p>
            <p>End Time: ${new Date(event.end_time).toLocaleString()}</p>
            <img src="${event.image_path}" alt="Event Image" width="200">
            <button class="reserve-button" ${buttonOnClick} ${buttonDisabled}>${buttonText}</button>
        `;
        eventsList.appendChild(eventItem);
    });
}

function reserveEvent(eventId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../process/ReserveEvent.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
            if (xhr.responseText.includes("Reservation successful!")) {
                const button = document.querySelector(`button[onclick="reserveEvent(${eventId})"]`);
                button.disabled = true;
                button.textContent = "Reserved";
                reservations.push(eventId); // Update reservations array
            } else if (xhr.responseText.includes("You have already reserved this event.")) {
                alert("You have already reserved this event.");
            }
        }
    };
    xhr.send(`eventId=${eventId}&userId=${userId}`);
}

function prevMonth() {
    if (currentMonth === 0) {
        currentMonth = 11;
        currentYear--;
    } else {
        currentMonth--;
    }
    generateCalendar(currentMonth, currentYear);
    displayEvents();
}

function nextMonth() {
    if (currentMonth === 11) {
        currentMonth = 0;
        currentYear++;
    } else {
        currentMonth++;
    }
    generateCalendar(currentMonth, currentYear);
    displayEvents();
}

document.addEventListener('DOMContentLoaded', () => {
    generateCalendar(currentMonth, currentYear);
    displayEvents();
});

