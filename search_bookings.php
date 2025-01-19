<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "zoo_website";

$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$search_query = "";
if (isset($_GET['query'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['query']);
}

$sql = "SELECT b.booking_id, e.event_name, u.user_id, b.quantity, b.total_price, b.status, b.booking_date 
        FROM bookings b 
        JOIN events e ON b.event_id = e.event_id 
        JOIN users u ON b.user_id = u.user_id";

if (!empty($search_query)) {
    $sql .= " WHERE e.event_name LIKE '%$search_query%' 
            OR u.user_id LIKE '%$search_query%'
            OR b.status LIKE '%$search_query%'
            OR b.booking_id LIKE '%$search_query%'
            OR b.booking_date LIKE '%$search_query%'";
}

$result = mysqli_query($conn, $sql);
$booking_count = mysqli_num_rows($result);

if ($booking_count > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['booking_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        echo "<td>$" . htmlspecialchars($row['total_price']) . "</td>";
        echo "<td>" . htmlspecialchars($row['booking_date']) . "</td>";
        echo "<td>";
        echo "<form method='POST' action='manage_bookings.php' class='actions'>";
        echo "<input type='hidden' name='booking_id' value='" . htmlspecialchars($row['booking_id']) . "'>";
        echo "<select name='status' class='status-select'>";
        echo "<option value='pending'" . ($row['status'] === 'pending' ? ' selected' : '') . ">Pending</option>";
        echo "<option value='in_progress'" . ($row['status'] === 'in_progress' ? ' selected' : '') . ">In Progress</option>";
        echo "<option value='confirmed'" . ($row['status'] === 'confirmed' ? ' selected' : '') . ">Confirmed</option>";
        echo "</select>";
        echo "</td>";
        echo "<td class='actions'>";
        echo "<button type='submit' name='update_status' class='update-btn'>Update</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8' style='text-align: center;'>No bookings found matching your search criteria.</td></tr>";
}

mysqli_close($conn);
?>
