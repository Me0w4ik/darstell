<?php

require_once __DIR__ . "/helpers.php";

session_start();

$idUser = $_SESSION['user']['id'];

$password = $_POST['password'];
$description = $_POST['description'];

$connect = getDB();

$badWords = ['пизда', 'хуй', 'сука', 'ебать', 'гандон', 'манда', 'блядь', 'хрен', 'пидор', 'ass', 'bitch', 'boomboom', 'cосk', 'crack', 'cunt', 'dick', 'pussy', 'pidor', 'huisos', 'манда', 'член', '4mo', 'idinahui', 'blya'];

// Функция для проверки наличия запрещенных слов
function containsBadWords($input, $badWords) {
    foreach ($badWords as $word) {
        // Создаем регулярное выражение для поиска слова с возможными изменениями
        $pattern = '/(' . implode(str_split(preg_quote($word))) . ')/i';
        if (preg_match($pattern, $input)) {
            return true; // Найдено запрещенное слово
        }
    }
    return false; // Запрещенных слов нет
}

if (containsBadWords($description, $badWords)) {
    header("Location: /profile/settings.php?error=bad-description");
    exit();
}

if ($idUser == '') {
    header("Location: /");
} else {

    $sql = "UPDATE `users` SET `description` = ('$description') WHERE `users`.`id` = ('$idUser')";
    
    if ($connect -> query(query: $sql) === TRUE){
        header(header: "Location: /profile/settings.php");
    } else {
        echo 'Что то пошло не так';
    }
}