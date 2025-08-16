<?php
session_start();
include 'connection.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch product for editing
$editMode = false;
$editProduct = [
    'id' => '',
    'name' => '',
    'price' => '',
    'image' => ''
];

if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM products WHERE id = $editId");
    if ($result->num_rows === 1) {
        $editProduct = $result->fetch_assoc();
        $editMode = true;
    }
}

// Handle product add/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $id = $_POST['product_id'] ?? '';

    $targetFilePath = '';
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imageName = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $imageName;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowedTypes)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath);
        } else {
            echo "<p style='color:red;'>Invalid image type.</p>";
            $targetFilePath = ''; // prevent updating with invalid image
        }
    }

    if (isset($_POST['add_product'])) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $targetFilePath);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['update_product']) && $id) {
        if ($targetFilePath) {
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sdsi", $name, $price, $targetFilePath, $id);
        } else {
            $stmt = $conn->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
            $stmt->bind_param("sdi", $name, $price, $id);
        }
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin_dashboard.php");
    exit();
}

// Fetch all products
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: rgb(243, 222, 230); }
        h1 { text-align: center; color: #b1415c; }
        h2 { color: #8e2f49; }
        .container { max-width: 1000px; margin: auto; }
        form, table { margin: 20px 0; }
        input, button { padding: 10px; margin: 5px 0; width: 100%; }
        button:hover { background: lightpink; transition: 0.4s; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid black; text-align: left; }
        .actions a { margin-right: 10px; color: red; }
        .actions .edit-link { color: blue; }
        .logout { float: right; }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Dashboard</h1>
    <a class="logout" href="logout.php">Logout</a>

    <h2><?= $editMode ? 'Modify Product' : 'Add New Product' ?></h2>
    <form method="POST" action="admin_dashboard.php" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($editProduct['id']) ?>">
        <input type="text" name="name" placeholder="Product Name" value="<?= htmlspecialchars($editProduct['name']) ?>" required>
        <input type="number" step="0.01" name="price" placeholder="Price (€)" value="<?= htmlspecialchars($editProduct['price']) ?>" required>
        <input type="file" name="image" accept="image/*" <?= $editMode ? '' : 'required' ?>>
        <?php if ($editMode && $editProduct['image']): ?>
            <p>Current Image: <img src="<?= htmlspecialchars($editProduct['image']) ?>" alt="" width="50"></p>
        <?php endif; ?>
        <button type="submit" name="<?= $editMode ? 'update_product' : 'add_product' ?>">
            <?= $editMode ? 'Update Product' : 'Add Product' ?>
        </button>
    </form>

    <h2>Product List</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Price (€)</th><th>Image</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="" width="50"></td>
                    <td class="actions">
                        <a class="edit-link" href="?edit=<?= $row['id'] ?>">Modify</a>
                        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>




