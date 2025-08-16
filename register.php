<?php
// Start session and include database connection
session_start();
include 'connection.php';

// Replace with your actual database credentials
$host = "localhost";
$user = "root";
$password = "";
$database = "florals_shop";

// Connect to database
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    // Validate fields
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Email already registered.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
            } else {
                $errors[] = "Registration failed. Try again.";
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">

   
    
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php elseif (!empty($success)): ?>
            <div class="message success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="register-portal">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="enter your name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="enter your email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="enter your password" required>

            <label for="confirm">Confirm Password</label>
            <input type="password" name="confirm" id="confirm" placeholder="confirm your password" required>

            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login</a></p>
            
        </form>
    </div>
</body>
</html>

