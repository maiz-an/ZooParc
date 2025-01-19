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

// Fetch booking details
$sql = "SELECT b.booking_id, e.event_name, b.total_price 
        FROM bookings b
        JOIN events e ON b.event_id = e.event_id
        WHERE b.booking_id = ? AND b.status = 'confirmed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

// If the booking is not confirmed or doesn't exist, redirect
if (!$booking) {
    header("Location: member_view_events.php");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay for Booking</title>
    <link rel="stylesheet" href="style/st.css">
    <link rel="stylesheet" href="style/1st.css">
    <style>
        .payment-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .payment-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .payment-form input[type="text"],
        .payment-form input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .payment-form button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .payment-form button[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pay for Booking</h2>
        <p>You are about to pay for the following event:</p>
        <p><strong>Event:</strong> <?php echo htmlspecialchars($booking['event_name']); ?></p>
        <p><strong>Total Price:</strong> $<?php echo htmlspecialchars($booking['total_price']); ?></p>

        <form action="process_payment.php" method="POST" class="payment-form">
            <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
            <input type="hidden" name="total_price" value="<?php echo htmlspecialchars($booking['total_price']); ?>">

            <label for="card_name">Cardholder's Name</label>
            <input type="text" id="card_name" name="card_name" required>

            <label for="card_number">Card Number</label>
            <input type="text" id="card_number" name="card_number" maxlength="16" required>

            <label for="exp_date">Expiration Date (MM/YY)</label>
            <input type="text" id="exp_date" name="exp_date" maxlength="5" required>

            <label for="cvv">CVV</label>
            <input type="number" id="cvv" name="cvv" maxlength="3" required>

            <button type="submit">Confirm Payment</button>
        </form>
    </div>
</body>
</html>
