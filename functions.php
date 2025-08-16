<?php
function getProductsFromDatabase($conn) {
    $products = [];
    $result = $conn->query("SELECT id, name, price, image FROM products");
    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = [
            'name' => $row['name'],
            'price' => (float)$row['price'],
            'image' => $row['image'],
        ];
    }
    return $products;
}
?>
