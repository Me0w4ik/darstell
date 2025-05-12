<?php

session_start();

require_once __DIR__ . "/src/helpers.php";

$connect = getDB();
$idUser = $_SESSION['user']['id'];

$sql = "SELECT * FROM images WHERE id = '$idUser'";

$result = mysqli_query(mysql: $connect, query:$sql);
$result = mysqli_fetch_all(result: $result);

$img = '';

foreach($result as $item){
  $img = $item[2];
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="icon" href="icon/DarstellIcon.png" type="image/png">
  <title>Darstell</title>
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
          <input type="text" placeholder="Найти" class="poisk">
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
  <div class="container-menu">
    <div class="icon-background">
      <img class="icon-darstell" src="icon/darstell2.svg">
      <p class="icon-background-text">Источник визуальных материалов в Интернете</p>
    </div>
    <div class="grid-container">
      <img class="grid-item" src = "">
      <img class="grid-item" src = "">
      <img class="grid-item" src = "">
      <img class="grid-item" src = "">
      <img class="grid-item" src = "">
      <img class="grid-item" src = "">
      <img class="grid-item" src = "">
    </div>
  </div>
</body>
</html>
