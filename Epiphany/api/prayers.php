<?php
require_once __DIR__ . '/config.php';


$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
$stmt = $pdo->query('SELECT * FROM prayers ORDER BY created_at DESC');
echo json_encode($stmt->fetchAll()); exit;
}


if ($method === 'POST') {
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$name = $input['name'] ?? 'Anonymous';
$category = $input['category'] ?? '';
$message = $input['message'] ?? '';
$prayer_date = $input['prayer_date'] ?? date('Y-m-d');
$stmt = $pdo->prepare('INSERT INTO prayers (name, category, message, prayer_date) VALUES (?, ?, ?, ?)');
$stmt->execute([$name,$category,$message,$prayer_date]);
echo json_encode(['success'=>true,'id'=>$pdo->lastInsertId()]); exit;
}


if ($method === 'PUT') {
parse_str(file_get_contents('php://input'), $put);
$id = $put['id'] ?? null;
if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
// mark as read
if (isset($put['is_read'])) {
$stmt = $pdo->prepare('UPDATE prayers SET is_read = ? WHERE id = ?');
$stmt->execute([ (int)$put['is_read'], $id]);
echo json_encode(['success'=>true]); exit;
}
}


http_response_code(400); echo json_encode(['error'=>'Invalid method']);


// -------------------------
// File: uploads .htaccess
// -------------------------



