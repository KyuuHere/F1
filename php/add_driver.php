<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Pouze POST metoda je povolena.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

$name = trim($input['name'] ?? '');
$team = trim($input['team'] ?? '');
$points = isset($input['points']) ? intval($input['points']) : null;

if ($name === '' || $team === '' || $points === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Jméno, tým a body jsou povinné.']);
    exit;
}

try {
    $db = getDatabase();
    $stmt = $db->prepare('INSERT INTO drivers (name, team, points) VALUES (:name, :team, :points)');
    $stmt->execute([':name' => $name, ':team' => $team, ':points' => $points]);

    echo json_encode(['status' => 'ok', 'message' => 'Jezdec byl přidán do databáze.']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
