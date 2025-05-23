<?php

session_start();

require_once __DIR__ . "/src/helpers.php";
require "src/addimg.php";

$connect = getDB();

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
  $search = mysqli_real_escape_string($connect, $search); // Экранирование для безопасности
  header('Location: /search.php?search=' . urlencode($search));
  exit();
}

$idUser = $_SESSION['user']['id'];

if ($idUser == ''){
  header(header: "Location: /login.php");
  exit();
}

$sql = "SELECT * FROM users WHERE id = '$idUser'";

$result = mysqli_query($connect, $sql);
$result = mysqli_fetch_all(result: $result);

$nik;
$description;
$avatar;

foreach($result as $item){
  $nik = $item[1];
  $description = $item[4];
  $avatar = $item[5];
}

if ($nik == ''){
  header("Location: /src/ban.php");
  exit();
}

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
            echo("errorsecurity");
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
    <link rel="icon" href="icon/Darstell.ico" type="image/x-icon">
    <title>Upload - Darstell</title>
</head>
<body>
  <header>
    <div class="container-menu">
      <div class="header-line">
        <div class="logo-menu">
          <div class="header-logo" href="@">
            <a href="/" style="display: flex;">
              <img header-logo src="icon/darstell.svg">
            </a>
          </div>
        </div>
        <form class="poisk-c" method="GET" action="">
          <input required autocomplete="off" type="text" name="search" placeholder="Найти" class="poisk" id="poiskc">
          <button class="buttonpoisk" type="submit">
            <svg class="poisk-icon" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8.07131 0.00390317C9.34289 -0.0350485 10.5939 0.222945 11.7266 0.742537C12.2988 1.00501 12.3797 1.74885 11.9395 2.19899L11.9183 2.22073C11.5991 2.54715 11.1024 2.60774 10.6811 2.43193C9.94233 2.12368 9.141 1.96 8.32229 1.95996C6.72684 1.95996 5.19654 2.58028 4.06838 3.68359C2.94026 4.78689 2.30667 6.28346 2.30667 7.84375C2.30672 8.61621 2.46241 9.38106 2.76467 10.0947C3.06696 10.8084 3.50989 11.4567 4.06838 12.0029C4.62689 12.5491 5.28985 12.9827 6.01956 13.2783C6.74941 13.574 7.5323 13.7266 8.32229 13.7266C9.11216 13.7265 9.8943 13.5739 10.624 13.2783C11.3538 12.9827 12.0167 12.5492 12.5752 12.0029C13.1338 11.4566 13.5776 10.8085 13.8799 10.0947C14.1821 9.38109 14.3379 8.61617 14.3379 7.84375C14.3379 7.07579 14.1834 6.32359 13.8924 5.62599C13.7197 5.21184 13.7726 4.72434 14.0862 4.40342L14.1344 4.35419C14.5752 3.90308 15.3228 3.9682 15.5942 4.53761C15.9774 5.34148 16.222 6.20967 16.3086 7.1084C16.4712 8.79644 16.0693 10.482 15.1772 11.9175C14.9067 12.3526 14.9366 12.9265 15.3029 13.2847L18.3877 16.3018C18.4861 16.3914 18.5653 16.5 18.6201 16.6201C18.6749 16.7403 18.7047 16.8703 18.7071 17.002C18.7094 17.1336 18.6842 17.2646 18.6338 17.3867C18.5834 17.5087 18.5083 17.6198 18.4131 17.7129C18.318 17.8058 18.2048 17.8794 18.0801 17.9287C17.9553 17.978 17.8211 18.0023 17.6865 18C17.5522 17.9976 17.4197 17.9685 17.2969 17.915C17.1739 17.8614 17.0625 17.7838 16.9707 17.6875L13.858 14.6433C13.5037 14.2967 12.952 14.2682 12.5274 14.5238C11.051 15.4127 9.31197 15.8138 7.57034 15.6533C5.53917 15.4661 3.65724 14.5295 2.30764 13.0332C0.958017 11.5367 0.241829 9.59306 0.305689 7.59863C0.369567 5.6042 1.20867 3.70883 2.65139 2.29785C4.09408 0.886938 6.03206 0.0664495 8.07131 0.00390317Z" fill-opacity="0.8" />
            </svg>
          </button>
        </form>
        <div class="menu-icon">
          <a href="upload.php" class="menu-icon-upload">
            <svg width="24" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
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
    </div>
  </header>
  <main>
    <div class="container-menu2">
        <div class="uploadbackground">
            <form class="form" enctype="multipart/form-data" method="post" id="formuploadimg">
                  <div class="upload-img">
                      <input required id="uploadimg" type="file" accept=".jpg, .jpeg, .png" name="image">
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
                          <p class="upload-img-text2">Нажми, что бы вставить изображение</p>
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
                            <a class="tags">Напишите теги через запятую (пример: обои, интерьеры, мода)</a>
                        </div>
                        <button class="addimage" type="submit" name="addimage">Загрузить</button>
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
        window.location.href = 'upload.php';
      } else if (data.includes('errorsecurity')) {
        $('#errorsecurity').addClass('show appear');
      }
    },
  });
});

</script>


</body>
</html>