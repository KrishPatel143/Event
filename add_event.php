<?php
include 'db_connect.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addEventBtn'])) {
    // Retrieve form data
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription'];
    $eventLocation = $_POST['eventLocation'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $deviceIDName = $_POST['deviceIDName']; // This is an array of selected device IDs
    $eventStatus = $_POST['EventStatus'] == "Visible" ? 1 : 0;

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO events (title, description, location, event_date, event_time, is_published) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $eventName, $eventDescription, $eventLocation, $eventDate, $eventTime, $eventStatus);
    
    // Execute and check success
    if ($stmt->execute()) {
        $newEventId = $conn->insert_id;
        // If you want to associate devices with the event right away
        $stmtDevice = $conn->prepare("INSERT INTO event_device (event_id, device_id) VALUES (?, ?)");
        foreach ($deviceIDName as $deviceId) {
            $stmtDevice->bind_param("ii", $newEventId, $deviceId);
            $stmtDevice->execute();
        }

        // Success response or redirect
        header('Location: Administrator.php?event_added_successfully');
    } else {
        // Error response or redirect
        header('Location: Administrator.php?error_adding_event');
    }
    $stmt->close();
    $conn->close();
}

exit();
?>
