<?php

session_start();

unset($_SESSION['user']);

header(header: "Location: /login.php?account=ban");