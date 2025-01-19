<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if booking_id and status are provided
if (!isset($_POST['booking_id']) || !isset($_POST['status'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$booking_id = $_POST['booking_id'];
$status = $_POST['status'];

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

// Update the booking status
$sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $status, $booking_id);
$stmt->execute();
$stmt->close();

$conn->close();

// Redirect back to the admin dashboard
header("Location: admin_dashboard.php?status_update_success=1");
exit();
?>
