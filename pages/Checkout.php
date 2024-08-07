#!/usr/local/bin/php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Checkout Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #77b300 3px solid;
        }
        header a {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            list-style: none;
        }
        header li {
            display: inline;
            padding: 0 20px 0 20px;
        }
        header #branding {
            float: left;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: right;
            margin-top: 10px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
    <script>
        function showTab(event, tabId) {
            // Hide all tab contents
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }

            // Remove active class from all tabs
            var tabs = document.querySelectorAll('header nav ul li a');
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }

            // Show the clicked tab's content and add active class to the clicked tab
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Library System</h1>
            </div>
            <nav>
                <ul>
                <li><a href="Library_Member_Home.php" class="active">Home</a></li>
                    <li><a href="#" onclick="showTab(event, 'checked-out')">Checked Out Books</a></li>
                    <li><a href="#" onclick="showTab(event, 'reserved-events')">Reserved Events</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div id="Welcome" class="tab-content active">
            <h2>Welcome to the Library System</h2>
            <p>This is the home page of the library system. Here you can find information about the library, upcoming events, new arrivals, and more. Use the tabs above to navigate through different sections.</p>
        </div>

        <div id="checked-out" class="tab-content">
            <h2>Checked Out Books</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th> 
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>The Great Gatsby</td>
                        <td>F. Scott Fitzgerald</td>
                        <td>2024-08-15</td>
                    </tr>
                    <tr>
                        <td>To Kill a Mockingbird</td>
                        <td>Harper Lee</td>
                        <td>2024-08-20</td>
                    </tr>
                    <tr>
                        <td>1984</td>
                        <td>George Orwell</td>
                        <td>2024-08-25</td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
        </div>
        <div id="reserved-events" class="tab-content">
            <h2>Reserved Events</h2>
            <table>
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Book Reading: Modern Classics</td>
                        <td>2024-09-01</td>
                        <td>Main Library Hall</td>
                    </tr>
                    <tr>
                        <td>Author Meet & Greet: Jane Doe</td>
                        <td>2024-09-15</td>
                        <td>Conference Room B</td>
                    </tr>
                    <tr>
                        <td>Writing Workshop: Fiction</td>
                        <td>2024-09-25</td>
                        <td>Room 101</td>
                    </tr>
                    <!-- More rows as needed -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
