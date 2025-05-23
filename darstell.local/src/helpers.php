<?php

const DB_HOST = 'localhost';
const DB_USER = 'danyk2nu_dars';
const DB_PASS = 'ydD%1wMoR01b';
const DB_NAME = 'danyk2nu_dars';

function getDB(): bool|mysqli {
    return mysqli_connect(hostname: DB_HOST, username: DB_USER, password: DB_PASS, database: DB_NAME);
}