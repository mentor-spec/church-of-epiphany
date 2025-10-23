<?php
// api/config.php
header("Content-Type: application/json; charset=UTF-8");
// For dev only: allow local origin. In production, restrict to your domain.
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$host = '127.0.0.1';
$db   = 'epiphany_db';
$user = 'root';
$pass = ''; // set your mysql password if any
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed: '.$e->getMessage()]);
  exit;
}
