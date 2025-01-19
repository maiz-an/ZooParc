<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoo_website";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];

    // Check if the email or username already exists
    $checkUser = "SELECT * FROM users WHERE email = ? OR username = ?";
    $stmt = mysqli_prepare($conn, $checkUser);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Email or username already exists, show an error message
        $_SESSION['error'] = "The email or username is already registered. Please try another one.";
        header("Location: registration.php");
    } else {
        // Password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Default role is 'member'
        $role = 'member';

        // Prepare SQL query to insert the user into the database (excluding user_id)
        $sql = "INSERT INTO users (name, username, email, password, phone_number, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssss', $name, $username, $email, $hashed_password, $phone_number, $role);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $_SESSION['success'] = "Your registration is successful!";
            header("Location: Login.html"); // Redirect to registration.php after successful registration
            exit(); // Ensure no further code is executed
        } else {
            $_SESSION['error'] = "Registration failed! Please try again.";
            header("Location: registration.php");
            exit(); // Ensure no further code is executed
        }
        
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
