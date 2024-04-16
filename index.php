<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="Images/Logo1.png" alt=""style="width:80px;">
            </div>
            <ul class="nav-links">
                <li><a href="#home-section" class="nav-item active">Home</a></li>
                <li><a href="#events-section" class="nav-item">Event</a></li>
                <li><a href="#about-section" class="nav-item">About Us</a></li>
                <li><a href="login.php" class="nav-item">Login</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="content">

        <div class="home-section" id="home-section">
            <h1 class="tagline">Welcome to Our Community</h1>
            <button class="join-us-btn" onclick="location.href='signup.php'">Join Us</button>
        </div>

        <div class="events-section-heading">
            <h2>New Upcoming Events</h2>
        </div>

        <div class="events-section" id="events-section">
            <div class="events-container">
                <?php
                include 'db_connect.php'; 

                $sql = "SELECT e.*, GROUP_CONCAT(d.name SEPARATOR ', ') AS device_names
                FROM events e
                LEFT JOIN event_device ed ON e.event_id = ed.event_id
                LEFT JOIN devices d ON ed.device_id = d.device_id
                WHERE e.is_published = 1
                GROUP BY e.event_id
                ORDER BY e.event_date, e.event_time";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='event-card'>";
                        echo "<h2 class='event-title'>" . htmlspecialchars($row['title']) . "</h2>";
                        echo "<p class='event-date'>Date: " . date('M d, Y', strtotime($row['event_date'])) . " at " . date('H:i', strtotime($row['event_time'])) . "</p>";
                        echo "<p class='event-description'>" . htmlspecialchars($row['description']) . "</p>";
                        echo "<p class='event-Devices'>Devices Registred :" . htmlspecialchars($row['device_names']) . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No events to display.</p>";
                }
                ?>
            </div>
        </div>
        
        <div class="about-section" id="about-section">
            <div class="team-photo">
                <img src="images/Logo1.png" alt="Our Team">
            </div>
            <div class="about-info">
                <h2>About Our Website</h2>
                <p>This website is designed to bring together event organizers and participants in a vibrant community.
                    Here, you can find details about upcoming events, register as a participant, or sign up as an
                    organizer to promote your events.</p>
                    
                    <span style="font-size:25px; color: #6F55A2">Our Team</span>
                    <br><br>

                    <ol>
                        <li>Bassam Khan - Team Lead</li>
                        <li>Hamza Yaqoob - UI/UX Designer</li>
                        <li>Ahmad Faraz - Database manager</li>
                        <li>Waqar - Backend Developer</li>
                    </ol><br>
                <button onclick="location.href='login.php'">Join Us</button>
            </div>
        </div>

        <footer class="simple-footer">
            <ul class="footer-links">
            <li><a href="#home-section" class="nav-item active">Home</a></li>
                <li><a href="#events-section" class="nav-item">Event</a></li>
                <li><a href="#about-section" class="nav-item">About Us</a></li>
                <li><a href="login.php" class="nav-item">Login</a></li>
            </ul>
            <p class="footer-credits">&copy; 2024 Cryptoshow. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
