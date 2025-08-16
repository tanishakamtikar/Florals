<?php
session_start();
include 'connection.php'; // $conn
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
$totalItems = 0;
$totalAmount = 0.00;
$products = [];

// Fetch product info
if (!empty($cart)) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $result = $conn->query("SELECT id, name, price FROM products WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row;
    }

    foreach ($cart as $productId => $qty) {
        if (isset($products[$productId])) {
            $totalItems += $qty;
            $totalAmount += $products[$productId]['price'] * $qty;
        }
    }
}

$error = '';

// --- Handle Form Submission ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $payment = $_POST['payment'] ?? '';
    $user_id = $_SESSION['user_id'];

    if (empty($customer_name) || empty($email) || empty($address) || empty($phone) || empty($payment)) {
        $error = "Please fill in all required fields.";
    } elseif ($totalItems === 0) {
        $error = "Your cart is empty.";
    } else {
        // Generate OTP
        $otp = rand(100000, 999999);
        $otp_hash = password_hash($otp, PASSWORD_DEFAULT);
        $expires_at = date("Y-m-d H:i:s", time() + 180); // 3 minutes expiry

        // Insert OTP into database
        $stmt = $conn->prepare("INSERT INTO otp_verification (user_id, otp_hash, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $otp_hash, $expires_at);
        $stmt->execute();
        $stmt->close();

        // Store order info temporarily in session for after OTP verification
        $_SESSION['order_info'] = [
            'user_id' => $user_id,
            'customer_name' => $customer_name,
            'email' => $email,
            'address' => $address,
            'phone' => $phone,
            'payment' => $payment,
            'cart' => $cart,
            'total_items' => $totalItems,
            'total_price' => $totalAmount
        ];

        // --- Send OTP via PHPMailer ---
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = ''; // your email
            $mail->Password = ''; // app password 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('florals.order@gmail.com', 'Floral Shop');
            $mail->addAddress($email, $customer_name);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Floral Shop Order';
            $mail->Body = "Hello $customer_name,<br><br>Thank you for shopping with us!<br>Your OTP for confirming your order is <b>$otp</b>.";

            $mail->send();

            header("Location: verify_otp.php");
            exit();
        } catch (Exception $e) {
            $error = "Failed to send OTP. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Floral Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="order-form">
    <h2>Checkout</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="order.php" class="login-portal">
        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Address</label>
        <textarea name="address" required></textarea>

        <label>Phone</label>
        <input type="text" name="phone" required>

        <label>Payment Method</label>
        <select name="payment" required>
            <option value="cod">Cash on Delivery</option>
            <option value="card">Credit/Debit Card</option>
            <option value="paypal">PayPal</option>
        </select>

        <p><strong>Total Items:</strong> <?= $totalItems ?></p>
        <p><strong>Total Amount:</strong> €<?= number_format($totalAmount, 2) ?></p>

        <button type="submit">Place Order</button>
    </form>
</div>

</body>
</html>


