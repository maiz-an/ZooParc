<?php
session_start();

// Check if the admin is logged in (you should have a separate check for admin users)
if (!isset($_SESSION['admin_id'])) {  // Replace with your admin session variable
    header("Location: admin_login.php");
    exit();
}

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

// Fetch all events
$sql = "SELECT event_id, event_name, event_date, event_time, ticket_price, availability, confirmed FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);

// Handle event confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_event'])) {
    $event_id = $_POST['event_id'];
    $sql = "UPDATE events SET confirmed = 1 WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $event_id);
    $stmt->execute();
    $stmt->close();

    // Refresh the page to reflect changes
    header("Location: admin_events.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
    <style>
        /* Table Styling */
        .events-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            max-width: 1000px;
        }
        .events-table th, .events-table td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }
        .events-table th {
            background-color: #f2f2f2;
            color: #333;
        }
        .events-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .events-table tr:hover {
            background-color: #f1f1f1;
        }
        .events-table td {
            color: #555;
        }
        .confirm-btn {
            padding: 8px 12px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .confirm-btn:hover {
            background-color: #4cae4c;
        }
        .confirm-btn[disabled] {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <h1>Manage Events</h1>
    <table class="events-table">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Confirmed</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['event_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_time']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['ticket_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['availability']); ?></td>
                        <td><?php echo $row['confirmed'] ? 'Yes' : 'No'; ?></td>
                        <td>
                            <?php if ($row['confirmed'] == 0): ?>
                                <form method="POST" action="admin_events.php">
                                    <input type="hidden" name="event_id" value="<?php echo $row['event_id']; ?>">
                                    <button type="submit" name="confirm_event" class="confirm-btn">Confirm</button>
                                </form>
                            <?php else: ?>
                                <button class="confirm-btn" disabled>Confirmed</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No events found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
