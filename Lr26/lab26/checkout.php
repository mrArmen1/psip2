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

// Обработка отправки формы заказа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверка наличия данных в форме
    if (!empty($_POST['user_id'])) {
        // Получение данных из формы
        $user_id = $_POST['user_id'];

        // Проверка наличия данных в корзине
        $sql_cart = "SELECT * FROM cart";
        $result_cart = $conn->query($sql_cart);
        if ($result_cart->num_rows > 0) {
            // Инициализация переменной для общей стоимости заказа
            $total_price = 0;
            
            // Пройти по товарам в корзине и добавить их в заказ
            while($row = $result_cart->fetch_assoc()) {
                $tv_id = $row['product_id']; // ID телевизора из корзины
                $quantity = $row['quantity']; // Количество телевизоров из корзины
                $price = $row['price']; // Цена телевизора из корзины

                // Подсчет общей стоимости заказа
                $total_price += $quantity * $price;

                // Вставка данных в базу данных
                $sql_insert_order = "INSERT INTO orders (users_id, tv_id, quantity, total_price) VALUES ('$user_id', '$tv_id', '$quantity', '$price')";

                if ($conn->query($sql_insert_order) !== TRUE) {
                    echo "Ошибка при оформлении заказа: " . $conn->error;
                }
            }

            // Добавляем общую стоимость в заказ
            $sql_update_order = "UPDATE orders SET total_price = '$total_price' WHERE users_id = '$user_id'";
            if ($conn->query($sql_update_order) !== TRUE) {
                echo "Ошибка при обновлении общей стоимости заказа: " . $conn->error;
            } else {
                echo "Заказ успешно оформлен.";
            }
        } else {
            echo "Корзина пуста. Пожалуйста, добавьте товары в корзину перед оформлением заказа.";
        }
    } else {
        echo "Пожалуйста, введите ID пользователя.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
</head>
<body>
    <h2>Оформление заказа</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="user_id">ID пользователя:</label><br>
        <input type="text" id="user_id" name="user_id" required><br><br>
        
        <input type="submit" value="Оформить заказ">
    </form>
</body>
</html>

<?php
// Закрытие соединения
$conn->close();
?>
