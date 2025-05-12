<?php
require_once __DIR__ . "/src/helpers.php";

$nik = $login = $password1 = $password2 = "";

// Список запрещенных слов
$badWords = ['мат', 'пизда', 'хуй', 'сука', 'ебать', 'гандон', 'манда', 'блядь', 'хрен', 'пидор', 'ass', 'bitch', 'boomboom', 'cосk', 'crack', 'cunt', 'dick', 'pussy', 'pidor', 'huisos', 'манда', 'член', '4mo', 'idinahui', 'blya', 'чмо'];

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных
    $nik = isset($_POST["nik"]) ? $_POST["nik"] : "";
    $login = isset($_POST["login"]) ? $_POST["login"] : "";
    $password1 = isset($_POST["password1"]) ? $_POST["password1"] : "";
    $password2 = isset($_POST["password2"]) ? $_POST["password2"] : "";

// Проверка на наличие запрещенных слов
if (containsBadWords($nik, $badWords) || containsBadWords($login, $badWords)) {
  echo ('bad');
  exit();
}


    // Запись данных в БД
    $connect = getDB();

    // Проверка существования nik
    $nikCheck = $connect->prepare("SELECT COUNT(*) FROM users WHERE nik = ?");
    $nikCheck->bind_param("s", $nik);
    $nikCheck->execute();
    $nikCheck->bind_result($nikCount);
    $nikCheck->fetch();
    $nikCheck->close();

    // Проверка существования login
    $loginCheck = $connect->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
    $loginCheck->bind_param("s", $login);
    $loginCheck->execute();
    $loginCheck->bind_result($loginCount);
    $loginCheck->fetch();
    $loginCheck->close();

    // Логика проверки и вставки
    if ($nikCount > 0) {
        echo ('nonik');
        exit();
    } elseif ($loginCount > 0) {
        echo ('nologin');
        exit();
    } else {
        if ($password1 === $password2) {
            // Подготовка SQL-запроса для вставки
            $stmt = $connect->prepare("INSERT INTO users (nik, login, password2) VALUES (?, ?, ?)");

            if ($stmt) {
                // Привязываем параметры
                $stmt->bind_param("sss", $nik, $login, $password1); // assuming you meant to save password1

                // Выполняем запрос
                if ($stmt->execute()) {
                    echo ('success');
                    exit(); // Make sure to exit after redirection
                } else {
                    // Обработка ошибки выполнения запроса
                }

                // Закрываем подготовленный запрос
                $stmt->close();
            } else {
                // Обработка ошибки подготовки запроса
            }
        } else {
            echo ('nopassword');
            exit();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/register.css">
    <link rel="icon" href="icon/DarstellIcon.png" type="image/png">
    <title>Register - Darstell</title>
</head>
<body>
  <div class="header">
    <div class="container-menu">
      <div class="header-line">
        <div class="logo-menu">
          <div class="header-logo" href="@">
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
  <div class="menu-account">
    <p class="name-operation">Регистрация</p>
    <main>
      <form class="form" id="registerform" method="post">
        <div class="input-container" id="inputnik">
            <input required name="nik" id="name" class="input" type="name" maxlength="30" placeholder=" " />
            <label for="name" class="placeholder">Никнейм</label>
          </div>
        <div class="input-container" id="inputlogin">
          <input required name="login" id="login" class="input" type="login" maxlength="250" placeholder=" " />
          <label for="login" class="placeholder">Логин</label>
        </div>
        <div class="input-container" id="inputpassword1">
          <input required name="password1" id="password" class="input" type="password" maxlength="250" placeholder=" " />
          <label for="password" class="placeholder">Придумайте пароль</label>
        </div>
        <div class="input-container" id="inputpassword2">
            <input required name="password2" id="password" class="input" type="password" maxlength="250" placeholder=" " />
            <label for="password" class="placeholder">Подтвердите пароль</label>
          </div>
        <div class="palka2"></div>
        <button type="text" class="submit">Зарегистрироваться</button>
        <div class="form-funks">
          <div class="form-reg">
            <p class="form-netacc">Уже есть аккаунт?</p>
            <div class="general-palk"></div>
            <a href="profile.php" type="text" class="form-register">Войти</a>
          </div>
        </div>
      </form>
    </main>
    <main class="info-block" id="success">
      Регистрация прошла успешно
    </main>
    <main class="info-block" id="nonik">
      Пользователь с таким ником уже есть
    </main>
    <main class="info-block" id="nologin">
      Этот логин уже занят
    </main>
    <main class="info-block" id="nopassword">
      Ваши пароли, которые вы ввели, не совпадают
    </main>
    <main class="info-block" id="error">
      Проверьте подключение к интернету
    </main>
    <main class="info-block" id="bad">
      Плохие слова запрещены
    </main>
  </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>

$("#registerform").on("submit", function(e) {
  e.preventDefault();
  let dataSubmit = $(this).serialize();
  //console.log(dataSubmit);

  $.ajax({
    method:'post',
    dataType: 'html',
    data: dataSubmit,
    success: function(data) {
      //console.log(data);
      if (data == 'success'){
        $('#inputnik, #inputlogin, #inputpassword1, #inputpassword2, #seccess, #nonik, #nologin, #nopassword, #error, #bad').removeClass('show appear inputred');
        $('#success').addClass('show appear');
        setTimeout(() =>{
          window.location.href = '/login.php';
        }, 1000)
        
      }

      if (data == 'nonik'){
        $('#inputlogin, #inputpassword1, #inputpassword2, #seccess, #nonik, #nologin, #nopassword, #error, #bad').removeClass('show appear inputred');
        $('#inputnik').addClass('inputred');
        $('#nonik').addClass('show appear');
        setTimeout(() =>{
          $('#nonik').removeClass('appear');
        }, 500)
        
      }
      
      if (data == 'nologin'){
        $('#inputnik, #inputpassword1, #inputpassword2, #seccess, #nonik, #nologin, #nopassword, #error, #bad').removeClass('show appear inputred');
        $('#inputlogin').addClass('inputred');
        $('#nologin').addClass('show appear');
        setTimeout(() =>{
          $('#nologin').removeClass('appear');
        }, 500)
      }

      if (data == 'nopassword'){ 
        $('#inputnik, #inputlogin, #seccess, #nonik, #nologin, #nopassword, #error, #bad').removeClass('show appear inputred');
        $('#inputpassword1, #inputpassword2').addClass('inputred');
        $('#nopassword').addClass('show appear');
        setTimeout(() =>{
          $('#nopassword').removeClass('appear');
        }, 500)
      }

      if (data == 'bad'){
        $('#inputnik, #inputlogin, #inputpassword1, #inputpassword2, #seccess, #nonik, #nologin, #nopassword, #error, #bad').removeClass('show appear');
        $('#bad').addClass('show appear');
        setTimeout(() =>{
          $('#bad').removeClass('appear');
        }, 500)
      }

      
    },
    error: function() {
      //console.log('Что то не так');
      $('#inputnik, #inputlogin, #inputpassword1, #inputpassword2, #seccess, #nonik, #nologin, #nopassword, #error, #bad').removeClass('show appear');
      $('#error').addClass('show appear');
    }
  })
})

</script>

</body>
</html>