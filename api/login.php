  <?php
  require_once '../includes/db.php'; // Adjust path
  session_start();
  header('Content-Type: application/json');

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = trim($data['email']);
    $password = $data['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      unset($user['password']); // Don’t expose hashed password
      $_SESSION['user_id'] = $user['id']; // Set session user_id
      echo json_encode(['user' => $user]);
    } else {
      echo json_encode(['message' => 'Invalid email or password']);
    }
  } else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
  }

