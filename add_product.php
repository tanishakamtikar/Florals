<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $image_path = '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "img/";
        $file_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . '_' . $file_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $image_path);
        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Failed to add product.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link rel="stylesheet" href="style.css">
  <style>
  
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="form-container">
  <h2>Add New Product</h2>

  <?php if ($success) echo "<p class='success'>$success</p>"; ?>
  <?php if ($error) echo "<p class='error'>$error</p>"; ?>

  <form action="add_product.php" method="POST" enctype="multipart/form-data">
    <label>Product Name:</label>
    <input type="text" name="name" required>

    <label>Price (€):</label>
    <input type="number" step="0.01" name="price" required>

    <label>Upload Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit">Add Product</button>
  </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
