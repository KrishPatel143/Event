<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateDeviceBtn'])) {
    $deviceID = $_POST['deviceID'];
    $deviceName = $_POST['deviceName'];
    $deviceDescription = $_POST['deviceDescription'];
    $selectedEvents = $_POST['eventList'] ?? []; 
    
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("UPDATE devices SET name = ?, description = ? WHERE device_id = ?");
        $stmt->bind_param("ssi", $deviceName, $deviceDescription, $deviceID);
        $stmt->execute();
        
        $stmt = $conn->prepare("DELETE FROM Event_device WHERE device_id = ?");
        $stmt->bind_param("i", $deviceID);
        $stmt->execute();
        
        $stmt = $conn->prepare("INSERT INTO Event_device (event_id, device_id) VALUES (?, ?)");
        foreach ($selectedEvents as $eventId) {
            $stmt->bind_param("ii", $eventId, $deviceID);
            $stmt->execute();
        }

        $conn->commit();
        header('Location: Member.php?success=1');
    } catch (Exception $e) {
        $conn->rollback();
        header('Location: Member.php?error=' . $e->getMessage());
    }
    exit();
}
?>
