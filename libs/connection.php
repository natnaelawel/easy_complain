<?php

const DB_HOST = 'localhost';
const DB_NAME = 'easy_complain';
const DB_USER = 'root';
const DB_PASSWORD = '';

function db(): PDO
{
    static $pdo;

    if (!$pdo) {
        return new PDO(
            sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
            DB_USER,
            DB_PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    return $pdo;
}
