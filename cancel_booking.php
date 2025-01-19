<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if booking_id is provided
if (!isset($_GET['booking_id'])) {
    header("Location: member_view_events.php");
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

// Delete the specific booking
$sql = "DELETE FROM bookings WHERE booking_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();

// Optionally, you can update the event's availability here to increase the number of available tickets

$conn->close();

// Redirect back to "My Events" page with a success message or directly
header("Location: member_view_events.php?cancellation_success=1");
exit();


echo "Deleting booking with ID: " . $booking_id;

?>
