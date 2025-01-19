<?php
$password = "Admin@1234"; // Replace with your desired admin password
$hashed_password = password_hash('Admin@1234', PASSWORD_DEFAULT);
echo $hashed_password;
?>
