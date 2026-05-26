<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$role = $_SESSION['user_role'] ?? null;
if ($role !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['error' => 'Přístup odepřen.']);
    exit;
}

require_once __DIR__ . '/user_store.php';

echo json_encode(['users' => get_public_users()]);
