<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$role = $_SESSION['user_role'] ?? null;
if ($role !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['error' => 'Přístup odepřen.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['error' => 'Pouze POST metoda je povolena.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

$username = trim($input['username'] ?? '');
if ($username === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Uživatelské jméno je povinné.']);
    exit;
}

require_once __DIR__ . '/user_store.php';

try {
    delete_user_by_username($username);
    echo json_encode(['status' => 'ok', 'message' => 'Uživatel byl úspěšně smazán.']);
} catch (RuntimeException $error) {
    http_response_code(400);
    echo json_encode(['error' => $error->getMessage()]);
}
