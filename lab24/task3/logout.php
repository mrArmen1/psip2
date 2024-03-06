<?php
session_start();

// Уничтожаем сессию администратора
unset($_SESSION['admin']);
session_destroy();

// Перенаправляем пользователя на страницу входа
header("Location: login.php");
exit;
?>
