<?php
session_start();

// Проверяем, если пользователь уже авторизован, перенаправляем его на страницу администратора
if(isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin.php");
    exit;
}

// Проверяем, была ли отправлена форма
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем, что введенный пароль соответствует ожидаемому
    if($_POST['password'] === 'admin123') {
        // Устанавливаем сессию администратора
        $_SESSION['admin'] = true;
        // Перенаправляем на страницу администратора
        header("Location: admin.php");
        exit;
    } else {
        // В случае неверного пароля выводим сообщение об ошибке
        $error = "Неверный пароль";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Авторизация</h2>
    <?php if(isset($error)) { echo "<p>$error</p>"; } ?>
    <form method="post" action="">
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password">
        <button type="submit">Войти</button>
    </form>
</body>
</html>
