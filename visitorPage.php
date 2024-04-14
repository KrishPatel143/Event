<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">Hack-time</div>
            <ul class="nav-links">
                <li><a href= #home-section class="nav-item active">Home</a></li>
                <li><a href= #events-section class="nav-item">Event</a></li>
                <li><a href= #past-events-container class="nav-item">Past Events</a></li>
                <li><a href= #about-section class="nav-item">About Us</a></li>
                <li><a href="login.html" class="nav-item">Login</a></li>
            </ul>
        </nav>
    </header>
    <div class="content">

        <!-- Existing content above... -->
        <div class="home-section" id="home-section">
            <h1 class="tagline">Welcome to Our Community</h1>
            <button class="join-us-btn" onclick="location.href='login.html'">Join Us</button>
        </div>
        <!-- Existing content below... -->

        <div class="events-section-heading">
            <h2>New Upcoming Events - Join Us Now!</h2>
        </div>


        <!-- Existing content above... -->
        <div class="events-section" id="events-section">
            <div class="events-container">
                <!-- Event Card 1 -->
                <div class="event-card">
                    <h2 class="event-title">Event Title One</h2>
                    <img src="images/g-1.jpg" alt="Event Image One" class="event-image">
                    <p class="event-date">Date: Jan 1, 2024</p>
                    <p class="event-description">This is a short description of the first event. Exciting details about
                        the event go here.</p>
                    <button class="register-btn" onclick="location.href='login.html'">Register</button>
                </div>

                <div class="event-card">
                    <h2 class="event-title">Event Title One</h2>
                    <img src="images/g-1.jpg" alt="Event Image One" class="event-image">
                    <p class="event-date">Date: Jan 1, 2024</p>
                    <p class="event-description">This is a short description of the first event. Exciting details about
                        the event go here.</p>
                    <button class="register-btn" onclick="location.href='login.html'">Register</button>
                </div>

                <div class="event-card">
                    <h2 class="event-title">Event Title One</h2>
                    <img src="images/g-1.jpg" alt="Event Image One" class="event-image">
                    <p class="event-date">Date: Jan 1, 2024</p>
                    <p class="event-description">This is a short description of the first event. Exciting details about
                        the event go here.</p>
                    <button class="register-btn" onclick="location.href='login.html'">Register</button>
                </div>

                <!-- Repeat for Event Cards 2-6 -->
                <!-- ... -->
            </div>
        </div>
        <!-- Existing content below... -->


        <div class="past-events-section-heading">
            <h2>Previous Memorable Events</h2>
        </div>

        <!-- Existing content above... -->

        <div class="past-events-container" id="past-events-container">
            <!-- Past Event Card 1 -->
            <div class="past-event-card">
                <h2 class="event-title">Past Event Title One</h2>
                <img src="images/g-1.jpg" alt="Past Event Image One" class="event-image">
                <p class="event-date">Date: Jan 1, 2024</p>
                <p class="event-description">This is a short description of the past event.</p>
                <button class="event-finished-btn" onclick="location.href='event-finished.html'">Sorry, Event has
                    Finished</button>
            </div>

            <div class="past-event-card">
                <h2 class="event-title">Past Event Title One</h2>
                <img src="images/g-1.jpg" alt="Past Event Image One" class="event-image">
                <p class="event-date">Date: Jan 1, 2024</p>
                <p class="event-description">This is a short description of the past event.</p>
                <button class="event-finished-btn" onclick="location.href='event-finished.html'">Sorry, Event has
                    Finished</button>
            </div>

            <div class="past-event-card">
                <h2 class="event-title">Past Event Title One</h2>
                <img src="images/g-1.jpg" alt="Past Event Image One" class="event-image">
                <p class="event-date">Date: Jan 1, 2024</p>
                <p class="event-description">This is a short description of the past event.</p>
                <button class="event-finished-btn" onclick="location.href='event-finished.html'">Sorry, Event has
                    Finished</button>
            </div>
            <!-- Repeat for Past Event Cards 2-6 -->
            <!-- ... -->
        </div>


        <div class="about-section" id="about-section">
            <div class="team-photo">
                <img src="images/about-img.jpg" alt="Our Team">
            </div>
            <div class="about-info">
                <h2>About Our Website</h2>
                <p>This website is designed to bring together event organizers and participants in a vibrant community.
                    Here, you can find details about upcoming events, register as a participant, or sign up as an
                    organizer to promote your events.</p>
                <button onclick="location.href='login.html'">Contact Us</button>
            </div>
        </div>




        <footer class="simple-footer">
            <ul class="footer-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="events.html">Events</a></li>
                <li><a href="past-events.html">Past Events</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="contact.html">Contact</a></li>
            </ul>
            <p class="footer-credits">&copy; 2024 Your Website Name. All rights reserved.</p>
        </footer>


        <!-- Existing content below... -->

        <!-- Content that causes the scrollbar -->
    </div>

    <!-- Additional content goes here -->
</body>

</html>