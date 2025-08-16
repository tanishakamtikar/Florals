<?php
require_once('connection.php'); // Make sure this connects properly and provides $conn

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 1. New password
$password = '12345'; // Change this to your new password
$newHash = password_hash($password, PASSWORD_BCRYPT);

echo "Generated Hash: " . $newHash . "<br>";

// 2. Update the admin table with the new password hash
$sql = "UPDATE admins 
        SET password = ? 
        WHERE username IN ('admin', 'admin1', 'admin2')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $newHash);

if ($stmt->execute()) {
    echo "Admin passwords updated successfully.<br>";
} else {
    echo "Error updating passwords: " . $stmt->error . "<br>";
}

$stmt->close();
$conn->close();
?>
