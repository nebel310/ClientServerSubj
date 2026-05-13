<?php
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host = 'localhost';
        $port = '5432';
        $dbname = 'practice6_api';
        $user = 'postgres';
        $pass = '123456';
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}