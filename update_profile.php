<?php
session_start();
include 'db_connect.php'; 

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $password = $_POST['password'];

        $sql = "UPDATE users SET fullname='$name', password='$password' WHERE id=$userId";

        if ($conn->query($sql) === TRUE) {
            header('Location: Member.php?success=1');
        } else {
            header('Location: Member.php?failed=1');
        }
    }

    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
