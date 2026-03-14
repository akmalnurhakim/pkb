<?php
// login.php
require_once 'config/db.php';
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_SESSION['admin_id'])) {
    header('Location: ./admin/index.php');
    exit;
}


$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT id, password_hash FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $username;
        header('Location: ./admin/index.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!doctype html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Organizer Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    @media (max-width: 576px) {
      .container { padding-top: 2rem !important; }
    }
    @media (max-width: 400px) {
      h4.card-title { font-size: 1.2rem; }
      input.form-control { font-size: 0.95rem; }
    }
  </style>
</head>
<body class="bg-light">
    <?php
echo date("H:i:s"); // Displays time in 24-hour format: hours:minutes:seconds
?>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="card-title mb-3">Organizer Login</h4>
            <?php if($error): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
            <form method="post">
              <div class="mb-2">
                <input class="form-control" name="username" placeholder="username" required>
              </div>
              <div class="mb-3">
                <input class="form-control" name="password" type="password" placeholder="password" required>
              </div>
              <button class="btn btn-primary w-100">Login</button>
            </form>
          </div>
        </div>
        <p class="text-muted mt-2 small">Use the admin account to manage matches & teams.</p>
      </div>
    </div>
  </div>
</body>
</html>
