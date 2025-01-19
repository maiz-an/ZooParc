<?php
// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "zoo_website";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

// Sanitize the input to prevent SQL injection
$query = $conn->real_escape_string($query);

$sql = "SELECT * FROM events WHERE event_name LIKE '%$query%' OR event_date LIKE '%$query%' OR event_time LIKE '%$query%' OR ticket_price LIKE '%$query%' OR availability LIKE '%$query%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td data-label="ID">' . htmlspecialchars($row['event_id'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td data-label="Name">' . htmlspecialchars($row['event_name'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td data-label="Date">' . htmlspecialchars($row['event_date'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td data-label="Time">' . htmlspecialchars($row['event_time'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td data-label="Ticket Price">' . htmlspecialchars($row['ticket_price'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td data-label="Availability">' . htmlspecialchars($row['availability'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td data-label="Image"><img src="' . htmlspecialchars($row['event_image'] ?? '', ENT_QUOTES, 'UTF-8') . '" alt="Event Image"></td>';
        echo '<td data-label="Actions">
                <a href="edit_event.php?event_id=' . htmlspecialchars($row['event_id'] ?? '', ENT_QUOTES, 'UTF-8') . '" class="action-btn">Edit</a>
                <a href="manage_events.php?delete_event=' . htmlspecialchars($row['event_id'] ?? '', ENT_QUOTES, 'UTF-8') . '" class="action-btn delete" onclick="return confirm(\'Are you sure you want to delete this event?\');">Delete</a>
              </td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="8">No events found.</td></tr>';
}

$conn->close();
?>
