<?php

session_start();

require_once __DIR__ . "/src/helpers.php";

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
$tgtag;
$description;
$avatar;
$tg;

foreach($result as $item){
  $nik = $item[1];
  $tgtag = $item[3];
  $description = $item[4];
  $avatar = $item[5];
  $tg = $item[6];
}

if ($nik == ''){
  header("Location: /src/ban.php");
  exit();
}

$sql1 = "SELECT `img` FROM `images` WHERE `iduser` = $idUser ORDER BY `images`.`id` DESC";
$stmt1 = mysqli_query($connect, $sql1);
$images = mysqli_fetch_all($stmt1, MYSQLI_ASSOC);

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <link rel="icon" href="icon/Darstell.ico" type="image/x-icon">
    <title>Profile - Darstell</title>
</head>
<body>
  <header>
    <div class="header">
      <div class="container-menu">
        <div class="header-line">
          <div class="logo-menu">
            <div class="header-logo">
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
          <a class="poisk-b disoff" id="poiskb">
            <svg height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M9.87218 0.236067C11.6553 0.181514 13.4063 0.586857 14.9587 1.39129C15.442 1.64174 15.5112 2.28368 15.1306 2.67281V2.67281C14.845 2.96475 14.3962 3.01271 14.0345 2.82301C12.8544 2.20394 11.509 1.85228 10.0802 1.85228C5.40744 1.85235 1.61925 5.60028 1.61925 10.2234C1.61939 14.8463 5.40752 18.5944 10.0802 18.5945C14.7529 18.5945 18.541 14.8464 18.5411 10.2234C18.5411 8.92571 18.2414 7.69765 17.7082 6.60164C17.5243 6.22358 17.574 5.76497 17.8679 5.46436V5.46436C18.2764 5.04655 18.9676 5.1065 19.241 5.62296C19.8359 6.74692 20.2125 7.98069 20.336 9.26341C20.5505 11.4894 19.9931 13.7122 18.7682 15.5798C18.4873 16.0081 18.5186 16.5825 18.8848 16.9407L22.2718 20.2536C22.6551 20.6286 22.6435 21.2491 22.2463 21.6094V21.6094C21.8834 21.9385 21.3276 21.9303 20.9746 21.5906L17.4478 18.1966C17.0928 17.855 16.5442 17.8261 16.1324 18.0964C14.1427 19.4023 11.5347 20.3338 9.23449 20.1218C6.6538 19.8839 4.26257 18.6922 2.54796 16.7908C0.833529 14.8893 -0.0761188 12.4204 0.00499322 9.88646C0.08615 7.35226 1.15245 4.94296 2.98546 3.15013C4.81843 1.35756 7.28139 0.315439 9.87218 0.236067Z" stroke-width="1.5" fill-opacity="0.8" />
            </svg>
          </a>
          <div class="menu-icon">
            <a href="upload.php" class="menu-icon-upload">
              <svg width="24" height="24" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
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
    </div>
  </header>
  <main>
    <div class="container-menu2">
      <div class="profile">
        <div class="profile-fon1">
        </div>
        <img src="/profile/avatars/<?= $avatar ?>" class="profile-avatar">
        <div class="profile-fon2">
          <div class="profile-settings-cont">
            <a href="/profile/settings.php">
              <svg class="profile-settings" width="20" viewBox="0 0 16 16"  xmlns="http://www.w3.org/2000/svg">
                <path d="M0.88889 12.4444C0.39797 12.4444 0 12.8424 0 13.3333V13.3333C0 13.8243 0.397969 14.2222 0.888889 14.2222H4.44444C4.93536 14.2222 5.33333 13.8243 5.33333 13.3333V13.3333C5.33333 12.8424 4.93536 12.4444 4.44444 12.4444H0.88889ZM0.88889 1.77778C0.39797 1.77778 0 2.17575 0 2.66667V2.66667C0 3.15759 0.397969 3.55556 0.888889 3.55556H8C8.49092 3.55556 8.88889 3.15759 8.88889 2.66667V2.66667C8.88889 2.17575 8.49092 1.77778 8 1.77778H0.88889ZM8 16C8.49092 16 8.88889 15.602 8.88889 15.1111V15.1111C8.88889 14.6202 9.28686 14.2222 9.77778 14.2222H15.1111C15.602 14.2222 16 13.8243 16 13.3333V13.3333C16 12.8424 15.602 12.4444 15.1111 12.4444H9.77778C9.28686 12.4444 8.88889 12.0465 8.88889 11.5556V11.5556C8.88889 11.0646 8.49092 10.6667 8 10.6667V10.6667C7.50908 10.6667 7.11111 11.0646 7.11111 11.5556V15.1111C7.11111 15.602 7.50908 16 8 16V16ZM4.44444 5.33333C3.95352 5.33333 3.55556 5.7313 3.55556 6.22222V6.22222C3.55556 6.71314 3.15759 7.11111 2.66667 7.11111H0.888889C0.397969 7.11111 0 7.50908 0 8V8C0 8.49092 0.397969 8.88889 0.888889 8.88889H2.66667C3.15759 8.88889 3.55556 9.28686 3.55556 9.77778V9.77778C3.55556 10.2687 3.95352 10.6667 4.44444 10.6667V10.6667C4.93536 10.6667 5.33333 10.2687 5.33333 9.77778V6.22222C5.33333 5.7313 4.93536 5.33333 4.44444 5.33333V5.33333ZM15.1111 8.88889C15.602 8.88889 16 8.49092 16 8V8C16 7.50908 15.602 7.11111 15.1111 7.11111H8C7.50908 7.11111 7.11111 7.50908 7.11111 8V8C7.11111 8.49092 7.50908 8.88889 8 8.88889H15.1111ZM10.6667 4.44444C10.6667 4.93536 11.0646 5.33333 11.5556 5.33333V5.33333C12.0465 5.33333 12.4444 4.93536 12.4444 4.44444V4.44444C12.4444 3.95352 12.8424 3.55556 13.3333 3.55556H15.1111C15.602 3.55556 16 3.15759 16 2.66667V2.66667C16 2.17575 15.602 1.77778 15.1111 1.77778H13.3333C12.8424 1.77778 12.4444 1.37981 12.4444 0.888889V0.888889C12.4444 0.397969 12.0465 0 11.5556 0V0C11.0646 0 10.6667 0.397969 10.6667 0.888889V4.44444Z"/>
              </svg>
            </a>
          </div>
          <div class="profile2">
            <div class="profile-info">
              <p class="profile-nik">
                <?= $nik ?>
                <?= $tg ?>
              </p>
              <div class="profile-palka"></div>
              <p class="profile-opisanie"> <?= $description ?> </p>
            </div>
          </div>
        </div>
      </div>
      <a class="upload-img" enctype="multipart/form-data" id="uploadLink">
        <div class="uploadsvg">
          <svg class="uploadsvg1" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.5 24.7501V2M19.5 2L24.75 8.12502M19.5 2L14.25 8.12502" stroke="#7879E0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <svg class="uploadsvg2" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.5 37H26.5C31.449 37 33.9252 37 35.4617 35.4635C37 33.9235 37 31.4507 37 26.5V24.75C37 19.8009 37 17.3264 35.4617 15.7882C34.1177 14.4442 32.0562 14.2744 28.25 14.2534M10.75 14.2534C6.94375 14.2744 4.88225 14.4442 3.53825 15.7882C2 17.3264 2 19.8009 2 24.75V26.5C2 31.4507 2 33.9252 3.53825 35.4635C4.06325 35.9885 4.69675 36.3333 5.5 36.5608" stroke="#7879E0" stroke-width="3" stroke-linecap="round" />
          </svg>
        </div>
        <div>
          <p class="upload-img-text1">Чего-то не хватает</p>
          <p class="upload-img-text2">Нажми, что бы загрузить изображение</p>
        </div>
        </label>
      </a>
      <div class="gridcontainer" id="gallery">
      </div>
    </div>
  </main>
<script>
  const poiskb = document.querySelector('.poisk-b');
  const poiskc = document.querySelector('.poisk-c');
  const poisk = document.querySelector('.poisk');
  const header = document.querySelector('.header');
  const logomenu = document.querySelector('.logo-menu');
  const menuicon = document.querySelector('.menu-icon');
  const zone = document.querySelector('.zone');
  const headerline = document.querySelector('.header-line');
  const placeholder = document.querySelector('#poisk::placeholder');

  document.addEventListener("DOMContentLoaded", function() {
    const uploadLink = document.getElementById('uploadLink');

    if (localStorage.getItem('uploadLinkHidden') === 'true') {
      uploadLink.style.display = 'none';
    }

    // Добавляем обработчик события на клик
    uploadLink.addEventListener('click', function(event) {
      uploadLink.style.display = 'none'; // Скрываем элемент
      localStorage.setItem('uploadLinkHidden', 'true'); // Сохраняем состояние в localStorage
      window.location.href = '/upload.php'; // Переходим по ссылке
    });
  });

  // Получаем данные изображений из PHP
  const images = <?php echo json_encode($images); ?>;

  // Находим элемент галереи
  const gallery = document.getElementById('gallery');

  // Добавляем каждое изображение в галерею
  images.forEach(image => {
      const imgElement = document.createElement('img');
      imgElement.className = 'grid-item';
      imgElement.src = '/image/' + image.img; // Путь к изображению
      gallery.appendChild(imgElement); // Добавляем элемент в галерею
  });
  window.addEventListener('scroll', function() {
    
    if (window.scrollY > 120) {
      header.classList.add('invincible'); // Добавить класс при скролле более 120px
      poiskc.classList.add('disoff');
      poiskb.classList.remove('disoff');
      poisk.style.background = 'rgb(0 0 0 / 0%)';
      header.classList.remove('zone');
      logomenu.style.display = 'flex';
      menuicon.style.display = 'flex';
      header.style.height = 'auto';
      headerline.style.padding = '';
      header.style.padding = '';
    } else {
      header.classList.remove('invincible'); // Удалить класс, если скролл меньше 120px
      poiskc.classList.remove('disoff');
      poiskb.classList.add('disoff');
      poisk.style.background = '';
      
    }

  });
  document.getElementById('poiskb').addEventListener('click', function(event) {
    event.preventDefault(); // Предотвращаем переход по ссылке

    logomenu.style.display = 'none';
    menuicon.style.display = 'none';
    poiskc.classList.remove('disoff');
    poiskb.classList.add('disoff');
    header.classList.add('zone');
    header.style.height = '65px';
    header.style.padding = '0 10px';
    headerline.style.padding = '15px 0';
  });
</script>
</body>
</html>
