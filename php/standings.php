<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = getDatabase();

    $drivers = $db->query('SELECT name, team, points FROM drivers ORDER BY points DESC')->fetchAll(PDO::FETCH_ASSOC);
    $teams = $db->query('SELECT name, points FROM teams ORDER BY points DESC')->fetchAll(PDO::FETCH_ASSOC);
    $fastest = $db->query('SELECT race, driver, time FROM fastest_laps ORDER BY race ASC')->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'drivers' => $drivers,
        'teams' => $teams,
        'fastest' => $fastest,
    ], JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
