<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Подключение к базе данных
    $mysqli = new mysqli("localhost", "root", "", "bdcursach");

    // Проверка подключения
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Получение данных из формы
    $description = $_POST["description"];
    $price = $_POST["cusre_price"];
    $videolessons = $_POST["videolessons"];

    // SQL запрос для добавления записи в таблицу curse
    $sql = "INSERT INTO curse (description, cusre_price, videolessons) VALUES (?, ?, ?)";

    // Подготовка запроса
    $stmt = $mysqli->prepare($sql);

    // Привязываем параметры
    $stmt->bind_param("sii", $description, $price, $videolessons);

    // Выполняем запрос
    if ($stmt->execute()) {
        // Закрываем запрос и подключение
        $stmt->close();
        $mysqli->close();
        
        // Перенаправляем на страницу профиля препода
        echo '<script>window.location.href = "teacher_profile.php";</script>';
        exit();
    } else {
        // Выводим ошибку во всплывающем окне
        echo '<script>alert("Ошибка добавления курса: ' . $stmt->error . '");</script>';
    }

    // Закрываем запрос и подключение
    $stmt->close();
    $mysqli->close();
}
?>
