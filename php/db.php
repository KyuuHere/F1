<?php
$dbFile = __DIR__ . '/../data/f1.sqlite';

function getDatabase(): PDO
{
    static $db = null;
    if ($db !== null) {
        return $db;
    }

    global $dbFile;

    if (!file_exists($dbFile)) {
        throw new RuntimeException('Database file not found. Run /api/init_db.php first.');
    }

    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}
