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

$required = ['race', 'date', 'circuit', 'winner', 'pole', 'fastest_lap'];
foreach ($required as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Chybí pole: $field"]);
        exit;
    }
}

try {
    $db = getDatabase();
    $stmt = $db->prepare(
        'INSERT INTO results (race, date, circuit, winner, pole, fastest_lap)
         VALUES (:race, :date, :circuit, :winner, :pole, :fastest_lap)'
    );
    $stmt->execute([
        ':race' => trim($input['race']),
        ':date' => trim($input['date']),
        ':circuit' => trim($input['circuit']),
        ':winner' => trim($input['winner']),
        ':pole' => trim($input['pole']),
        ':fastest_lap' => trim($input['fastest_lap']),
    ]);

    echo json_encode(['status' => 'ok', 'message' => 'Výsledek byl uložen do databáze.']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
