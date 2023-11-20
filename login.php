<?php
session_start();
require_once 'config.php';
require_once 'lib/index.php';
require 'templates/head.php';

$dbh = connectDB();
   
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Grab values from the POST array
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];

        // Retrieve user data from the database
        $stmt = $dbh->prepare("SELECT user_id, username, email, password FROM users WHERE username = :username");
        $stmt->bindParam(':username', $inputUsername);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($user && password_verify($inputPassword, $user['password'])) {
            // Password is correct, set user information in the session
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['user_id'];

            // Redirect to a dashboard or another page after successful login
            header("Location: index.php");
            exit();
        } else {
            // Invalid username or password
            $loginError = "Invalid username or password";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
   

        h2 {
            text-align: center;
            color: #363636;
        }

        form {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #363636;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #62aaa7;
            color: #f6f6f6;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #508c89;
        }

        .text {
            text-align: center;
            margin-top: 16px;
            color: #363636;
        }

        a {
            color: #62aaa7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p.error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>User Login</h2>

    <?php
    if (isset($loginError)) {
        echo '<p class="error">' . $loginError . '</p>';
    }
    ?>

    <!-- Display the login form -->
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>

    <p class="text">Don't have an account? <a href="register.php">Register here</a>.</p>

</body>
</html>




<?php
require 'templates/footer.php';
?>


