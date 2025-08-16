<?php
session_start();
include 'connection.php'; // $conn
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['order_info'])) {
    header("Location: order.php");
    exit();
}

$error = '';
$success = false;
$info = '';

$order = $_SESSION['order_info'];
$email = $order['email'];
$customer_name = $order['customer_name'];

// --- Handle OTP verification ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_otp'])) {
    $input_otp = $_POST['otp'] ?? '';

    if (empty($input_otp)) {
        $error = "Please enter the OTP.";
    } elseif (!isset($_SESSION['otp_expiry']) || time() > $_SESSION['otp_expiry']) {
        $error = "OTP has expired. Please request a new one.";
        unset($_SESSION['otp'], $_SESSION['otp_expiry']);
    } elseif ($input_otp != $_SESSION['otp']) {
        $error = "Invalid OTP. Please check and try again.";
    } else {
        // OTP is correct: insert order and items
        try {
            $conn->begin_transaction();

            // Insert order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, email, address, phone, payment_method, total_items, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param(
                "isssssid",
                $order['user_id'],
                $order['customer_name'],
                $order['email'],
                $order['address'],
                $order['phone'],
                $order['payment'],
                $order['total_items'],
                $order['total_price']
            );
            $stmt->execute();
            $orderId = $stmt->insert_id;
            $stmt->close();

            // Insert order items
            $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
            foreach ($order['cart'] as $productId => $qty) {
                $product_name = $_SESSION['cart_products'][$productId]['name'] ?? '';
                $product_price = $_SESSION['cart_products'][$productId]['price'] ?? 0;
                $itemStmt->bind_param(
                    "iisid",
                    $orderId,
                    $productId,
                    $product_name,
                    $qty,
                    $product_price
                );
                $itemStmt->execute();
            }
            $itemStmt->close();

            $conn->commit();

            // Clear session
            unset($_SESSION['cart'], $_SESSION['otp'], $_SESSION['otp_expiry'], $_SESSION['order_info'], $_SESSION['cart_products']);

            $success = true;

        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            $error = "Failed to place order. Please try again.";
        }
    }
}

// --- Handle Resend OTP ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resend_otp'])) {

    // Generate new OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = time() + 180; // 3 minutes

    // Optionally save hashed OTP to DB if you have otp_verification table
    /*
    $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE otp_verification SET otp_hash = ?, created_at = NOW() WHERE email = ?");
    $stmt->bind_param("ss", $hashedOtp, $email);
    $stmt->execute();
    $stmt->close();
    */

    // Send OTP via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'florals.order@gmail.com'; // your email
        $mail->Password = 'your_app_password'; // app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('florals.order@gmail.com', 'Florals');
        $mail->addAddress($email, $customer_name);

        $mail->isHTML(true);
        $mail->Subject = 'Your Florals Order OTP (Resent)';
        $mail->Body = "Hello $customer_name,<br>Your new OTP for confirming your order is <b>$otp</b>!";

        $mail->send();
        $info = "A new OTP has been sent to your email.";

    } catch (Exception $e) {
        $error = "Failed to resend OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP - Floral Shop</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #fce4ec; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .otp-box { background: #fff; padding: 30px; border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); text-align: center; width: 400px; }
        input { width: 100%; padding: 12px; margin: 12px 0; border-radius: 10px; border: 1px solid #ddd; }
        button { padding: 12px 20px; border-radius: 25px; border: none; background: #de7b9fff; color: white; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        button:hover { background: #b94b73ff; }
        .error { color: red; margin: 10px 0; }
        .info { color: green; margin: 10px 0; }
        .confirmation { background: #f1f8e9; padding: 20px; border-radius: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="otp-box">
    <?php if ($success): ?>
        <div class="confirmation">
            <h2>🌸 Your order is confirmed!</h2>
            <p>Thank you for shopping with us.</p>
            <a href="products.php"><button>Continue Shopping</button></a>
        </div>
    <?php else: ?>
        <h2>Enter OTP</h2>

        <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
        <?php if ($info): ?><p class="info"><?= htmlspecialchars($info) ?></p><?php endif; ?>

        <form method="POST">
            <input type="text" name="otp" maxlength="6" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>

        <form method="POST">
            <button type="submit" name="resend_otp">Resend OTP</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>



