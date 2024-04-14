<?php
include 'db_connect.php';
session_start();

// Check if user is logged in and has the right to delete the device
if ( isset($_GET['deviceID'])) {
    $deviceID = intval($_GET['deviceID']);

    // Optionally check if the device belongs to the user
    // This is important for preventing users from deleting devices that do not belong to them

    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM devices WHERE device_id = ? ");
    $stmt->bind_param("i", $deviceID);
    
    // Execute the statement
    if ($stmt->execute()) {
        // Check if any rows were actually deleted
        if ($stmt->affected_rows > 0) {
            $successMsg = "Device deleted successfully.";
        } else {
            $errorMsg = "No device found, or you do not have permission to delete this $deviceID device.";
        }
    } else {
        $errorMsg = "Error deleting device.";
    }

    $stmt->close();
    $conn->close();
    
    // Redirect back to the Member with a message
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
