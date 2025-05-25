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
  $tgtag = $item[3];
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

if ($nik == ''){
    header("Location: /../src/ban.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка чекбокса
    if (isset($_POST['tgactive'])) {
        // чекбокс отмечен
        $tgactive = true;
    } else {
        // чекбокс не отмечен
        $tgactive = false;
    }

        // Обновление базы
    if ($tgactive) {
        $telegramactive = "<a href='$tgtag'><img src='icon/telegram.svg' style='width: 18px;'></a>";

        $stmt = $connect->prepare("UPDATE users SET tg = ? WHERE id = ?");
        $stmt->bind_param("ss", $telegramactive, $idUser);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt = $connect->prepare("UPDATE users SET tg = NULL WHERE id = ?");
        $stmt->bind_param("s", $idUser);
        $stmt->execute();
        $stmt->close();
    }


}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/css/settings.css">
    <link rel="icon" href="/../icon/Darstell.ico" type="image/x-icon">
    <title>Settings - Darstell</title>
</head>
<body>
  <div class="menu-account">
    <main>
        <p class="name-operation">Настройки</p>
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
                <button type="submit" class="submit sub2 disoff">Изменить</button>
            </form>
            <?= $info1 ?>
            <form class="datat" action="/src/EditProfile.php" method="post" id="EditProfile">
                <div class="input-container">
                    <textarea name="description" id="text" class="input opisanie" rows="4" maxlength="255"><?= $description ?></textarea>
                    <label for="text" class="placeholder">Описание</label>
                </div>
                <?= $info2 ?>
                <button type="submit" class="submit disoff">Изменить описание</button>
            </form>
            <form class="teg" method="post" action="" id="formtg">
                <p style="margin: 0 0 0 2px;">Показывать свой тег телеграм
                    <a href="<?= $tgtag ?>">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 0C3.584 0 0 3.584 0 8C0 12.416 3.584 16 8 16C12.416 16 16 12.416 16 8C16 3.584 12.416 0 8 0ZM11.712 5.44C11.592 6.704 11.072 9.776 10.808 11.192C10.696 11.792 10.472 11.992 10.264 12.016C9.8 12.056 9.448 11.712 9 11.416C8.296 10.952 7.896 10.664 7.216 10.216C6.424 9.696 6.936 9.408 7.392 8.944C7.512 8.824 9.56 6.96 9.6 6.792C9.60556 6.76655 9.60482 6.74014 9.59785 6.71504C9.59088 6.68995 9.57788 6.66694 9.56 6.648C9.512 6.608 9.448 6.624 9.392 6.632C9.32 6.648 8.2 7.392 6.016 8.864C5.696 9.08 5.408 9.192 5.152 9.184C4.864 9.176 4.32 9.024 3.912 8.888C3.408 8.728 3.016 8.64 3.048 8.36C3.064 8.216 3.264 8.072 3.64 7.92C5.976 6.904 7.528 6.232 8.304 5.912C10.528 4.984 10.984 4.824 11.288 4.824C11.352 4.824 11.504 4.84 11.6 4.92C11.68 4.984 11.704 5.072 11.712 5.136C11.704 5.184 11.72 5.328 11.712 5.44Z" fill="#54A9EB" />
                            <path d="M11.712 5.44C11.592 6.704 11.072 9.776 10.808 11.192C10.696 11.792 10.472 11.992 10.264 12.016C9.8 12.056 9.448 11.712 9 11.416C8.296 10.952 7.896 10.664 7.216 10.216C6.424 9.696 6.936 9.408 7.392 8.944C7.512 8.824 9.56 6.96 9.6 6.792C9.60556 6.76655 9.60482 6.74014 9.59785 6.71504C9.59088 6.68995 9.57788 6.66694 9.56 6.648C9.512 6.608 9.448 6.624 9.392 6.632C9.32 6.648 8.2 7.392 6.016 8.864C5.696 9.08 5.408 9.192 5.152 9.184C4.864 9.176 4.32 9.024 3.912 8.888C3.408 8.728 3.016 8.64 3.048 8.36C3.064 8.216 3.264 8.072 3.64 7.92C5.976 6.904 7.528 6.232 8.304 5.912C10.528 4.984 10.984 4.824 11.288 4.824C11.352 4.824 11.504 4.84 11.6 4.92C11.68 4.984 11.704 5.072 11.712 5.136C11.704 5.184 11.72 5.328 11.712 5.44Z" fill="white" />
                        </svg>
                    </a>
                </p>
                <label class="switch">
                    <input type="checkbox" id="tgactive" name="tgactive" <?= ($tgactive ?? false) ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
            </form>
            <div class="palka2"></div>
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

<script>
(function(){
        const text = document.getElementById('text');
        const name = document.getElementById('name');
        const prof = document.getElementById('EditProfile');
        const nik = document.getElementById('EditNik');

        let timeoutId;

        function submitnik() {
            nik.submit();
        }

        function submitprof() {
            prof.submit();
        }

        name.addEventListener('blur', () => {
            clearTimeout(timeoutId);
            submitnik();
        });

        text.addEventListener('blur', () => {
            clearTimeout(timeoutId);
            submitprof();
        });
    })();

    const checkbox = document.getElementById('tgactive');

    // Восстанавливаем состояние при загрузке
    const saved = localStorage.getItem('tgactive');
    if (saved !== null) {
        checkbox.checked = saved === 'true';
    }

    // Сохраняем состояние при изменении
    checkbox.addEventListener('change', () => {
        localStorage.setItem('tgactive', checkbox.checked);
    });

$(document).ready(function() {
    $('#tgactive').change(function() {
        // Формируем данные формы
        var formData = $('#formtg').serialize();

        $.ajax({
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('Ответ сервера:', response);
                // Здесь можно добавить дополнительную логику по результату
            },
            error: function(xhr, status, error) {
                console.error('Ошибка AJAX:', error);
            }
        });
    });
});
</script>
</body>
</html>
