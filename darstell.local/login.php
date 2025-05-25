<?php
require_once __DIR__ . "/src/helpers.php";

$connect = getDB();


$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
  $search = mysqli_real_escape_string($connect, $search);
  header('Location: /search.php?search=' . urlencode($search));
  exit();
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgid = $_POST['tgid'];
    $tgtag = $_POST['tgtag'];
    $tgname = $_POST['tgname'];

    // Проверка существования login
    $tgidCheck = $connect->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
    $tgidCheck->bind_param("s", $tgid);
    $tgidCheck->execute();
    $tgidCheck->bind_result($tgidCount);
    $tgidCheck->fetch();
    $tgidCheck->close();

    if ($tgidCount > 0) { //Вход
        $sql = "SELECT * FROM users WHERE login = ?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("s", $tgid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $_SESSION['user']['id'] = $row['id'];
              $idUser = $_SESSION['user']['id'];
              $sql = "UPDATE users SET tag = '@$tgtag' WHERE id = '$idUser'"; // Упрощаем запрос

              // Выполняем запрос
              if (mysqli_query($connect, $sql)) {
                  header('location: /profile.php');
                  return true;
              } else {
                  // Обработка ошибки при выполнении запроса
                  echo("Ошибка обновления аватара: " . mysqli_error($connect));
                  return false;
              }
              }
        }
    } else { //Регестрация
        // Подготовка запроса на регистрацию
        $stmt = $connect->prepare("INSERT INTO users (nik, login, tag) VALUES (?, ?, CONCAT('@', ?))");
        $stmt->bind_param("sss", $tgname, $tgid, $tgtag); // Используем tgid как пароль

        // Выполнение запроса
        if ($stmt->execute()) {
            // Автоматический вход после регистрации
            $_SESSION['user']['id'] = $stmt->insert_id; // Получаем ID нового пользователя
            header('location: /profile.php');
        } else {
          echo('error');
          exit();
        }

        $stmt->close();
        exit; // Завершаем выполнение скрипта
    }
}
?>



<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="icon" href="icon/Darstell.ico" type="image/x-icon">
    <title>Log in - Darstell</title>
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
  <div class="menu-account">
    <p class="name-operation">Вход</p>
    <main>
      <form class="form" id="registerform" method="post">
        <input type="hidden" name="tgid" id="tgid" />
        <input type="hidden" name="tgtag" id="tgtag" />
        <input type="hidden" name="tgname" id="tgname" />
        <p>Войти с помощью Telegram</p>
        <div class="telega">
          <img class="tg" src="icon/telegram.svg">
          <img class="tgx" src="icon/krest.svg">
          <img class="tgd" src="icon/darstelltg.svg">
        </div>
        <?php if (isset($_GET['account']) && $_GET['account'] == 'ban'){echo ('<main class="info-block appear show" id="ban">Вы были заблокированы за непристойный контент. Если вы считаете, что это произошло по ошибке, то свяжитесь с <a href="https://t.me/KocMoHaBT_B_KPacHoM">тех поддержкой</a></main>');}?>      
        <div class="palka2"></div>
        <script async src="https://telegram.org/js/telegram-widget.js?22" data-telegram-login="Darstellbot" data-size="large" data-onauth="onTelegramAuth(user)" data-request-access="write"></script>
        <script type="text/javascript">
        function onTelegramAuth(user) {
        document.getElementById('tgid').value = user.id;
        document.getElementById('tgtag').value = user.username || '';
        document.getElementById('tgname').value = user.first_name;
        
        // Отправка формы
        document.getElementById('registerform').submit();
        }
        </script>
      </form>
    </main>
  </div>

</body>
</html>
