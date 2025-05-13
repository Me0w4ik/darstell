<?php

session_start();

require_once __DIR__ . "/../src/helpers.php";
require "../src/EditAvatar.php";

$connect = getDB();

$idUser = $_SESSION['user']['id'] ?? null; // Используем null coalescing оператор для безопасного доступа

if (empty($idUser)) {
    header("Location: /login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $avatar = $_FILES['avatar'];

    // Проверка на ошибки загрузки
    if ($avatar['error'] !== UPLOAD_ERR_OK) {
        echo("Ошибка загрузки файла: " . $avatar['error']);
    } else {
        // Проверка безопасности файла (например, тип файла)
        if (avatarSecurity($avatar)) {
            loadAvatar($avatar);
        } else {
            echo'errorsecurity'; //Безопасность аватара не прошла проверку.
            exit();
        }
    }
}

function loadAvatar($avatar){
    $idUser = $_SESSION['user']['id'];
    $type = $avatar['type'];
    $name = 'Darstell' . md5(microtime()).'.'.substr($type, strlen("image/"));
    $dir = __DIR__ . '/avatars/';
    if (!is_dir($dir) || !is_writable($dir)) {
        return false;
    }

    $uploadfile = $dir.$name;

    if (move_uploaded_file($avatar['tmp_name'], $uploadfile)) {
        global $connect; // Объявляем переменную как глобальную
        $sql = "UPDATE users SET avatar = '$name' WHERE id = '$idUser'"; // Упрощаем запрос

        // Выполняем запрос
        if (mysqli_query($connect, $sql)) {
            return true;
        } else {
            // Обработка ошибки при выполнении запроса
            echo("Ошибка обновления аватара: " . mysqli_error($connect));
            return false;
        }
    } else {
        return false; // Возвращаем false, если загрузка не удалась
    }
}



$data = $_POST;
if (isset($data['editavatarbutton'])) {
    if (isset($_FILES['avatar'])) {
        $avatar = $_FILES['avatar'];
        if (avatarSecurity($avatar)) {
            echo 'filewin';
            loadAvatar($avatar);
        } else {
            //echo("Вы выбрали аватарку не того типа");
        }
    } else {
        //echo("Файл аватара не был загружен.");
    }    
}

$sql = "SELECT * FROM users WHERE id = '$idUser'";

$result = mysqli_query(mysql: $connect, query:$sql);
$result = mysqli_fetch_all(result: $result);

foreach($result as $item){
  $nik = $item[1];
  $login = $item[2];
  $password = $item[3];
  $description = $item[4];
  $avatar = $item[5];
}

$info1 = '';
$info2 = '';

if (isset($_GET['error']) && $_GET['error'] === 'nickname') {
    $info1 = '<main class="info-block show appear" id="info1">Такой ник уже существует</main>';
}
if (isset($_GET['error']) && $_GET['error'] === 'bad-nick') {
    $info1 =  '<main class="info-block show appear" id="info1">В нике не должно быть плохих слов</main>';
}
if (isset($_GET['error']) && $_GET['error'] === 'bad-description') {
    $info2 = '<main class="info-block show appear" id="info1">В описании не должно быть плохих слов</main>';
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/settings.css">
    <link rel="icon" href="/icon/DarstellIcon.png" type="image/png">
    <title>Settings - Darstell</title>
</head>
<body>
  <div class="menu-account">
    <p class="name-operation">Настройки</p>
    <main>
        <div class="form">
            <form class="upload-form" enctype="multipart/form-data" method="post" id="EditAvatar">
                <input id="file-upload" type="file" accept=".jpg, .jpeg, .png" name="avatar"/>
                <label for="file-upload" class="file-upload"><img src="avatars/<?= $avatar ?>" class="profile-avatar">
                <svg class="arrow" width="48" height="38" viewBox="0 0 48 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M46 19L29.5 2M46 19L29.5 36M46 19L17.125 19M2 19H8.875" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div id="preview"></div></label>
                <button type="submit" class="submitava" name="editavatarbutton">Изменить аватар</button>
                <main class="info-block" id="filewin">Аватарка изменена</main>
                <main class="info-block" id="errorsecurity">Аватар не прошёл проверку на допустимый тип или размер файла (Совет: загружайте файлы типа png, jpg, jpag размером не более 5МГ)</main>
            </form>
            <form class="datat d2" action="/src/EditNik.php" method="post" id="EditNik">
                <div class="input-container" id="inputnik">
                    <input required name="nik" id="name" class="input" type="name" maxlength="30" value="<?= $nik ?>" />
                    <label for="name" class="placeholder">Никенйм</label>
                </div>
                <button type="submit" class="submit sub2">Изменить</button>
            </form>
            <?= $info1 ?>
            <form class="datat" action="/src/EditProfile.php" method="post" id="EditProfile">
                <div class="input-container">
                    <textarea name="description" id="text" class="input opisanie" rows="4" maxlength="255"><?= $description ?></textarea>
                    <label for="text" class="placeholder">Описание</label>
                </div>
                <?= $info2 ?>
                <div class="width">
                    <div class="input-container">
                    <div name="login" id="login" class="input logn" maxlength="250" type="login"><?= $login ?></div>
                    <label for="login" class="placeholder">Логин</label>
                    </div>
                    <p class="width-text">нельзя менять логин</p>
                </div>
                <div class="input-container">
                <input required name="password" id="password" class="input" maxlength="250" value="<?= $password ?>" />
                <label for="password" class="placeholder">Пароль</label>
                </div>
                <div class="palka2"></div>
                <button type="submit" class="submit">Изменить данные</button>
                <div class="form-funks">
                <a href="/profile.php" class="form-profile">
                    <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 7L7 13M1 7L7 1M1 7L11.5 7M17 7L14.5 7" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Назад
                </a>
                <div class="form-ex">
                    <a href="/src/logout.php" class="form-exit">Выйти из аккаунта</a>
                </div>
                </div>
            </form>
        </div>
    </main>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
document.getElementById('file-upload').addEventListener('change', function() {
    const file = this.files[0];
    const arrow = document.querySelector('.arrow');
    const submitava = document.querySelector('.submitava');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = "newavatar";
            document.getElementById('preview').innerHTML = ''; // Очистить предыдущий просмотр
            document.getElementById('preview').appendChild(img); // Добавить новое изображение
            arrow.classList.add('dison');
            submitava.classList.add('dison');
        }
        reader.readAsDataURL(file); // Чтение файла как URL
    }
});
</script>

<script>
$("#EditAvatar").on("submit", function(e) {
  e.preventDefault();
  let dataSubmit = new FormData(this); // Используйте FormData для загрузки файлов
  
  $.ajax({
    method: 'post',
    dataType: 'html',
    data: dataSubmit,
    processData: false, // Не обрабатывайте данные
    contentType: false, // Не устанавливайте заголовок contentType
    success: function(data) {
      // Обработка ответа сервера
      if (data.includes('filewin')) {
        $('#filewin').addClass('show appear');
        $('.profile-avatar, .arrow.dison, .submitava.dison').addClass('disoff').removeClass('dison');
      } else if (data.includes('errorsecurity')) {
        $('#errorsecurity').addClass('show appear');
      }
    },
  });
});

</script>
</body>
</html>