<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ''; // Initialize the error variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['usernameOrEmail']); // Trim input to avoid extra spaces
    $password = $_POST['password'];

    // Prepare SQL query based on input type (email or username)
    if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT user_id, username, password, role FROM users WHERE email = ?";
    } else {
        $sql = "SELECT user_id, username, password, role FROM users WHERE username = ?";
    }

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $usernameOrEmail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $username, $hashed_password, $role);
            $stmt->fetch();

            // Verify the password, including the newly reset one
            if (password_verify($password, $hashed_password)) {
                // Password is correct, regenerate session ID for security
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Redirect based on user role
                if ($role === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: member_dashboard.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid username or password.";
            }
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again later.";
    }
}

// Check if there is a success or error message
if (isset($_SESSION['error'])) {
    echo "<script>
        alert('" . htmlspecialchars($_SESSION['error']) . "');
        window.location.href = 'login.html';
    </script>";
    unset($_SESSION['error']); // Clear the error after displaying it
}

if (isset($_SESSION['success_message'])) {
    echo "<script>
        alert('" . htmlspecialchars($_SESSION['success_message']) . "');
        window.location.href = 'login.html';
    </script>";
    unset($_SESSION['success_message']); // Clear the success message after displaying it
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/st.css">
    <link rel="stylesheet" href="style/1st.css">
    <title>Login</title>
    <style>
        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-form button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-form button[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .forgot-password {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #007bff;
        }
        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>

        <form action="login.php" method="POST">
            <label for="usernameOrEmail">Username or Email</label>
            <input type="text" id="usernameOrEmail" name="usernameOrEmail" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
