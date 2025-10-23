<?php
require 'config.php';
session_start();

$maxSize = 200 * 1024 * 1024; // 200MB for sermons
$allowedVideo = ['video/mp4','video/webm','audio/mpeg','audio/mp3'];

if(!isset($_FILES['file'])) {
  http_response_code(400);
  echo json_encode(['error'=>'No file uploaded']);
  exit;
}

$file = $_FILES['file'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if(!in_array($mime, $allowedVideo)) {
  http_response_code(400); echo json_encode(['error'=>'Invalid file type']);
  exit;
}

if($file['size'] > $maxSize){
  http_response_code(400); echo json_encode(['error'=>'File too large']);
  exit;
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
$targetDir = __DIR__ . '/../uploads/sermons/';
if(!is_dir($targetDir)) mkdir($targetDir, 0755, true);

if(move_uploaded_file($file['tmp_name'], $targetDir.$newName)) {
  // Save metadata in DB
  $title = $_POST['title'] ?? $file['name'];
  $speaker = $_POST['speaker'] ?? '';
  $date = $_POST['sermon_date'] ?? date('Y-m-d');

  $stmt = $pdo->prepare("INSERT INTO sermons (title, speaker, filename, file_type, sermon_date) VALUES (?, ?, ?, ?, ?)");
  $stmt->execute([$title, $speaker, $newName, $mime, $date]);

  echo json_encode(['success'=>true, 'filename'=>$newName, 'id'=>$pdo->lastInsertId()]);
} else {
  http_response_code(500); echo json_encode(['error'=>'Upload failed']);
}
