<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

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
$password = trim($input['password'] ?? '');

$valid_username = 'admin';
$valid_password = 'admin';

if ($username === $valid_username && $password === $valid_password) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    $_SESSION['login_time'] = time();
    
    echo json_encode([
        'status' => 'ok',
        'message' => 'Úspěšně přihlášen.',
    ]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Neplatné přihlašovací údaje.']);
}
