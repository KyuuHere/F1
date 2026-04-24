<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['authenticated' => true, 'username' => $_SESSION['admin_username']]);
} else {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['authenticated' => false]);
}
