<?php

session_start();

require_once __DIR__ . "/helpers.php";

$idUser = $_SESSION['user']['id'];

$connect = getDB();

function avatarSecurity($avatar){
    $name = $avatar['name'];
    $type = $avatar['type'];
    $size = $avatar['size'];
    $blacklist = array(".php", '.js', '.html');

    foreach($blacklist as $row){
        if(preg_match("/$row\$/i", $name)) return false;
    }

    if(($type != "image/png") && ($type != "image/jpeg") && ($type != "image/jpg")) return false;
    if($size > 5242880) return false; //5МГ

    return true;
}


