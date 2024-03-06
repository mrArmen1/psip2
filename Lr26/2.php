<?php
// Запрос на получение категорий продукции
$query = "SELECT * FROM categories";
$result = mysqli_query($db, $query);

// Отображение категорий продукции
while ($row = mysqli_fetch_assoc($result)) {
    echo "<h2>" . $row['category_name'] . "</h2>";

    // Запрос на получение товаров или услуг из данной категории
    $products_query = "SELECT * FROM products WHERE category_id = " . $row['category_id'];
    $products_result = mysqli_query($db, $products_query);

    // Отображение товаров или услуг
    while ($product_row = mysqli_fetch_assoc($products_result)) {
        echo "<p>" . $product_row['product_name'] . ": " . $product_row['price'] . "</p>";
    }
}
?>
