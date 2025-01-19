<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if booking_id is provided
if (!isset($_GET['booking_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$booking_id = $_GET['booking_id'];

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the booking status to confirmed
$sql = "UPDATE bookings SET status = 'confirmed' WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $booking_id);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirect back to the admin dashboard or a specific page
header("Location: admin_dashboard.php?confirmation_success=1");
exit();
?>
