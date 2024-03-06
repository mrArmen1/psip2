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

// Количество записей на странице
$records_per_page = 5;

// Определение текущей страницы
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// Вычисление начального смещения для запроса
$offset = ($page - 1) * $records_per_page;

// Поиск продукции в каталоге
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $search_condition = " WHERE brand LIKE '%$search%' OR model LIKE '%$search%'"; // Добавляем условие поиска
} else {
    $search_condition = ''; // Если поиск не выполнен, условие поиска пустое
}

// Сортировка данных
$sort_column = 'brand';
$sort_order = 'ASC';
if (isset($_GET['sort_column']) && in_array($_GET['sort_column'], ['brand', 'model', 'size', 'resolution', 'price'])) {
    $sort_column = $_GET['sort_column'];
}
if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['ASC', 'DESC'])) {
    $sort_order = $_GET['sort_order'];
}

// Фильтрация данных
$filter_condition = '';
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    switch ($filter) {
        case 'cheap':
            $filter_condition = " WHERE price < 500"; // Фильтр для дешевых телевизоров
            break;
        case 'medium':
            $filter_condition = " WHERE price BETWEEN 500 AND 1000"; // Фильтр для средних телевизоров
            break;
        case 'expensive':
            $filter_condition = " WHERE price > 1000"; // Фильтр для дорогих телевизоров
            break;
        
    }
}

// Обработка добавления товара в корзину
if(isset($_GET['action']) && $_GET['action'] == 'add_to_cart' && isset($_GET['product_id'])) {
    // Проверяем, существует ли товар с указанным идентификатором
    $product_id = $_GET['product_id'];
    $sql_check_product = "SELECT * FROM tv WHERE id = $product_id";
    $result_check_product = $conn->query($sql_check_product);
    if ($result_check_product->num_rows > 0) {
        // Товар найден, добавляем его в корзину
        // Предположим, что данные корзины будут храниться в таблице cart с полями: id, product_id, quantity
        $sql_add_to_cart = "INSERT INTO cart (product_id, quantity) VALUES ($product_id, 1)";
        if ($conn->query($sql_add_to_cart) === TRUE) {
            echo "Товар успешно добавлен в корзину.";
        } else {
            echo "Ошибка: " . $sql_add_to_cart . "<br>" . $conn->error;
        }
    } else {
        echo "Товар с указанным идентификатором не найден.";
    }
}

// Выполнение запроса SELECT для таблицы "tv" с использованием LIMIT для пагинации, условия поиска, сортировки и фильтрации
$sql = "SELECT * FROM tv$search_condition$filter_condition ORDER BY $sort_column $sort_order LIMIT $offset, $records_per_page";
$result = $conn->query($sql);

// Вывод данных каталога телевизоров
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px; width: 300px; float: left;'>";
        echo "<p>Марка: " . $row["brand"]. "</p>";
        echo "<p>Модель: " . $row["model"]. "</p>";
        echo "<p>Размер: " . $row["size"]. "</p>";
        echo "<p>Разрешение: " . $row["resolution"]. "</p>";
        echo "<p>Цена: " . $row["price"]. "</p>";
        echo "<a href='?action=add_to_cart&product_id=" . $row['id'] . "'>Добавить в корзину</a>";
        echo "</div>";
    }
} else {
    echo "0 результатов";
}

// Определение общего числа записей в таблице
$sql_total = "SELECT COUNT(*) AS total FROM tv$search_condition$filter_condition";
$result_total = $conn->query($sql_total);

if (!$result_total) {
    echo "Ошибка выполнения запроса: " . $conn->error;
} else {
    $row_total = $result_total->fetch_assoc();
    $total_records = $row_total['total'];
}

// Определение общего числа страниц
$total_pages = ceil($total_records / $records_per_page);

// Вывод ссылок на страницы
echo "<div style='clear: both;'>";
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i'>$i</a> ";
}
echo "</div>";

// Форма для поиска
echo "<form method='get'>";
echo "<input type='text' name='search' placeholder='Поиск по марке или модели'>";
echo "<input type='submit' value='Искать'>";
echo "</form>";

// Форма для сортировки
echo "<form method='get'>";
echo "<select name='sort_column'>";
echo "<option value='brand'>Марка</option>";
echo "<option value='model'>Модель</option>";
echo "<option value='size'>Размер</option>";
echo "<option value='resolution'>Разрешение</option>";
echo "<option value='price'>Цена</option>";
echo "</select>";
echo "<select name='sort_order'>";
echo "<option value='ASC'>По возрастанию</option>";
echo "<option value='DESC'>По убыванию</option>";
echo "</select>";
echo "<input type='submit' value='Сортировать'>";
echo "</form>";

// Форма для фильтрации
echo "<form method='get'>";
echo "<select name='filter'>";
echo "<option value=''>Все</option>";
echo "<option value='cheap'>Дешевые</option>";
echo "<option value='medium'>Средние</option>";
echo "<option value='expensive'>Дорогие</option>";
// Добавьте другие варианты фильтров здесь, если необходимо
echo "</select>";
echo "<input type='submit' value='Применить фильтр'>";
echo "</form>";

echo "<a href='cart.php'>Перейти в корзину</a>";

// Закрытие соединения
$conn->close();
?>
