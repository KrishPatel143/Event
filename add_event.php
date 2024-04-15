<?php
include 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addEventBtn'])) {
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription'];
    $eventLocation = $_POST['eventLocation'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $deviceIDName = $_POST['deviceIDName']; 
    $eventStatus = $_POST['EventStatus'] == "Visible" ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO events (title, description, location, event_date, event_time, is_published) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $eventName, $eventDescription, $eventLocation, $eventDate, $eventTime, $eventStatus);
    
    if ($stmt->execute()) {
        $newEventId = $conn->insert_id;
        $stmtDevice = $conn->prepare("INSERT INTO event_device (event_id, device_id) VALUES (?, ?)");
        foreach ($deviceIDName as $deviceId) {
            $stmtDevice->bind_param("ii", $newEventId, $deviceId);
            $stmtDevice->execute();
        }

        header('Location: Administrator.php?event_added_successfully');
    } else {
        header('Location: Administrator.php?error_adding_event');
    }
    $stmt->close();
    $conn->close();
}

exit();
?>
