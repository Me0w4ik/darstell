<?php

session_start();

require_once __DIR__ . "/src/helpers.php";

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем наличие ключей в массиве $_POST
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $login = $_POST["email"];
        $password = $_POST["password"];

        $connect = getDB();

        $sql = "SELECT * FROM users WHERE email = '$login' AND password2 = '$password'";

        $result = $connect->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['user']['id'] = $row['id'];
                header("Location: /profile.php");
                exit();
            }
        } else {

        }
    } else {

    }
} else {

}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/darstell-login.css">
    <title>darstell</title>
</head>
<body>
    <div class="header">
      <div class="container-menu">
        <div class="header-line">
          <div class="logo-menu">
            <div class="header-logo" href="@">
              <a href="index.html">
                <img header-logo src="darstell.svg">
              </a>
            </div>
          </div>
          <div class="poisk-c">
            <input id="input" type="text" placeholder="Найти" class="poisk">
            <img src="lupa.svg" alt="Искать" class="poisk-icon">
          </div>
          <div class="menu-icon">
            <a class="menu-icon-upload">
              <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.8918 14.2712V1.41214M10.8918 1.41214L13.8593 4.8742M10.8918 1.41214L7.92432 4.8742" stroke-opacity="0.8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6.93495 21.1953H14.8482C17.6456 21.1953 19.0452 21.1953 19.9137 20.3269C20.7832 19.4564 20.7832 18.0587 20.7832 15.2604V14.2712C20.7832 11.4739 20.7832 10.0752 19.9137 9.20574C19.154 8.44607 17.9888 8.35012 15.8374 8.33825M5.9458 8.33825C3.79437 8.35012 2.62915 8.44607 1.86947 9.20574C1 10.0752 1 11.4739 1 14.2712V15.2604C1 18.0587 1 19.4574 1.86947 20.3269C2.16622 20.6236 2.52429 20.8185 2.97832 20.9471" stroke-width="1.5" stroke-linecap="round" />
              </svg>
            </a>
            <a class="menu-icon-arhiv">
              <svg width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.61475 10.6747C8.61475 10.1882 8.61475 9.9449 8.6941 9.75278C8.80029 9.49746 9.00344 9.29469 9.25896 9.18896C9.45108 9.10857 9.69436 9.10857 10.1809 9.10857H13.3133C13.7998 9.10857 14.0431 9.10857 14.2352 9.18792C14.4905 9.29412 14.6933 9.49726 14.799 9.75278C14.8794 9.9449 14.8794 10.1882 14.8794 10.6747C14.8794 11.1613 14.8794 11.4046 14.8001 11.5967C14.6939 11.852 14.4907 12.0548 14.2352 12.1605C14.0431 12.2409 13.7998 12.2409 13.3133 12.2409H10.1809C9.69436 12.2409 9.45108 12.2409 9.25896 12.1616C9.00364 12.0554 8.80087 11.8522 8.69514 11.5967C8.61475 11.4046 8.61475 11.1613 8.61475 10.6747Z" stroke="D1D1D1" stroke-width="1.5" />
                <path d="M20.6222 5.45411V11.7188C20.6222 15.6561 20.6222 17.6253 19.3985 18.848C18.1748 20.0706 16.2067 20.0717 12.2693 20.0717H11.2252M2.87232 5.45411V11.7188C2.87232 15.6561 2.87232 17.6253 4.09602 18.848C4.83108 19.5841 5.8376 19.8775 7.37872 19.9944M11.7473 1.27766H3.39438C2.40978 1.27766 1.918 1.27766 1.61208 1.58358C1.30615 1.88951 1.30615 2.38128 1.30615 3.36588C1.30615 4.35048 1.30615 4.84226 1.61208 5.14818C1.918 5.45411 2.40978 5.45411 3.39438 5.45411H20.1002C21.0848 5.45411 21.5766 5.45411 21.8825 5.14818C22.1884 4.84226 22.1884 4.35048 22.1884 3.36588C22.1884 2.38128 22.1884 1.88951 21.8825 1.58358C21.5766 1.27766 21.0848 1.27766 20.1002 1.27766H15.9237" stroke="D1D1D1" stroke-width="1.5" stroke-linecap="round" />
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
      <div class="menu-account">
        <p class="name-operation">Вход</p>
        <main>
          <form class="form" method="post">
            <div class="input-container">
              <input required name="email" id="email" class="input" type="email" placeholder=" " />
              <label for="email" class="placeholder">Логин</label>
            </div>
            <div class="input-container">
              <input required name="password" id="password" class="input" type="password" placeholder=" " />
              <label for="password" class="placeholder">Пароль</label>
            </div>
            <div class="palka2"></div>
            <button type="text" class="submit">Войти</button>
            <div class="form-funks">
              <div class="form-reg">
                <p class="form-netacc">Нет аккаунта?</p>
                <div class="general-palk"></div>
                <a href="register.html" class="form-register">Зарегистрироваться</a>
              </div>
              <a href="@" type="text" class="form-nopassword">Забыли пароль?</a>
            </div>
          </form>
        </main>
      </div>
</body>
</html>