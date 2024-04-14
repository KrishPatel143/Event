<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateDeviceBtn'])) {
    $deviceID = $_POST['deviceID'];
    $deviceName = $_POST['deviceName'];
    $deviceDescription = $_POST['deviceDescription'];
    $selectedEvents = $_POST['eventList'] ?? []; // This is an array of selected event IDs
    
    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update the device
        $stmt = $conn->prepare("UPDATE devices SET name = ?, description = ? WHERE device_id = ?");
        $stmt->bind_param("ssi", $deviceName, $deviceDescription, $deviceID);
        $stmt->execute();
        
        // Delete the current associations for this device
        $stmt = $conn->prepare("DELETE FROM Event_device WHERE device_id = ?");
        $stmt->bind_param("i", $deviceID);
        $stmt->execute();
        
        // Insert new associations in the Event_device table
        $stmt = $conn->prepare("INSERT INTO Event_device (event_id, device_id) VALUES (?, ?)");
        foreach ($selectedEvents as $eventId) {
            $stmt->bind_param("ii", $eventId, $deviceID);
            $stmt->execute();
        }

        // Commit transaction
        $conn->commit();
        // Redirect to Member with a success message
        header('Location: Member.php?success=1');
    } catch (Exception $e) {
        // An error occurred, roll back the transaction
        $conn->rollback();
        // Redirect to Member with an error message
        header('Location: Member.php?error=' . $e->getMessage());
    }
    exit();
}
?>
