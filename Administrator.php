<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $sql = "SELECT fullname, email, password, role FROM users WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fullname = $row["fullname"];
            $email = $row["email"];
            $password = $row["password"];
            $role = $row["role"];
        }
    } else {
        echo "No user found.";
    }

    // Fetching Member details
    $memberDetails = array();
    $sql = "SELECT id,fullname, email, password FROM users WHERE role = 'Participant'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $memberDetails[] = $row;
        }
    }

    // Fetching Admin details
    $adminDetails = array();
    $sql = "SELECT fullname, email FROM users WHERE role = 'Organizer'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $adminDetails[] = $row;
        }
    }




    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="member.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>

    <div class="dashboard-container" id="dashboard">
        <div class="navbar">
            <div class="navbar-header">
                <img src="Images/Logo.png" alt="Website Logo" class="navbar-logo">
            </div>

            <div class="navbar-menu">
                <a href="#dashboard">Dashboard</a>
                <a href="#Profile_Details">Profile</a>
                <a href="#Published_Events">Published Events</a>
                <a href="#Unpublished_Events">Unpublished Events</a>
                <a href="#Members">Members</a>
            </div>
            <a href="Logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="main-content">
            <div class="header">
                <div class="header-info">
                    <div class="info">
                        <i class="fas fa-user avatar"></i>
                    </div>
                    <div class="user-details">
                        <h1><?php echo $fullname; ?> (<?php echo $role; ?>)</h1>
                        <p><?php echo $email; ?></p>
                    </div>
                </div>
            </div>

            <section id="Profile_Details" class="section-content">
                <div class="card">
                    <div id="profile-info">
                        <a href="#" class="edit-icon" onclick="updateform()">
                            edit
                        </a>
                        <h3>Profile Information</h3>
                        <p>Full Name: <?php echo $fullname ?>
                        </p>
                        <p>User Email: <?php echo $email ?>
                        </p>
                    </div>

                    <div id="update-form" style="display : none;">
                        <form id="ProfileEditform" method="post" action="update_profile.php">
                            <a href="#" class="close-form" onclick="closeProfileEdit()"
                                style="border:none; text-decoration: none;">Close</a>
                            <h3>View or Update your personal Details :</h3>
                            <label for="name">Full Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo $fullname; ?>">

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly
                                style="cursor:not-allowed;">

                            <label for="password">Password:</label>
                            <input type="text" id="password" name="password" value="<?php echo $password; ?>">

                            <label for="usertype">User Type:</label>
                            <input type="text" id="usertype" name="usertype" value="<?php echo $role; ?>" readonly
                                style="cursor:not-allowed;">

                            <button type="submit">Update</button>
                        </form>
                    </div>
                </div>

            </section>

            <section id="Published_Events" class="section-content">
                <div class="card">
                    <h3>Published Events</h3>
                    <p>Events which are for everyone.</p>

                    <div class="published-event-table-container">
                        <table class="published-event-table">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Description</th>
                                    <th>Devices Registered</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'db_connect.php';

                                $publishedEventsQuery = "
                                        SELECT e.*, GROUP_CONCAT(d.name SEPARATOR ', ') AS device_names
                                        FROM events e
                                        LEFT JOIN event_device ed ON e.event_id = ed.event_id
                                        LEFT JOIN devices d ON ed.device_id = d.device_id
                                        WHERE e.is_published = 1
                                        GROUP BY e.event_id
                                        ORDER BY e.event_date, e.event_time";
                                $publishedEventsResult = $conn->query($publishedEventsQuery);

                                if ($publishedEventsResult->num_rows > 0):
                                    while ($event = $publishedEventsResult->fetch_assoc()):
                                        ?>
                                        <tr onclick="showEditEventModal(<?php echo $event['event_id']; ?>)">
                                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                                            <td><?php echo htmlspecialchars($event['device_names']); ?></td>
                                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                                            <td><?php echo htmlspecialchars($event['event_time']); ?></td>
                                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                                        </tr>
                                        <?php
                                    endwhile;
                                else:
                                    echo "<tr><td colspan='6'>No published events found</td></tr>";
                                endif;
                                ?>
                            </tbody>

                            <tfoot>
                                <tr id="new-event-row">
                                    <td colspan="4">
                                        <a href="#" class="plus-icon" onclick="showAddEventModal()">+ Add New
                                            Event</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </section>
            <section id="Unpublished_Events" class="section-content">
                <div class="card">
                    <h3>Unpublished Events</h3>
                    <p>Events which are not available for the Visitors.</p>

                    <div class="published-event-table-container">
                        <table class="published-event-table">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Description</th>
                                    <th>Devices Registered</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'db_connect.php';
                                // Query for fetching unpublished events along with device names
                                $unpublishedEventsQuery = "
                                        SELECT e.*, GROUP_CONCAT(d.name SEPARATOR ', ') AS device_names
                                        FROM events e
                                        LEFT JOIN event_device ed ON e.event_id = ed.event_id
                                        LEFT JOIN devices d ON ed.device_id = d.device_id
                                        WHERE e.is_published = 0
                                        GROUP BY e.event_id
                                        ORDER BY e.event_date, e.event_time";
                                $unpublishedEventsResult = $conn->query($unpublishedEventsQuery);

                                if ($unpublishedEventsResult->num_rows > 0):
                                    while ($event = $unpublishedEventsResult->fetch_assoc()):
                                        ?>
                                        <tr onclick="showEditEventModal(<?php echo $event['event_id']; ?>)">
                                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                                            <td><?php echo htmlspecialchars($event['device_names']); ?></td>
                                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                                            <td><?php echo htmlspecialchars($event['event_time']); ?></td>
                                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                                        </tr>
                                        <?php
                                    endwhile;
                                else:
                                    echo "<tr><td colspan='6'>No unpublished events found</td></tr>";
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="Members" class="section-content">
                <div class="card">
                    <h3>Member</h3>
                    <p> Who has registered in the system.</p>

                    <div class="Member-table-container">
                        <table class="Member-table">
                            <thead>
                                <tr>
                                    <th>Member Name</th>
                                    <th>Member Email</th>
                                    <th>Member Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($memberDetails as $member): ?>
                                    <tr onclick="ShowuserEditModal(this, '<?php echo $member['id']; ?>')">
                                        <td><?php echo $member['fullname']; ?></td>
                                        <td><?php echo $member['email']; ?></td>
                                        <td><?php echo $member['password']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <tr id="new-Member-row">
                            <td colspan="3">
                                <a href="#" class="plus-icon" onclick="ShowuserAddModal()">+ Add New Member</a>
                            </td>
                        </tr>
                    </div>


                    <p style="font-weight: bold; font-size: 25px;"> ADMIN DATA:</p>

                    <table class="Administrator-table">
                        <thead>
                            <tr>
                                <th>Administrator Name</th>
                                <th>Administrator Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($adminDetails as $member): ?>
                                <tr>
                                    <td><?php echo $member['fullname']; ?></td>
                                    <td><?php echo $member['email']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
        </section>


        <div id="EventEditModel" class="modal">

            <span class="closeEvents" onclick="closeEditEventModal()">&times;</span>
            <div class="modal-content">
            <form id="Event-Edit-Form" method="post" action="edit_event.php">
                    <input type="hidden" id="eventID" name="eventID">
                    <h2>Edit Event</h2>

                    <label for="eventName">Event Name:</label>
                    <input type="text" id="eventName" name="eventName" required>

                    <label for="eventDescription">Event Description:</label>
                    <input type="text" id="eventDescription" name="eventDescription" required>

                    <label for="eventLocation">Event Location:</label>
                    <input type="text" id="eventLocation" name="eventLocation" required>

                    <label for="eventDate">Event Date:</label>
                    <input type="date" id="eventDate" name="eventDate" required>

                    <label for="eventTime">Event Time:</label>
                    <input type="time" id="eventTime" name="eventTime" required>

                    <label for="deviceIDName">Select Devices:</label>
                    <select id="deviceIDName" name="deviceIDName[]" multiple>
                        <?php
                            // Fetch all devices from the database
                            $deviceQuery = "SELECT device_id, name FROM devices";
                            $deviceResult = $conn->query($deviceQuery);

                            // Check if we have any devices
                            if ($deviceResult && $deviceResult->num_rows > 0):
                                // Output data of each row
                                while($device = $deviceResult->fetch_assoc()):
                        ?>
                                    <option value="<?php echo $device['device_id']; ?>">
                                        <?php echo htmlspecialchars($device['name']); ?>
                                    </option>
                        <?php 
                                endwhile;
                            else:
                                echo "<option>No devices found</option>";
                            endif;
                        ?>
                    </select>


                    <label for="EventStatus">Select Devices:</label>
                    <select id="EventStatus" name="EventStatus">
                        <option value="Visible">Visible</option>
                        <option value="Hidden">Hidden</option>
                    </select>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="submit" id="updateEventBtn" name="updateEventBtn" value="Update Event">
                        <input type="submit" id="deleteEventBtn" name="deleteEventBtn" value="Delete Event">
                    </div>
                </form>
            </div>
        </div>

        <div id="EventAddModel" class="modal">

            <div class="modal-content-Event">
                <span class="closeEvents" onclick="closeAddEventModal()">&times;</span>
                <form id="Event-Add-Form" method="post" action="add_event.php">
                    <input type="hidden" id="eventID" name="eventID">
                    <h2>Add Event:</h2>

                    <label for="eventName">Event Name:</label>
                    <input type="text" id="eventName" name="eventName" required>

                    <label for="eventDescription">Event Description:</label>
                    <input type="text" id="eventDescription" name="eventDescription" required>

                    <label for="eventLocation">Event Location:</label>
                    <input type="text" id="eventLocation" name="eventLocation" required>

                    <label for="eventDate">Event Date:</label>
                    <input type="date" id="eventDate" name="eventDate" required>

                    <label for="eventTime">Event Time:</label>
                    <input type="time" id="eventTime" name="eventTime" required>

                    <label for="deviceIDName">Select Devices:</label>
                    <select id="deviceIDName" name="deviceIDName[]" multiple>
                        <?php
                        // Fetch all devices from the database
                        $deviceQuery = "SELECT device_id, name FROM devices";
                        $deviceResult = $conn->query($deviceQuery);

                        // Check if we have any devices
                        if ($deviceResult && $deviceResult->num_rows > 0):
                            // Output data of each row
                            while ($device = $deviceResult->fetch_assoc()):
                                ?>
                                <option value="<?php echo $device['device_id']; ?>">
                                    <?php echo htmlspecialchars($device['name']); ?>
                                </option>
                                <?php
                            endwhile;
                        else:
                            echo "<option>No devices found</option>";
                        endif;
                        ?>
                    </select>

                    <label for="EventStatus">Select Devices:</label>
                    <select id="EventStatus" name="EventStatus">
                        <option value="Visible">Visible</option>
                        <option value="Hidden">Hidden</option>
                    </select>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="submit" id="addEventBtn" name="addEventBtn" value="Add Event">
                    </div>
                </form>
            </div>
        </div>

        <div id="userEditModal" class="Membermodal">
            <div class="modal-content-user">
                <span class="closeUserForm" onclick="CloseUEF()">&times;</span>
                <form id="userEditForm" method="post" action="Update_Member.php">

                    <input type="hidden" id="userID" name="userID">
                    <label for="userName">User Name:</label>
                    <input type="text" id="userName" name="userName" required>

                    <label for="userEmail">User Email:</label>
                    <input type="email" id="userEmail" name="userEmail" required>

                    <label for="userPassword">User Password:</label>
                    <input type="text" id="userPassword" name="userPassword" required>

                    <label for="userType">User Type:</label>
                    <select id="userType" name="userType" required>
                        <option value="Participant">Participant</option>
                        <option value="Organizer">Organizer</option>
                    </select>

                    <div class="button-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="submit" id="updateUserBtn" name="updateUserBtn" value="Update User">
                        <input type="submit" id="deleteUserBtn" name="deleteUserBtn" value="Delete User"
                            onclick="deleteUser()">


                    </div>
                </form>
            </div>
        </div>


        <div id="userAddModal" class="Membermodal">
            <div class="modal-content-user">
                <span class="closeUserForm" onclick="CloseAUF()">&times;</span>
                <form id="userEditForm" method="post" action="Add_member.php">
                    <input type="hidden" id="userID" name="userID">
                    <label for="userName">User Name:</label>
                    <input type="text" id="userName" name="userName" required>

                    <label for="userEmail">User Email:</label>
                    <input type="email" id="userEmail" name="userEmail" required>

                    <label for="userPassword">User Password:</label>
                    <input type="Text" id="userPassword" name="userPassword" required>

                    <label for="userType">User Type:</label>
                    <select id="userType" name="userType" required>
                        <option value="Participant">Participant</option>
                        <option value="Organizer">Organizer</option>
                    </select>

                    <div class="button-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="submit" id="addUserBtn" onclick="changeAction('add')" name="AddUserBtn"
                            value="Add User">
                    </div>
                </form>
            </div>
        </div>



    </div>

    <script>

        function deleteUser() {
            if (confirm("Are you sure you want to delete this user?")) {
                document.getElementById("userEditForm").submit();
            }
        }

        function showEditEventModal(eventid) {
            document.getElementById('eventID').value = eventid; 
            var EventEditForm = document.getElementById("EventEditModel");
            EventEditForm.style.display = "block";
        }

        function closeEditEventModal() {
            var EventFormClose = document.getElementById("EventEditModel");
            EventFormClose.style.display = "none";
        }

        function showAddEventModal() {
            var EventAddForm = document.getElementById("EventAddModel");
            EventAddForm.style.display = "block";
        }

        function closeAddEventModal() {
            var EventFormClose = document.getElementById("EventAddModel");
            EventFormClose.style.display = "none";
        }

        function ShowuserEditModal(row, userID) {
            var cells = row.cells;
            document.getElementById('userID').value = userID; // Pass userID to the hidden input field
            document.getElementById('userName').value = cells[0].textContent; // Assuming the first cell contains user name
            document.getElementById('userEmail').value = cells[1].textContent; // Assuming the second cell contains user email
            document.getElementById('userPassword').value = cells[2].textContent; // Assuming the third cell contains user password

            document.getElementById('userEditModal').style.display = 'block';
        }
        function CloseUEF() {
            var UserFormClose = document.getElementById("userEditModal");
            UserFormClose.style.display = "none";
        }

        function ShowuserAddModal() {
            var UserAddForm = document.getElementById("userAddModal");
            UserAddForm.style.display = "block";
        }

        function CloseAUF() {
            var UserFormClose = document.getElementById("userAddModal");
            UserFormClose.style.display = "none";
        }

        function updateform() {
            var Profile = document.getElementById("update-form");
            Profile.style.display = "block";
        }

        function closeProfileEdit() {
            var ProfileClose = document.getElementById("update-form");
            ProfileClose.style.display = "none";
        }

        document.addEventListener("DOMContentLoaded", function () {
            const navbarLinks = document.querySelectorAll('.navbar-menu a');
            const sections = document.querySelectorAll('.section');

            navbarLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    const targetSection = document.getElementById(targetId);
                    if (targetSection) {
                        sections.forEach(section => {
                            section.style.display = 'none';
                        });

                        targetSection.style.display = 'block';

                        window.scrollTo({
                            top: targetSection.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });




    </script>

</body>

</html>