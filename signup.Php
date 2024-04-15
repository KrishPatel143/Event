<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="signup.css"> 
</head>

<body>
    <?php
    include 'db_connect.php'; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $conn->real_escape_string($_POST['fullname']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        // Determine role based on email domain
        $emailDomain = substr(strrchr($email, "@"), 1);
        $role = ($emailDomain === 'organizer.com') ? 'Organizer' : 'Participant';

        $sql = "INSERT INTO users (fullname, email, password, role) VALUES ('$fullname', '$email', '$password', '$role')";

        if ($conn->query($sql) === TRUE) {
            header("Location: login.php");
            exit();
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
    ?>

    <div class="container">
        <div class="card">
            <div class="card-image">
                <h2 class="card-heading">
                    Sign Up
                    <small>Create your account</small>
                </h2>
            </div>
            <form class="card-form" method="post" action="signup.php">
                <div class="input">
                    <input type="text" class="input-field" name="fullname" required />
                    <label class="input-label">Full name</label>
                </div>
                <div class="input">
                    <input type="email" class="input-field" name="email" required />
                    <label class="input-label">Email</label>
                </div>
                <div class="input">
                    <input type="password" class="input-field" name="password" required />
                    <label class="input-label">Password</label>
                </div>
                <div class="action">
                    <button class="action-button">Sign Up</button>
                </div>
            </form>
            <div class="card-info">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</body>

</html>
