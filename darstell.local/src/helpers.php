<?php

const DB_HOST = 'MySQL-8.0';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'darstell_lessons';

function getDB(): bool|mysqli {
    return mysqli_connect(hostname: DB_HOST, username: DB_USER, password: DB_PASS, database: DB_NAME);
}