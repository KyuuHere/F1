<?php
session_start();
require_once __DIR__ . '/user_store.php';

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

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Uživatelské jméno a heslo jsou povinné.']);
    exit;
}

$user = validate_user($username, $password);
if ($user === null) {
    http_response_code(401);
    echo json_encode(['error' => 'Neplatné přihlašovací údaje.']);
    exit;
}

$role = $user['role'];
if ($role === 'databasemaster') {
    $_SESSION['databasemaster_logged_in'] = true;
    $_SESSION['databasemaster_username'] = $username;
} else {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
}

$_SESSION['user_role'] = $role;
$_SESSION['login_time'] = time();

echo json_encode([
    'status' => 'ok',
    'message' => 'Úspěšně přihlášen.',
    'role' => $role,
]);
