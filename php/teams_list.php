<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$role = $_SESSION['user_role'] ?? null;
if ($role !== 'admin' && $role !== 'superadmin') {
    http_response_code(403);
    echo json_encode(['error' => 'Přístup odepřen.']);
    exit;
}

require_once __DIR__ . '/db.php';

try {
    $db = getDatabase();
    $stmt = $db->query('SELECT id, name, points FROM teams ORDER BY id DESC');
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['teams' => $teams]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
