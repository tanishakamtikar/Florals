<?php
session_start();
include 'connection.php'; // make sure this file has your $conn = new mysqli(...)

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($admin = $result->fetch_assoc()) {
            if (password_verify($password, $admin['password'])) {
                // Login success
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: admin_dashboard.php");
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Admin not found.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            width: 400px;
            margin: 80px auto;
            padding: 25px;
            border: 1px solid #ed34a9;
            border-radius: 10px;
            background: #fff;
        }
        .login-container h2 {
            text-align: center;
        }
        .login-container input, .login-container button {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
        .login-portal input[type="text"],
        .login-portal [type="email"],
        .login-portal [type="password"] {
            width: 96%;
            padding: 10px;
            margin: 20px 0 16px 0;
            border: 1px solid #8e8e8e;
            border-radius: 5px;
            
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="admin_login.php" class="login-portal">
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login as Admin</button>
           <p>
        Don't have an account? <a href="admin_register.php">Register here</a>.
    </p>
    
    </form>
</div>

</body>
</html>

