<?php
require_once __DIR__ . '/db.php';

try {
    $db = getDatabase();
    $res = $db->query('SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE()');
    foreach ($res as $row) {
        echo $row['table_name'] . "\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage();
}
