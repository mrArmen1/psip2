<?php
session_start();

// Проверяем, если пользователь не авторизован, перенаправляем его на страницу входа
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

// Если пользователь авторизован, выводим страницу администратора
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h2>Панель администратора</h2>
    <p>Вы авторизованы как администратор.</p>
    <a href="logout.php">Выйти</a>
</body>
</html>
