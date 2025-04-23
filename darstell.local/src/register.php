<?php

require_once __DIR__ . "/helpers.php";

// Получение данных
$nik = $_POST["nik"];
$login = $_POST["email"];
$password1 = $_POST["password1"];
$password2 = $_POST["password2"];

// Запись данных в БД
$connect = getDB();

// Проверка существования nik
$nikCheck = $connect->prepare("SELECT COUNT(*) FROM users WHERE nik = ?");
$nikCheck->bind_param("s", $nik);
$nikCheck->execute();
$nikCheck->bind_result($nikCount);
$nikCheck->fetch();
$nikCheck->close();

// Проверка существования email
$emailCheck = $connect->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$emailCheck->bind_param("s", $login);
$emailCheck->execute();
$emailCheck->bind_result($emailCount);
$emailCheck->fetch();
$emailCheck->close();

// Логика проверки и вставки
if ($nikCount > 0) {
    echo "Пользователь с таким ником уже существует.";
} elseif ($emailCount > 0) {
    echo "Пользователь с таким email уже существует.";
} else {
    if ($password1 === $password2) {
        // Подготовка SQL-запроса для вставки
        $stmt = $connect->prepare("INSERT INTO users (nik, email, password2) VALUES (?, ?, ?)");

        if ($stmt) {
            // Привязываем параметры
            $stmt->bind_param("sss", $nik, $login, $password2);
            
            // Выполняем запрос
            if ($stmt->execute()) {
                //"Регистрация прошла успешно"
                header(header: "Location: /login.html");
            } else {
                echo "Ошибка при регистрации: " . $stmt->error; // Выводим сообщение об ошибке
            }

            // Закрываем подготовленный запрос
            $stmt->close();
        } else {
            echo "Ошибка подготовки запроса: " . $connect->error; // Выводим сообщение об ошибке подготовки
        }
    } else {
        echo "Ваши пароли, которые вы ввели, отличаются";
    }
}
