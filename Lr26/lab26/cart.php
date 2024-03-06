<?php
// Подключение к базе данных
$servername = "localhost"; // Имя сервера базы данных
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль пользователя базы данных
$dbname = "tv_store"; // Имя базы данных

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Обработка удаления товара из корзины
if(isset($_GET['action']) && $_GET['action'] == 'remove_from_cart' && isset($_GET['cart_item_id'])) {
    // Удаляем товар из корзины по его идентификатору в корзине
    $cart_item_id = $_GET['cart_item_id'];
    $sql_remove_from_cart = "DELETE FROM cart WHERE id = $cart_item_id";
    if ($conn->query($sql_remove_from_cart) === TRUE) {
        echo "Товар успешно удален из корзины.";
    } else {
        echo "Ошибка: " . $sql_remove_from_cart . "<br>" . $conn->error;
    }
}

// Обновление цены в корзине
$sql_update_cart_price = "UPDATE cart INNER JOIN tv ON cart.product_id = tv.id SET cart.price = tv.price";
if ($conn->query($sql_update_cart_price) === TRUE) {
    echo "Цены в корзине успешно обновлены.";
} else {
    echo "Ошибка при обновлении цен в корзине: " . $conn->error;
}

// Получение товаров из корзины с ценой
$sql_cart_items = "SELECT cart.id as cart_item_id, tv.*, cart.price as price FROM cart INNER JOIN tv ON cart.product_id = tv.id";
$result_cart_items = $conn->query($sql_cart_items);

// Отображение товаров в корзине
if ($result_cart_items->num_rows > 0) {
    echo "<h2>Корзина</h2>";
    while($row = $result_cart_items->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px; width: 300px; float: left;'>";
        echo "<p>Марка: " . $row["brand"]. "</p>";
        echo "<p>Модель: " . $row["model"]. "</p>";
        echo "<p>Размер: " . $row["size"]. "</p>";
        echo "<p>Разрешение: " . $row["resolution"]. "</p>";
        echo "<p>Цена: " . $row["price"]. "</p>";
        echo "<a href='?action=remove_from_cart&cart_item_id=" . $row['cart_item_id'] . "'>Удалить из корзины</a>";
        echo "</div>";
    }
    echo "<a href='checkout.php'>Перейти к оформлению заказа</a><br>";
} else {
    echo "Корзина пуста";
}

echo "<a href='script2.php'>Вернуться</a><br>";

// Закрытие соединения
$conn->close();
?>
