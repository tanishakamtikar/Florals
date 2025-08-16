<?php
session_start();
include 'connection.php'; // Make sure this file sets up $conn (MySQLi connection)

$error = '';
$success = '';

// Handle admin registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password || !$confirm) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if admin already exists
        $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "An admin with this email already exists.";
        } else {
            // Hash password and insert new admin
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hashed_password);

            if ($insert->execute()) {
                $success = "✅ Admin registered successfully. You can now <a href='admin_login.php'>login here</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" href="style.css"> <!-- Optional CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f9f9f9;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        input, button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }
        .error { color: red; }
        .success { color: green; }
        h2 {
            text-align: center;
        }
        .register-portal input[type="text"],
        .register-portal [type="email"],
        .register-portal [type="password"] {
            width: 96%;
            padding: 10px;
            margin: 20px 0 16px 0;
            border: 1px solid #8e8e8e;
            border-radius: 5px;
            
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Admin Registration</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST" action="admin_register.php" class="register-portal">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>

    <p>
        Already have an account? <a href="admin_login.php">Login here</a>.
    </p>
    
</div>

</body>
</html>
