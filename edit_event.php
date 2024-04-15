<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventID = $_POST['eventID'];
    $eventName = $_POST['eventName'];
    $eventDescription = $_POST['eventDescription'];
    $eventLocation = $_POST['eventLocation'];
    $eventDate = $_POST['eventDate'];
    $eventTime = $_POST['eventTime'];
    $deviceIDs = $_POST['deviceIDName'];
    $eventStatus = ($_POST['EventStatus'] === "Visible") ? 1 : 0;

    if (isset($_POST['updateEventBtn'])) {
        // Update event details
        $updateStmt = $conn->prepare("UPDATE events SET title = ?, description = ?, location = ?, event_date = ?, event_time = ?, is_published = ? WHERE event_id = ?");
        $updateStmt->bind_param("sssssii", $eventName, $eventDescription, $eventLocation, $eventDate, $eventTime, $eventStatus, $eventID);
        if ($updateStmt->execute()) {
            // Update devices associated with the event
            $conn->query("DELETE FROM event_device WHERE event_id = $eventID"); // Clear existing entries
            $insertDeviceStmt = $conn->prepare("INSERT INTO event_device (event_id, device_id) VALUES (?, ?)");
            foreach ($deviceIDs as $deviceID) {
                $insertDeviceStmt->bind_param("ii", $eventID, $deviceID);
                $insertDeviceStmt->execute();
            }
            $_SESSION['message'] = "Event updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update event.";
        }
        $updateStmt->close();
        $insertDeviceStmt->close();
    } 
    if (isset($_POST['deleteEventBtn'])) {
        // Delete associated devices first
        $deleteDeviceStmt = $conn->prepare("DELETE FROM event_device WHERE event_id = ?");
        $deleteDeviceStmt->bind_param("i", $eventID);
        if ($deleteDeviceStmt->execute()) {
            // Then delete the event itself
            $deleteStmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
            $deleteStmt->bind_param("i", $eventID);
            if ($deleteStmt->execute()) {
                $_SESSION['message'] = "Event deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete event.";
            }
            $deleteStmt->close();
        } else {
            $_SESSION['error'] = "Failed to delete event devices.";
        }
        $deleteDeviceStmt->close();
    }
    
    $conn->close();
    header("Location: Administrator.php"); // Redirect back to the admin page
    exit();
}
?>
