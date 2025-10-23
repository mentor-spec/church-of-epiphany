<?php
require_once __DIR__ . '/config.php';

if ($stmt->fetch()) { http_response_code(409); echo json_encode(['error'=>'Email already exists']); exit; }


$hash = password_hash($password, PASSWORD_DEFAULT);
$role = $input['role'] ?? 'admin'; // change default creation role as needed
$stmt = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
$stmt->execute([$name,$email,$hash,$role]);
echo json_encode(['success'=>true, 'id'=>$pdo->lastInsertId()]);
exit;
}


if ($action === 'login' && $method === 'POST') {
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
if (!$email || !$password) { http_response_code(400); echo json_encode(['error'=>'Email and password required']); exit; }


$stmt = $pdo->prepare('SELECT id, password, role, name FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();
if ($user && password_verify($password, $user['password'])) {
// regenerate session id
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];
$_SESSION['name'] = $user['name'];
echo json_encode(['success'=>true, 'id'=>$user['id'], 'role'=>$user['role'], 'name'=>$user['name']]);
} else {
http_response_code(401); echo json_encode(['error'=>'Invalid credentials']);
}
exit;
}


if ($action === 'logout' && $method === 'POST') {
$_SESSION = [];
if (ini_get('session.use_cookies')) {
$params = session_get_cookie_params();
setcookie(session_name(), '', time() - 42000,
$params['path'], $params['domain'], $params['secure'], $params['httponly']
);
}
session_destroy();
echo json_encode(['success'=>true]);
exit;
}


if ($action === 'status' && $method === 'GET') {
if (!empty($_SESSION['user_id'])) echo json_encode(['logged'=>true,'id'=>$_SESSION['user_id'],'role'=>$_SESSION['role'],'name'=>$_SESSION['name']]);
else echo json_encode(['logged'=>false]);
exit;
}


http_response_code(400);
echo json_encode(['error'=>'Invalid action or method']);