<?php
include 'db_connect.php';
session_start();

// Check if user is logged in and has the right to delete the device
if (isset($_GET['deviceID'])) {
    $deviceID = intval($_GET['deviceID']);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Optionally check if the device belongs to the user
        // You should have logic here to confirm that the device being deleted belongs to the logged-in user

        // First, delete any references in the event_device table
        $stmt = $conn->prepare("DELETE FROM event_device WHERE device_id = ?");
        $stmt->bind_param("i", $deviceID);
        $stmt->execute();

        // Next, delete the device itself
        $stmt = $conn->prepare("DELETE FROM devices WHERE device_id = ? ");
        $stmt->bind_param("i", $deviceID);
        $stmt->execute();

        // If the device was deleted, commit the transaction
        if ($stmt->affected_rows > 0) {
            $conn->commit();
            $successMsg = "Device deleted successfully.";
        } else {
            // If no device was deleted, it may not exist or may not belong to the user
            $conn->rollback();
            $errorMsg = "No device found, or you do not have permission to delete this device.";
        }
        
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        // If an error occurred, rollback the transaction
        $conn->rollback();
        $errorMsg = "Error deleting device: " . $e->getMessage();
    }

    $conn->close();

    // Redirect back to the dashboard with a message
    if (isset($successMsg)) {
        header('Location: Member.php?success=' . urlencode($successMsg));
    } else {
        header('Location: Member.php?error=' . urlencode($errorMsg));
    }
} else {
    // Redirect to login page or display an error
    header('Location: login.php');
}

exit();
?>
