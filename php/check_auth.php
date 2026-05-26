<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['user_role'])) {
    $username = $_SESSION['admin_username'] ?? $_SESSION['databasemaster_username'] ?? '';
    echo json_encode([
        'authenticated' => true,
        'username' => $username,
        'role' => $_SESSION['user_role'],
    ]);
    exit;
}

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    echo json_encode(['authenticated' => true, 'username' => $_SESSION['admin_username'] ?? '', 'role' => 'admin']);
    exit;
}

if (isset($_SESSION['databasemaster_logged_in']) && $_SESSION['databasemaster_logged_in'] === true) {
    echo json_encode(['authenticated' => true, 'username' => $_SESSION['databasemaster_username'] ?? '', 'role' => 'databasemaster']);
    exit;
}

http_response_code(401);
echo json_encode(['authenticated' => false]);

