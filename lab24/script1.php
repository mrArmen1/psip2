<?php
// Начало сессии
session_start();

// Установка значения переменной в сессии
$_SESSION['username'] = 'user123';

// Вывод сообщения о сохранении значения в сессии
echo "Значение 'username' сохранено в сессии.";

// Отображение текущей сессии
echo "<br>Текущая сессия:<br>";
foreach ($_SESSION as $key => $value) {
    echo "$key: $value<br>";
}

// Завершение сессии
session_destroy();
?>
