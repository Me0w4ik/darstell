<?php

require_once __DIR__ . "/helpers.php";

session_start();

$idUser = $_SESSION['user']['id'];

$nik = $_POST['nik'];

$connect = getDB();

$nikCheck = $connect->prepare("SELECT COUNT(*) FROM users WHERE nik = ?");
$nikCheck->bind_param("s", $nik);
$nikCheck->execute();
$nikCheck->bind_result($nikCount);
$nikCheck->fetch();
$nikCheck->close();

$badWords = ['мат', 'пизда', 'хуй', 'сука', 'ебать', 'гандон', 'манда', 'блядь', 'хрен', 'пидор', 'ass', 'bitch', 'boomboom', 'cосk', 'crack', 'cunt', 'dick', 'pussy', 'pidor', 'huisos', 'манда', 'член', '4mo', 'idinahui', 'blya'];

// Функция для проверки наличия запрещенных слов
function containsBadWords($input, $badWords) {
    foreach ($badWords as $word) {
        // Создаем регулярное выражение для поиска слова с возможными изменениями
        $pattern = '/(' . implode('.*?', str_split(preg_quote($word))) . ')/i';
        if (preg_match($pattern, $input)) {
            return true; // Найдено запрещенное слово
        }
    }
    return false; // Запрещенных слов нет
}

if (containsBadWords($nik, $badWords)) {
    header("Location: /profile/settings.php?error=bad-nick");
    exit();
}

if ($idUser == '') {
    header(header: "Location: /");
} else {
    
    if ($nikCount > 0) {
        header("Location: /profile/settings.php?error=nickname");
        exit();
    } else {
        //измен пароль

        $sql = "UPDATE `users` SET `nik` = ('$nik') WHERE `users`.`id` = ('$idUser')";
        
        if ($connect -> query(query: $sql) === TRUE){
            header(header: "Location: /profile/settings.php");
        } else {
            echo 'Что то пошло не так';
    }
    }
    
}
