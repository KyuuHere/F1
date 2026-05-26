<?php
$dbHost = '127.0.0.1';
$dbPort = 3306;
$dbName = 'roblox';
$dbUser = 'root';
$dbPass = '';

// Připojí se k MySQL databázi roblox a vrátí PDO spojení.
function getDatabase(): PDO
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }

    global $dbHost, $dbPort, $dbName, $dbUser, $dbPass;

    $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);
    $db = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $db;
}
