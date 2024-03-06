<?php
session_start();

// Подключение к базе данных
$mysqli = new mysqli("localhost", "root", "", "bdcursach");

// Проверка подключения
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

// Получение данных о пользователе из базы данных
$login = $_SESSION['login'];
$stmt = $mysqli->prepare("SELECT * FROM student WHERE login = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

// Проверка наличия данных о пользователе
if ($result->num_rows === 0) {
    echo "Ошибка: Пользователь не найден";
    exit;
}

$user = $result->fetch_assoc();

// Получение списка курсов
$courses_stmt = $mysqli->prepare("SELECT * FROM curse");
$courses_stmt->execute();
$courses_result = $courses_stmt->get_result();

// Функция удаления аккаунта
function deleteAccount() {
    global $mysqli, $login;
    $delete_stmt = $mysqli->prepare("DELETE FROM student WHERE login = ?");
    $delete_stmt->bind_param("s", $login);
    $delete_stmt->execute();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Функция подписки на курс
function subscribeCourse($course_id) {
    global $mysqli, $user;
    $subscribed_courses = $user['subscribed_courses'] ? explode(",", $user['subscribed_courses']) : [];
    if (!in_array($course_id, $subscribed_courses)) {
        $subscribed_courses[] = $course_id;
        $subscribed_courses_str = implode(",", $subscribed_courses);
        $update_stmt = $mysqli->prepare("UPDATE student SET subscribed_courses = ? WHERE login = ?");
        $update_stmt->bind_param("ss", $subscribed_courses_str, $user['login']);
        $update_stmt->execute();
        return true;
    } else {
        return false; // Пользователь уже подписан на этот курс
    }
}

// Проверка нажатия кнопки "Удалить аккаунт"
if (isset($_POST['delete_account'])) {
    deleteAccount();
}

// Проверка нажатия кнопки "Подписаться на курс"
if (isset($_POST['subscribe_course'])) {
    $course_id = $_POST['course_id'];
    if (subscribeCourse($course_id)) {
        // Пользователь успешно подписан на курс
        header('Location: profile.php'); // Перенаправление на страницу профиля
        exit;
    } else {
        // Пользователь уже подписан на этот курс
        echo "<script>alert('Вы уже подписаны на этот курс');</script>";
    }
}

// Проверка нажатия кнопки "Посмотреть курс" для подписанных курсов
if (isset($_POST['view_subscribed_course'])) {
    $course_id = $_POST['course_id'];
    header("Location: course.php?course_id=$course_id"); // Перенаправление на страницу курса
    exit;
}

// Закрытие подключения
$stmt->close();
$courses_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" type="text/css" href="profile_styles.css">

</head>
<body>
    <h1>Профиль пользователя</h1>
    <div>
        <p>Имя: <?php echo $user['Name']; ?></p>
        <p>Фамилия: <?php echo $user['Surname']; ?></p>
        <form method="post">
            <button type="submit" name="delete_account">Удалить аккаунт</button>
        </form>
    </div>
    <h2>Доступные курсы</h2>
    <ul>
        <?php while ($course = $courses_result->fetch_assoc()): ?>
            <li>
                <p><?php echo $course['description']; ?></p>
                <p>Цена: <?php echo $course['cusre_price']; ?></p>
                <form method="post">
                    <input type="hidden" name="course_id" value="<?php echo $course['curse_id']; ?>">
                    <button type="submit" name="subscribe_course">Подписаться на курс</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Подписанные курсы</h2>
    <ul>
        <?php 
        // Получение подписанных курсов пользователя
        $subscribed_courses = explode(",", $user['subscribed_courses']);
        if (empty($subscribed_courses)) {
            echo "<p>У вас пока нет подписанных курсов</p>";
        } else {
            // Формируем строку с плейсхолдерами для IN оператора
            $placeholders = implode(',', array_fill(0, count($subscribed_courses), '?'));
            // Подготавливаем запрос с динамическим количеством параметров
            $subscribed_courses_stmt = $mysqli->prepare("SELECT * FROM curse WHERE curse_id IN ($placeholders)");
            if ($subscribed_courses_stmt) {
                // Привязываем параметры
                $types = str_repeat('i', count($subscribed_courses)); // 'i' означает integer
                $subscribed_courses_stmt->bind_param($types, ...$subscribed_courses);
                // Выполняем запрос
                $subscribed_courses_stmt->execute();
                $subscribed_courses_result = $subscribed_courses_stmt->get_result();
                while ($course = $subscribed_courses_result->fetch_assoc()): 
        ?>
            <li>
                <p><?php echo $course['description']; ?></p>
                <p>Цена: <?php echo $course['cusre_price']; ?></p>
                <form method="post">
                    <input type="hidden" name="course_id" value="<?php echo $course['curse_id']; ?>">
                    <button type="submit" name="view_subscribed_course">Посмотреть курс</button>
                </form>
            </li>
        <?php endwhile; ?>
        <?php } else { ?>
            <p>Ошибка при выполнении запроса к базе данных</p>
        <?php } ?>
        <?php } ?>
    </ul>
</body>
</html>
