<?php
include 'db_connect.php';

session_start();

    $userId = $_SESSION['user_id'] ?? 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // At the beginning of your PHP script, before any output is sent to the browser
    $deviceName = $conn->real_escape_string($_POST['deviceName']);
    $deviceDescription = $conn->real_escape_string($_POST['deviceDescription']);
    $selectedEvents = $_POST['eventList']; // This is an array of selected event IDs
    

    // Start transaction
    $conn->begin_transaction();

    // Insert the new device
    $stmt = $conn->prepare("INSERT INTO devices (name, description,user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $deviceName, $deviceDescription, $userId);
    $stmt->execute();
    $newDeviceId = $conn->insert_id;

    // Insert the associations in the Event_device table
    $stmt = $conn->prepare("INSERT INTO Event_device (event_id, device_id) VALUES (?, ?)");
    foreach ($selectedEvents as $eventId) {
        $stmt->bind_param("ii", $eventId, $newDeviceId);
        $stmt->execute();
    }

    // If we reach this point without errors, commit the transaction
    $conn->commit();

    // Redirect or send back a success response
    header('Location: dashboard.php?success=1');
    exit();
}

// If the script is accessed without a POST request, redirect to the dashboard
header('Location: dashboard.php');
exit();
?>
