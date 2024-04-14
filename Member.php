<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member | User</title>
    <link rel="stylesheet" href="member.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>

    <div class="Member-container" id= "Member">
        <div class="navbar">
            <div class="navbar-header">
                <img src="Images/Logo.png" alt="Website Logo" class="navbar-logo">
            </div>

            <div class="navbar-menu">
                <a href="#Profile_Details">Profile</a>
                <a href="#Member">Member</a>
                <a href="#Devices">Devices</a>
                <a href="#Events">Registered Events</a>
            </div>
            <a href="" class="logout-btn">Logout</a>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="header-info">
                    <div class="info">
                        <i class="fas fa-user avatar"></i>
                    </div>
                    <div class="user-details">
                        <h1>Logged IN User Name</h1>
                        <p>Logged In User's Email</p>
                    </div>
                </div>
            </div>

            <section id="Profile_Details" class="section-content">
                <div class="card">
                    <div id="profile-info">
                        <a href="#" class="edit-icon" onclick="updateform()">
                            <i class="fas fa-edit"></i>
                        </a>
                        <h3>Profile Information</h3>
                        <p>Full Name:
                        </p>
                        <p>User Email:
                        </p>
                    </div>

                    <div id="update-form" style="display: none;">
                        <form>
                            <a href="#" class="close-form" onclick="closeProfileEdit()"
                                style="border:none; text-decoration: none;">X</a>
                            <h3>View or Update your personal Details :</h3>
                            <label for="name">Full Name:</label>
                            <input type="text" id="name" name="name" value="">

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="" readonly style="cursor:not-allowed;">

                            <label for="password">Password:</label>
                            <input type="text" id="password" name="password" value="">

                            <label for="usertype">User Type:</label>
                            <input type="text" id="usertype" name="usertype" value="" readonly
                                style="cursor:not-allowed;">

                            <button type="submit">Update</button>
                        </form>
                    </div>
                </div>

            </section>

            <section id="Devices" class="section-content">
                <div class="card">
                    <h3>Devices</h3>
                    <p>Create or edit crypto devices.</p>
                    <div class="device-table-container">
                        <table class="device-table">
                            <thead>
                                <tr>
                                    <th>Device Name</th>
                                    <th>Description</th>
                                    <th>Registered in</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            // Fetch devices for the logged-in user
                            session_start();
                            include 'db_connect.php';

                            $userId = 1;
                            // Check if the user is logged in and retrieve their ID from the session
                            // if (isset($_SESSION['user_id'])) {
                            //     $userId = $_SESSION['user_id'];
                            // } else {
                            //     // Redirect to login page if the user is not logged in
                            //     header('Location: login.php');
                            //     exit;
                            // }

                            $devicesQuery = "SELECT * FROM devices WHERE user_id = ?";
                            $devicesStmt = $conn->prepare($devicesQuery);
                            $devicesStmt->bind_param("i", $userId);
                            $devicesStmt->execute();
                            $devicesResult = $devicesStmt->get_result();
                            while ($device = $devicesResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($device['name']); ?></td>
                                    <td><?php echo htmlspecialchars($device['description']); ?></td>
                                    <td>
                                        <!-- Fetch and list events for this device -->
                                        <?php
                                        $eventsQuery = "SELECT e.* FROM events e 
                                                        INNER JOIN Event_device ed ON e.event_id = ed.event_id
                                                        WHERE ed.device_id = ?";
                                        $eventsStmt = $conn->prepare($eventsQuery);
                                        $eventsStmt->bind_param("i", $device['device_id']);
                                        $eventsStmt->execute();
                                        $eventsResult = $eventsStmt->get_result();
                                        
                                        // List all events associated with this device
                                        while ($event = $eventsResult->fetch_assoc()) {
                                            echo "<div>" . htmlspecialchars($event['title']) . "</div>";
                                        }
                                        ?>
                                    </td>
                                    <td>

                                        <button onclick="showEditDeviceModal(<?php echo $device['device_id']; ?>)">Edit</button>
                                        <button onclick="deleteDevice(<?php echo $device['device_id']; ?>)">Delete</button>

                                    </td>
                                </tr>
                            <?php endwhile; ?>

                            </tbody>
                            <tfoot>
                                <tr id="new-device-row">
                                    <td colspan="4">
                                        <a href="#" class="plus-icon" onclick="showAddDeviceModal()">+ Add New
                                            Device</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>



                    <div id="deviceEditModel" class="modal">
                        <span class="close" onclick="closeEditDeviceModal()">&times;</span>
                        <div class="modal-content">
                            <form id="deviceEditForm">
                                <input type="hidden" id="deviceID" name="deviceID">
                                <h2>Edit Device / Add Device : </h2>

                                <label for="deviceName">Device Name:</label>
                                <input type="text" id="deviceName" name="deviceName" required>

                                <label for="deviceDescription">Device Description:</label>
                                <input type="text" id="deviceDescription" name="deviceDescription" required>

                                <label for="eventList">Select Events:</label>
                                <select id="eventList" name="eventList[]" multiple>
                                    <option value="1">Option1</option>
                                    <option value="2">Option2</option>
                                    <option value="3">Option3</option>
                                </select>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <input type="submit" id="updateDeviceBtn" name="updateDeviceBtn"
                                        value="Update Device" style="display: block;">
                                    <!-- <input type="submit" id="deleteDeviceBtn" name="deleteDeviceBtn"
                                        value="Delete Device" style="display: block;"> -->

                                </div>
                            </form>

                        </div>

                    </div>
                    <div id="deviceAddModel" class="modal">
                        <span class="close" onclick="closeAddDeviceModal()">&times;</span>
                        <div class="modal-content">
                            <form id="deviceEditForm" method="post" action="add_device.php">
                                <input type="hidden" id="deviceID" name="deviceID">
                                <h2>Edit Device / Add Device : </h2>

                                <label for="deviceName">Device Name:</label>
                                <input type="text" id="deviceName" name="deviceName" required>

                                <label for="deviceDescription">Device Description:</label>
                                <input type="text" id="deviceDescription" name="deviceDescription" required>

                                <label for="eventList">Select Events:</label>
                                <select id="eventList" name="eventList[]" multiple>
                                <?php
                                    include 'db_connect.php';

                                    $sql = "SELECT event_id, title FROM events ORDER BY event_date ASC";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['event_id'] . "'>" . htmlspecialchars($row['title']) . "</option>";
                                        }
                                    }
                                ?>

                                </select>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                    <input type="submit" id="AddDeviceBtn" name="AddDeviceBtn" value="Add Device"
                                        style="display: block;">

                                </div>
                            </form>
                        </div>
                    </div>
            </section>

            <section id="Events" class="section-content">
                <div class="card">
                    <h3>Events</h3>
                    <p>Events in which User has taken part.</p>

                    <div class="events-table-container">
                        <table class="events-table">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Description</th>
                                    <th>Devices Registered</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- PHP code to fetch and display events -->
                                <?php
                                include 'db_connect.php'; // Your database connection file

                                $userId = $_SESSION['user_id'] ?? 1;
                                $sql = "SELECT DISTINCT e.* FROM events e 
                                INNER JOIN Event_device ed ON e.event_id = ed.event_id
                                INNER JOIN devices d ON ed.device_id = d.device_id
                                WHERE d.user_id = ?
                                ORDER BY e.event_date ASC, e.event_time ASC";
                        
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $userId);
                                $stmt->execute();
                                $result = $stmt->get_result();


                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['event_date']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['event_time']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                                        echo "<td>" . ($row['is_published'] ? 'Published' : 'Unpublished') . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No registered events to display</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>


        </div>
    </div>

    <script>

        function showAddDeviceModal() {
            var DeviceAddForm = document.getElementById("deviceAddModel");
            DeviceAddForm.style.display = "block";
        }

        function closeEditDeviceModal() {
            var DeviceFormClose = document.getElementById("deviceEditModel");
            DeviceFormClose.style.display = "none";
        }
        function closeAddDeviceModal() {
            var DeviceFormClose = document.getElementById("deviceAddModel");
            DeviceFormClose.style.display = "none";
        }
        function showEditDeviceModal(deviceID) {
            var DeviceEditForm = document.getElementById("deviceEditModel");
            DeviceEditForm.style.display = "block";
        }
        function deleteDevice(deviceID) {
            
            console.log(deviceID);
            if (confirm('Are you sure you want to delete this device? This action cannot be undone.')) {
                window.location.href = 'delete_device.php?deviceID=' + deviceID;
            }
        }

        function updateform() {
            var Profile = document.getElementById("update-form");
            Profile.style.display = "block";
        }

        function closeProfileEdit() {
            var ProfileClose = document.getElementById("update-form");
            ProfileClose.style.display = "none";
        }

        document.addEventListener("DOMContentLoaded", function() {
    const navbarLinks = document.querySelectorAll('.navbar-menu a');
    const sections = document.querySelectorAll('.section');
    
    navbarLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                sections.forEach(section => {
                    section.style.display = 'none';
                });
                
                // Show the target section
                targetSection.style.display = 'block';
                
                // Scroll to the target section
                window.scrollTo({
                    top: targetSection.offsetTop - 70, // Adjust according to the height of the navbar
                    behavior: 'smooth'
                });
            }
        });
    });
});




    </script>

</body>

</html>