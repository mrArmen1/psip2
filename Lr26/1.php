<?php
// Подключение к базе данных
// $db = mysqli_connect('localhost', 'пользователь', 'пароль', 'название_базы_данных');

// Запрос на получение контента или новостей из базы данных
$query = "SELECT * FROM content_or_news";
$result = mysqli_query($db, $query);

// Отображение полученных данных
while ($row = mysqli_fetch_assoc($result)) {
    echo "<h2>" . $row['title'] . "</h2>";
    echo "<p>" . $row['content'] . "</p>";
}
?>
