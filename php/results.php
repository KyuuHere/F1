<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = getDatabase();
    $results = $db->query('SELECT race, date, circuit, winner, pole, fastest_lap FROM results ORDER BY date ASC')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['results' => $results], JSON_PRETTY_PRINT);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
