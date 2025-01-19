<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form data is provided
if (!isset($_POST['booking_id']) || !isset($_POST['total_price'])) {
    header("Location: member_view_events.php");
    exit();
}

$booking_id = $_POST['booking_id'];
$total_price = $_POST['total_price'];
$card_name = $_POST['card_name'];
$card_number = $_POST['card_number'];
$exp_date = $_POST['exp_date'];
$cvv = $_POST['cvv'];

// Simulate payment processing (In real-world, integrate with a payment gateway here)
$payment_success = true;  // Assume payment is successful

if ($payment_success) {
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

    // Insert payment record
    $sql = "INSERT INTO payments (booking_id, user_id, amount_paid, payment_date) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sid", $booking_id, $_SESSION['user_id'], $total_price);
    $stmt->execute();
    $stmt->close();

    // Update booking status to 'paid'
    $sql = "UPDATE bookings SET status = 'paid' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $booking_id);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    // Redirect to a success page
    header("Location: payment_success.php");
    exit();
} else {
    // Redirect to a failure page
    header("Location: payment_failure.php");
    exit();
}
?>
















<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $ticket_type = $_POST['ticket_type'];
    $quantity = $_POST['quantity'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Here you would add logic to process the payment through a payment gateway

    // After processing, redirect back to the tickets.php page with a success message
    header("Location: tickets.php?success=1");
    exit();
}

?>
