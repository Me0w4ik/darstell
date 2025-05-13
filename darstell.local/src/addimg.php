<?php

session_start();

require_once __DIR__ . "/helpers.php";

$idUser = $_SESSION['user']['id'];

$connect = getDB();

function imageSecurity($image){
    $nameimg = $image['name'];
    $type = $image['type'];
    $size = $image['size'];
    $blacklist = array(".php", '.js', '.html');

    foreach($blacklist as $row){
        if(preg_match("/$row\$/i", $nameimg)) return false;
    }

    if(($type != "image/png") && ($type != "image/jpeg") && ($type != "image/jpg")) return false;
    if($size > 31457280) return false; //30МГ

    return true;
}