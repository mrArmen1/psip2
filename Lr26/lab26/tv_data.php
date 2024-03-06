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

// Выполнение запроса SELECT для таблицы "tv"
$sql_tv = "SELECT * FROM tv";
$result_tv = $conn->query($sql_tv);

// Вывод данных таблицы "tv" на веб-страницу
if ($result_tv->num_rows > 0) {
    echo "<h2>Таблица Телевизоры</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Марка</th><th>Модель</th><th>Размер</th><th>Разрешение</th><th>Цена</th></tr>";
    while($row = $result_tv->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"]. "</td>";
        echo "<td>" . $row["brand"]. "</td>";
        echo "<td>" . $row["model"]. "</td>";
        echo "<td>" . $row["size"]. "</td>";
        echo "<td>" . $row["resolution"]. "</td>";
        echo "<td>" . $row["price"]. "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 результатов";
}

// Выполнение запроса SELECT для таблицы "users"
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

// Вывод данных таблицы "users" на веб-страницу
if ($result_users->num_rows > 0) {
    echo "<h2>Таблица Пользователи</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Имя</th><th>Email</th></tr>";
    while($row = $result_users->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"]. "</td>";
        echo "<td>" . $row["username"]. "</td>";
        echo "<td>" . $row["email"]. "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 результатов";
}

// Выполнение запроса SELECT для таблицы "orders"
$sql_orders = "SELECT * FROM orders";
$result_orders = $conn->query($sql_orders);

// Вывод данных таблицы "orders" на веб-страницу
if ($result_orders->num_rows > 0) {
    echo "<h2>Таблица Заказы</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>ID пользователя</th><th>ID телевизора</th><th>Количество</th><th>Общая цена</th></tr>";
    while($row = $result_orders->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"]. "</td>";
        echo "<td>" . ($row["users_id"] ?? 'Нет данных') . "</td>"; // Добавлена проверка наличия данных
        echo "<td>" . ($row["tv_id"] ?? 'Нет данных') . "</td>"; // Добавлена проверка наличия данных
        echo "<td>" . $row["quantity"]. "</td>";
        echo "<td>" . $row["total_price"]. "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 результатов";
}

// Закрытие соединения
$conn->close();
?>
