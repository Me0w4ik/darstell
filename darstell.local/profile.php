<?php

session_start();

require_once __DIR__ . "/src/helpers.php";

$connect = getDB();

$idUser = $_SESSION['user']['id'];

if ($idUser == ''){
  header(header: "Location: /login.php");
}

$sql = "SELECT * FROM users WHERE id = '$idUser'";

$result = mysqli_query(mysql: $connect, query:$sql);
$result = mysqli_fetch_all(result: $result);

$nik;
$description;
$avatar;

foreach($result as $item){
  $nik = $item[1];
  $description = $item[4];
  $avatar = $item[5];
}

$sql1 = "SELECT * FROM images WHERE iduser = '$idUser'";

$result = mysqli_query(mysql: $connect, query:$sql1);
$result = mysqli_fetch_all(result: $result);

$img = '';

foreach($result as $item){
  $img = $item[2];
}

?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <link rel="icon" href="icon/DarstellIcon.png" type="image/png">
    <title>Profile - Darstell</title>
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
      <div class="profile">
        <div class="profile-fon1">
        </div>
        <img src="/profile/avatars/<?= $avatar ?>" class="profile-avatar">
        <div class="profile-fon2">
          <div class="profile-settings-cont">
            <a href="/profile/settings.php">
              <svg class="profile-settings" width="16" height="16" viewBox="0 0 16 16"  xmlns="http://www.w3.org/2000/svg">
                <path d="M0.88889 12.4444C0.39797 12.4444 0 12.8424 0 13.3333V13.3333C0 13.8243 0.397969 14.2222 0.888889 14.2222H4.44444C4.93536 14.2222 5.33333 13.8243 5.33333 13.3333V13.3333C5.33333 12.8424 4.93536 12.4444 4.44444 12.4444H0.88889ZM0.88889 1.77778C0.39797 1.77778 0 2.17575 0 2.66667V2.66667C0 3.15759 0.397969 3.55556 0.888889 3.55556H8C8.49092 3.55556 8.88889 3.15759 8.88889 2.66667V2.66667C8.88889 2.17575 8.49092 1.77778 8 1.77778H0.88889ZM8 16C8.49092 16 8.88889 15.602 8.88889 15.1111V15.1111C8.88889 14.6202 9.28686 14.2222 9.77778 14.2222H15.1111C15.602 14.2222 16 13.8243 16 13.3333V13.3333C16 12.8424 15.602 12.4444 15.1111 12.4444H9.77778C9.28686 12.4444 8.88889 12.0465 8.88889 11.5556V11.5556C8.88889 11.0646 8.49092 10.6667 8 10.6667V10.6667C7.50908 10.6667 7.11111 11.0646 7.11111 11.5556V15.1111C7.11111 15.602 7.50908 16 8 16V16ZM4.44444 5.33333C3.95352 5.33333 3.55556 5.7313 3.55556 6.22222V6.22222C3.55556 6.71314 3.15759 7.11111 2.66667 7.11111H0.888889C0.397969 7.11111 0 7.50908 0 8V8C0 8.49092 0.397969 8.88889 0.888889 8.88889H2.66667C3.15759 8.88889 3.55556 9.28686 3.55556 9.77778V9.77778C3.55556 10.2687 3.95352 10.6667 4.44444 10.6667V10.6667C4.93536 10.6667 5.33333 10.2687 5.33333 9.77778V6.22222C5.33333 5.7313 4.93536 5.33333 4.44444 5.33333V5.33333ZM15.1111 8.88889C15.602 8.88889 16 8.49092 16 8V8C16 7.50908 15.602 7.11111 15.1111 7.11111H8C7.50908 7.11111 7.11111 7.50908 7.11111 8V8C7.11111 8.49092 7.50908 8.88889 8 8.88889H15.1111ZM10.6667 4.44444C10.6667 4.93536 11.0646 5.33333 11.5556 5.33333V5.33333C12.0465 5.33333 12.4444 4.93536 12.4444 4.44444V4.44444C12.4444 3.95352 12.8424 3.55556 13.3333 3.55556H15.1111C15.602 3.55556 16 3.15759 16 2.66667V2.66667C16 2.17575 15.602 1.77778 15.1111 1.77778H13.3333C12.8424 1.77778 12.4444 1.37981 12.4444 0.888889V0.888889C12.4444 0.397969 12.0465 0 11.5556 0V0C11.0646 0 10.6667 0.397969 10.6667 0.888889V4.44444Z"/>
              </svg>
            </a>
          </div>
          <div class="profile2">
            <div class="profile-info">
              <p class="profile-nik"><?= $nik ?></p>
              <div class="profile-palka"></div>
              <p class="profile-opisanie"> <?= $description ?> </p>
            </div>
          </div>
        </div>
      </div>
      <a href="/upload.php" class="upload-img" enctype="multipart/form-data">
        
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
      <div class="grid-container">
        <img class="grid-item" src = "/image/<?= $img ?>">
      </div>
    </div>
  </main>
</body>
</html>