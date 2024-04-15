<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "db_connect.php";
    
    if (isset($_POST["updateUserBtn"])) {
        $userID = $_POST["userID"];
        $userName = $_POST["userName"];
        $userEmail = $_POST["userEmail"];
        $userPassword = $_POST["userPassword"];
        $userType = $_POST["userType"];

        $sql = "UPDATE users SET fullname = '$userName', email = '$userEmail', password = '$userPassword', role = '$userType' WHERE id = $userID";

        if (mysqli_query($conn, $sql)) {
            header("Location: Administrator.php#Members");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    if (isset($_POST["deleteUserBtn"])) {
        $userID = $_POST["userID"];

        $sql_delete_event_device = "DELETE FROM event_device WHERE device_id IN (SELECT device_id FROM devices WHERE user_id = $userID)";
        $sql_delete_device = "DELETE FROM devices WHERE user_id = $userID";

        mysqli_begin_transaction($conn);

        if (mysqli_query($conn, $sql_delete_event_device) && mysqli_query($conn,$sql_delete_device )) {
            $sql_delete_user = "DELETE FROM users WHERE id = $userID";
            if (mysqli_query($conn, $sql_delete_user)) {
                mysqli_commit($conn);
                header("Location: Administrator.php#Members");
            } else {
                mysqli_rollback($conn);
                echo "Error: " . $sql_delete_user . "<br>" . mysqli_error($conn);
            }
        } else {
            mysqli_rollback($conn);
            echo "Error: " . $sql_delete_device . "<br>" . mysqli_error($conn);
            echo "Error: " . $sql_delete_event_device . "<br>" . mysqli_error($conn);
        }
    }
}
?>
