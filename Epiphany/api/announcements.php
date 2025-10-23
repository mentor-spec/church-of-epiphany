<?php
require_once __DIR__ . '/config.php';
$status = $putVars['status'] ?? null;



$fields = [];
$params = [];
if ($title !== null) { $fields[]='title = ?'; $params[]=$title; }
if ($category !== null) { $fields[]='category = ?'; $params[]=$category; }
if ($content !== null) { $fields[]='content = ?'; $params[]=$content; }
if ($publish_date !== null) { $fields[]='publish_date = ?'; $params[]=$publish_date; }
if ($status !== null) { $fields[]='status = ?'; $params[]=$status; }
if (empty($fields)) { http_response_code(400); echo json_encode(['error'=>'No fields to update']); exit; }
$params[] = $id;
$sql = 'UPDATE announcements SET '.implode(', ',$fields).' WHERE id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode(['success'=>true]);
exit;
}


if ($method === 'DELETE') {
parse_str(file_get_contents('php://input'), $del);
$id = $del['id'] ?? null;
if (!$id) { http_response_code(400); echo json_encode(['error'=>'Missing id']); exit; }
$stmt = $pdo->prepare('DELETE FROM announcements WHERE id = ?');
$stmt->execute([$id]);
echo json_encode(['success'=>true]);
exit;
}


http_response_code(400);
echo json_encode(['error'=>'Invalid method']);