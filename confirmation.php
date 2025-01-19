<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check for the success flag in the URL
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <script type="text/javascript">
        // JavaScript to show success message and redirect to events page
        function showSuccessMessageAndRedirect() {
            <?php if ($success): ?>
                alert("Thank you! Your booking was successful.");
                window.location.href = "view_events_member.php"; // Redirect to the events page
            <?php else: ?>
                window.location.href = "view_events_member.php"; // Redirect to the events page if there's no success flag
            <?php endif; ?>
        }

        // Call the function when the page loads
        window.onload = showSuccessMessageAndRedirect;
    </script>
</head>
<body>
    <!-- Optionally, you can add a loading message here -->
</body>
</html>
