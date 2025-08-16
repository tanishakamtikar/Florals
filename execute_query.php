<?php
require_once('connection.php');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "INSERT INTO products (name, price, image) VALUES
('Red Roses', 40.00, 'img/redroses1.png'),
('Spring Tulips', 30.00, 'img/tulips2.png'),
('White Lillies', 70.00, 'img/lillies3.png'),
('Purple Orchids', 60.00, 'img/purpleorchids4.png'),
('Yellow Sunflowers', 30.00, 'img/sunflowers5.png'),
('Pink Roses', 50.00, 'img/pinkroses6.png'),
('White Orchids', 70.00, 'img/whiteorchids7.png'),
('Pink Peony', 90.00, 'img/peony8.png'),
('Clematis', 80.00, 'img/clematis9.png'),
('Waxflowers', 30.00, 'img/waxflower.png'),
('Lilac Flowers', 50.00, 'img/lilac11.png'),
('Dahlias', 50.00, 'img/dahlia12.png'),
('Pink Sunflowers', 40.00, 'img/pinksunflower13.png'),
('Hydrangea', 200.00, 'img/hydrangea14.png'),
('Lotus', 200.00, 'img/lotus15.png'),
('Queen Annes Lace', 70.00, 'img/anneslace16.png'),
('Iris', 60.00, 'img/iris17.png'),
('Daffodils', 30.00, 'img/daffodil18.png'),
('Orange Poppy', 30.00, 'img/poppy19.png'),
('Daisy', 40.00, 'img/daisy20.png');
";

if (mysqli_multi_query($conn, $sql)) {
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    echo "All queries executed successfully.";
} else {
    echo "Error executing queries: " . mysqli_error($conn);
}

mysqli_close($conn);
?>