<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <?php
    session_start();
    include 'db_connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $sql = "SELECT id, email, password,role FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['email'] = $row['email'];

                if ($row['role'] ==  "Organizer") {
                    header("Location: Administrator.php");
                }
                else {
                    header("Location: Member.php");
                    # code...
                }

                exit();
            } else {
                echo "<p>Invalid password. Please try again.</p>";
            }
        } else {
            echo "<p>No user found with that email address. Please try again.</p>";
        }
    }
    ?>

    <div class="container">
        <div class="card">
            <div class="card-image">
                <h2 class="card-heading">
                    Login
                    <small>Access your account</small>
                </h2>
            </div>
            <form class="card-form" method="post" action="login.php">
                <div class="input">
                    <input type="text" class="input-field" name="email" required />
                    <label class="input-label">Email</label>
                </div>
                <div class="input">
                    <input type="password" class="input-field" name="password" required />
                    <label class="input-label">Password</label>
                </div>
                <div class="action">
                    <button type="submit" class="action-button">Login</button>
                </div>
            </form>
            <div class="card-info">
                <p>Don't have an account? <a href="signup.Php">Sign up</a></p>
            </div>
        </div>
    </div>
</body>

</html>
