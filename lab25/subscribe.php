<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $subject = "Подписка на рассылку";
    $message = "Спасибо за подписку!";
    $headers = "From: your_email@example.com";

    if (mail($email, $subject, $message, $headers)) {
        echo "Email успешно отправлен на $email";
    } else {
        echo "Ошибка при отправке email";
    }
}
?>
