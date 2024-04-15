<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once "db_connect.php";
    
    if (isset($_POST["AddUserBtn"])) {
        $userName = $_POST["userName"];
        $userEmail = $_POST["userEmail"];
        $userPassword = $_POST["userPassword"];
        $userType = $_POST["userType"];

        $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$userName', '$userEmail', '$userPassword', '$userType')";

        if (mysqli_query($conn, $sql)) {
            header("Location: Administrator.php#Members");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>
