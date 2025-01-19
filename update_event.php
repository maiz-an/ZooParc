<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "zoo_website";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Set the character set and collation to utf8mb4
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];
    $new_image = $_FILES['image']['tmp_name']; // New image file temp path
    $image_filename = $_FILES['image']['name']; // Get the original filename

    // Handle image upload if a new image is uploaded
    if (!empty($new_image)) {
        // Move the uploaded file to a specific directory
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image_filename);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Update the event including the image filename
            $sql = "UPDATE events SET event_name = ?, event_date = ?, event_time = ?, ticket_price = ?, availability = ?, event_image = ? WHERE event_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssdssi', $name, $date, $time, $price, $availability, $image_filename, $event_id);
        } else {
            echo "<script>alert('Error uploading the image.'); window.history.back();</script>";
            exit();
        }
    } else {
        // Update the event without changing the image filename
        $sql = "UPDATE events SET event_name = ?, event_date = ?, event_time = ?, ticket_price = ?, availability = ? WHERE event_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssdsi', $name, $date, $time, $price, $availability, $event_id);
    }

    if ($stmt->execute()) {
        $_SESSION['event_update_success'] = true;
        header("Location: manage_events.php");
        exit();
    } else {
        echo "<script>alert('Error updating event.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
