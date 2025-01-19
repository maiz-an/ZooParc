<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zoo_website";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$sql = "SELECT user_id, name, username, email, phone_number, role FROM users WHERE 
        name LIKE '%$query%' OR 
        username LIKE '%$query%' OR 
        email LIKE '%$query%' OR 
        phone_number LIKE '%$query%' OR 
        role LIKE '%$query%'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
        echo "<td>" . htmlspecialchars($row['role']) . "</td>";
        echo "<td class='actions'>";
        echo "<a href='edit_user.php?user_id=" . $row['user_id'] . "'>Edit</a>";
        echo "<a href='manage_users.php?delete_user_id=" . $row['user_id'] . "' class='delete' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
       
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7' class='no-users'>No users found</td></tr>";
}

$conn->close();
?>
