<?php

session_start();

require_once __DIR__ . "/src/helpers.php";
require "src/addimg.php";

$connect = getDB();
if (!$connect) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

$idUser = $_SESSION['user']['id'];

if (empty($idUser)) {
    header("Location: /login.php");
    exit;
}

// Получаем данные о пользователе
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, 'i', $idUser);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_all($result, MYSQLI_ASSOC);

$userid = null;
foreach ($userData as $item) {
    $userid = $item['id']; // Предполагается, что 'id' - это имя колонки
}

// Получаем данные из формы
$name = isset($_POST["name"]) ? $_POST["name"] : "";
$description = isset($_POST["description"]) ? $_POST["description"] : "";
$tags = isset($_POST["tags"]) ? $_POST["tags"] : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image'];

    // Проверка на ошибки загрузки
    if ($image['error'] !== UPLOAD_ERR_OK) {
        echo("Ошибка загрузки файла: " . $image['error']);
    } else {
        // Проверка безопасности файла (например, тип файла)
        if (imageSecurity($image)) {
            loadimage($idUser, $image, $name, $description, $tags);
        } else {
            echo("errorsecurity"); // Безопасность аватара не прошла проверку.
            exit();
        }
    }
}

function loadimage($idUser, $image, $name, $description, $tags) {
    global $connect; // Обязательно добавьте глобальную переменную
    $type = $image['type'];
    $nameimg = "Darstell" . md5(microtime()) . '.' . substr($type, strlen("image/"));
    $dir = __DIR__ . '/image/';
    
    // Проверка директории
    if (!is_dir($dir) || !is_writable($dir)) {
        echo "Директория недоступна для записи.";
        return false;
    }

    $uploadfile = $dir . $nameimg;

    // Перемещение загруженного файла
    if (move_uploaded_file($image['tmp_name'], $uploadfile)) {
        $sql = "INSERT INTO images (iduser, img, name, description, tags) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss', $idUser, $nameimg, $name, $description, $tags);
        
        // Выполняем запрос
        if (mysqli_stmt_execute($stmt)) {
            return true; // Возвращаем true при успешной вставке
        } else {
            // Обработка ошибки при выполнении запроса
            echo("Ошибка обновления аватара: " . mysqli_stmt_error($stmt)); // Исправлено
            return false;
        }
    } else {
        return false; // Возвращаем false, если загрузка не удалась
    }
}

$data = $_POST;
if (isset($data['addimage'])) {
    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        if (imageSecurity($image)) {
            echo ("filewin");
        } else {
            echo("Вы выбрали фото не того типа");
        }
    } else {
        echo("Файл не был загружен.");
    }    
}
?>





<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/upload.css">
    <link rel="icon" href="icon/DarstellIcon.png" type="image/png">
    <title>upload - Darstell</title>
</head>
<body>
<div class="header">
    <div class="container-menu">
      <div class="header-line">
        <div class="logo-menu">
          <div class="header-logo">
            <a href="/">
              <img header-logo src="icon/darstell.svg">
            </a>
          </div>
        </div>
        <div class="poisk-c">
          <input id="input" type="text" placeholder="Найти" class="poisk">
          <img src="icon/lupa.svg" alt="Искать" class="poisk-icon">
        </div>
        <div class="menu-icon">
          <a href="upload.php" class="menu-icon-upload">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10.8918 14.2712V1.41214M10.8918 1.41214L13.8593 4.8742M10.8918 1.41214L7.92432 4.8742" stroke-opacity="0.8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
              <path d="M6.93495 21.1953H14.8482C17.6456 21.1953 19.0452 21.1953 19.9137 20.3269C20.7832 19.4564 20.7832 18.0587 20.7832 15.2604V14.2712C20.7832 11.4739 20.7832 10.0752 19.9137 9.20574C19.154 8.44607 17.9888 8.35012 15.8374 8.33825M5.9458 8.33825C3.79437 8.35012 2.62915 8.44607 1.86947 9.20574C1 10.0752 1 11.4739 1 14.2712V15.2604C1 18.0587 1 19.4574 1.86947 20.3269C2.16622 20.6236 2.52429 20.8185 2.97832 20.9471" stroke-width="1.5" stroke-linecap="round" />
            </svg>
          </a>
          <a href="profile.php" class="menu-icon-account">
            <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10.2957 9.7046C12.6994 9.7046 14.648 7.75601 14.648 5.3523C14.648 2.94859 12.6994 1 10.2957 1C7.89195 1 5.94336 2.94859 5.94336 5.3523C5.94336 7.75601 7.89195 9.7046 10.2957 9.7046Z" stroke-width="1.5" />
              <path d="M18.9979 18.4092C18.9993 18.2308 19 18.0494 19 17.8652C19 15.1613 15.1025 12.9688 10.2954 12.9688C5.48831 12.9688 1.59082 15.1613 1.59082 17.8652C1.59082 20.569 1.59082 22.7615 10.2954 22.7615C12.7229 22.7615 14.4736 22.5907 15.7358 22.286" stroke-width="1.5" stroke-linecap="round" />
            </svg>
          </a>
        </div>
      </div>
      <div class="parent-container">
        <div class="palka"></div>
      </div>
      <div class="header-line">
        <div class="battons">
          <a class="batton-oboi" href="@">Обои</a>
          <div class="general-palk"></div>
          <a class="batton-priroda" href="@">Природа</a>
          <div class="general-palk"></div>
          <a class="batton-3D" href="@">3D</a>
          <div class="general-palk"></div>
          <a class="batton-textura" href="@">4K</a>
          <div class="general-palk"></div>
          <a class="batton-intereri" href="@">Интерьеры</a>
          <div class="general-palk"></div>
          <a class="batton-4k" href="@">Текстуры</a>
          <div class="general-palk"></div>
          <a class="batton-filmi" href="@">Фильм</a>
          <div class="general-palk"></div>
          <a class="batton-ludi" href="@">Люди</a>
          <div class="general-palk"></div>
          <a class="batton-zoo" href="@">Животные</a>
          <div class="general-palk"></div>
          <a class="batton-game" href="@">Игры</a>
          <div class="general-palk"></div>
          <a class="batton-moda" href="@">Мода</a>
          <div class="general-palk"></div>
          <a class="batton-eda" href="@">Еда</a>
        </div>
      </div>
    </div>
  </div>
  <main>
    <div class="container-menu">
        <div class="uploadbackground">
            <form class="form" enctype="multipart/form-data" method="post" id="formuploadimg">
                  <div class="upload-img">
                      <input id="uploadimg" type="file" accept=".jpg, .jpeg, .png" name="image">
                      <label for="uploadimg" class="upload-imglabel">
                        <div id="preview"></div>
                        <div class="infocub">
                          <div class="cubcontainer">
                            <svg class="cub" width="142" height="163" viewBox="0 0 142 163" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g filter="url(#filter0_i_403_310)">
                                <path d="M141.033 37.4649L137.69 109.721L88.29 162.558L0.724776 125.638L0.724772 52.2727L53.4673 0.545117L141.033 37.4649Z" fill="#5D5EAD" />
                              </g>
                              <defs>
                                <filter id="filter0_i_403_310" x="0.724609" y="0.544922" width="190.308" height="188.013" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                  <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                  <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                  <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                  <feOffset dx="120" dy="26" />
                                  <feGaussianBlur stdDeviation="25" />
                                  <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1" />
                                  <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                                  <feBlend mode="normal" in2="shape" result="effect1_innerShadow_403_310" />
                                </filter>
                              </defs>
                            </svg>
                            <svg class="cub cubcart" viewBox="0 0 112 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M32.1298 2.05126C34.0981 0.10196 37.0307 -0.503273 39.6098 0.507542L106.571 26.7507C111.283 28.5974 112.538 34.6803 108.942 38.2416L78.9411 67.954C76.9728 69.9033 74.0402 70.5085 71.461 69.4977L4.50026 43.2546C-0.211822 41.4078 -1.46719 35.325 2.12877 31.7636L32.1298 2.05126Z" fill="#7879E0" />
                            </svg>
                            <svg class="cub" width="142" height="163" viewBox="0 0 142 163" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <foreignObject x="-49.6289" y="-49.8701" width="240.819" height="262.428">
                                <div xmlns="http://www.w3.org/1999/xhtml" style="backdrop-filter:blur(25px);clip-path:url(#bgblur_0_403_312_clip_path);height:100%;width:100%"></div>
                              </foreignObject>
                              <path data-figma-bg-blur-radius="50" d="M88.2898 162.558L88.2862 88.8913L141.19 37.463L53.2561 0.129751L0.371074 51.824L0.374696 125.491L88.2898 162.558Z" fill="url(#paint0_linear_403_312)" fill-opacity="0.26" />
                              <defs>
                                <clipPath id="bgblur_0_403_312_clip_path" transform="translate(49.6289 49.8701)">
                                  <path d="M88.2898 162.558L88.2862 88.8913L141.19 37.463L53.2561 0.129751L0.371074 51.824L0.374696 125.491L88.2898 162.558Z" />
                                </clipPath>
                                <linearGradient id="paint0_linear_403_312" x1="34.1265" y1="132.802" x2="81.0245" y2="18.3334" gradientUnits="userSpaceOnUse">
                                  <stop stop-color="#595AA5" />
                                  <stop offset="0.375" stop-color="#CFCFCF" />
                                  <stop offset="0.504808" stop-color="white" />
                                  <stop offset="0.625" stop-color="#CFCFCF" />
                                  <stop offset="1" stop-color="#5A5BA8" />
                                </linearGradient>
                              </defs>
                            </svg>
                          </div>
                          <div>
                            <p class="upload-img-text2">Нажми, что бы вставить изображение</p>
                          </div>
                        </div>
                      </label>
                  </div>
                <main class="info-block" id="filewin">Изображение добавлено</main>
                <main class="info-block" id="errorsecurity">Изображение не прошло проверку на допустимый тип или размер файла (Совет: загружайте файлы типа png, jpg, jpag размером не более 30МГ)</main>
                <div class="uploadbackgroundform">
                    <div class="input-container">
                        <input required name="name" id="name" class="input" type="name" maxlength="150" placeholder="" />
                        <label for="name" class="placeholder">Название</label>
                    </div>
                    <div class="input-container opisanie1">
                        <textarea name="description" id="descriptionImg" class="input opisanie" rows="4" maxlength="300" placeholder=""></textarea>
                        <label for="text" class="placeholder">Описание</label>
                    </div>
                    <div class="input-container">
                        <input name="tags" id="tags" class="input" type="tags" maxlength="255" placeholder="" />
                        <label for="tags" class="placeholder">Теги</label>
                    </div>
                    <div class="niz">
                        <div class="tagi">
                            <a class="tags">Обои</a>
                            <a class="tags">Природа</a>
                            <a class="tags">3D</a>
                            <a class="tags">4К</a>
                            <a class="tags">Интерьеры</a>
                            <a class="tags">Текстуры</a>
                            <a class="tags">Фильм</a>
                            <a class="tags">Люди</a>
                            <a class="tags">Животные</a>
                            <a class="tags">Игры</a>
                            <a class="tags">Мода</a>
                            <a class="tags">Еда</a>
                        </div>
                        <button type="submit" name="addimage">Загрузить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </main>

<script>
    document.getElementById('uploadimg').addEventListener('change', function() {
        const file = this.files[0];
        const uploadImgElement = document.querySelector('.infocub'); // Находим элемент с классом upload-img
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'previewimg';
                document.getElementById('preview').innerHTML = ''; // Очистить предыдущий просмотр
                document.getElementById('preview').appendChild(img); // Добавить новое изображение
                uploadImgElement.classList.add('disoff');
            }
            reader.readAsDataURL(file); // Чтение файла как URL
        }
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
$("#formuploadimg").on("submit", function(e) {
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
        window.location.href = 'profile.php';
      } else if (data.includes('errorsecurity')) {
        $('#errorsecurity').addClass('show appear');
      }
    },
  });
});

</script>


</body>
</html>