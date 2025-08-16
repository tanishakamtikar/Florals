<?php
session_start();
include 'connection.php';


// CSRF Token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Brute force config
define('MAX_ATTEMPTS', 3);
define('LOCKOUT_TIME', 60); // 60 seconds

// Initialize brute force tracking
if (!isset($_SESSION['login_attempts'])) $_SESSION['login_attempts'] = 0;
if (!isset($_SESSION['last_attempt_time'])) $_SESSION['last_attempt_time'] = 0;

// Helper: check if user can attempt login
function can_attempt_login() {
    if ($_SESSION['login_attempts'] >= 3) {
        $elapsed = time() - $_SESSION['last_attempt_time'];
        if ($elapsed > 60) {
            // Reset attempts after cooldown
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;
            return true;
        }
        return false;
    }
    return true;
}


// Helper: check CSRF
function check_csrf($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Handle login
$error = '';
$locked = false;
$remaining = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!check_csrf($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } elseif (!can_attempt_login()) {
        $locked = true;
        $remaining = LOCKOUT_TIME - (time() - $_SESSION['last_attempt_time']);
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = "Please enter both email and password.";
        } 
        else {
            // admin database check
            $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $name, $hashed);
                $stmt->fetch();

                if (password_verify($password, $hashed)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['login_attempts'] = 0;
                    $_SESSION['last_attempt_time'] = 0;

                    header("Location: admin_dashboard.php");
                    exit;
                }
            }

            $stmt->close();

            // regular user database check
            $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $name, $hashed);
                $stmt->fetch();

                if (password_verify($password, $hashed)) {
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['login_attempts'] = 0;
                    $_SESSION['last_attempt_time'] = 0;

                    header("Location: home.php");
                    exit;
                } else {
                    $_SESSION['login_attempts'] += 1;
                    $_SESSION['last_attempt_time'] = time();
                    $error = "Incorrect password.";
                }
            } else {
                $_SESSION['login_attempts'] += 1;
                $_SESSION['last_attempt_time'] = time();
                $error = "No user found with that email.";
            }

            $stmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
  
</head>
<body>

<div class="form-container">
  <h2>Login</h2>

  <?php if (!empty($error)): ?>
    <div class="message"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($locked): ?>
    <div class="message">
      🚫 Too many failed attempts. Try again in <span class="countdown" id="countdown"><?= $remaining ?></span> seconds.
    </div>
    <script>
      let remaining = <?= $remaining ?>;
      const countdownSpan = document.getElementById('countdown');
      const interval = setInterval(() => {
        remaining--;
        countdownSpan.textContent = remaining;
        if (remaining <= 0) {
          clearInterval(interval);
          location.reload(); // refresh page when unlocked
        }
      }, 1000);
    </script>
  <?php endif; ?>

  <?php if (!$locked): ?>
  <form method="POST" action="" class="login-portal">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>

    <p>Don't have an account? <a href="register.php">Register</a></p>
            

  </form>
  <?php endif; ?>
</div>

</body>
</html>



