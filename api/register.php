<?php
require_once '../includes/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);

  if (!isset($data['username'], $data['email'], $data['password'], $data['role'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
  }

  // Check if email already exists
  $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->execute([$data['email']]);
  if ($stmt->rowCount() > 0) {
    echo json_encode(['error' => 'Email already exists']);
    exit;
  }

  // Insert new user
  $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?)");
  $stmt->execute([
    $data['username'],
    $data['email'],
    password_hash($data['password'], PASSWORD_DEFAULT),
    $data['role']
  ]);

  echo json_encode(['message' => 'Registration successful!']);
} else {
  http_response_code(405);
  echo json_encode(['error' => 'Method not allowed']);
}
