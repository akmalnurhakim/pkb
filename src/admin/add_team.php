<?php
require_once __DIR__.'/../functions.php';
require_login();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name = trim($_POST['name']);
    $players = $_POST['players'] ?? [];
    if ($name && count($players) == 5) {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO teams (name) VALUES (?)");
        $stmt->execute([$name]);
        $team_id = $pdo->lastInsertId();
        $pstmt = $pdo->prepare("INSERT INTO players (team_id, name, court_pos) VALUES (?, ?, ?)");
        for ($i=1;$i<=5;$i++){
            $pstmt->execute([$team_id, $players[$i-1], $i]);
        }
        $pdo->commit();
        header('Location: index.php');
        exit;
    } else {
        $error = 'Provide team name and 5 player names (one for each court).';
    }
}
?>
<!doctype html>
<html><head><title>Add Team</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="p-4">
<div class="container">
  <h4>Add Team</h4>
  <?php if(!empty($error)): ?><div class="alert alert-danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
  <form method="post">
    <div class="mb-2"><input name="name" class="form-control" placeholder="Team name" required></div>
    <?php for($i=1;$i<=5;$i++): ?>
      <div class="mb-2">
        <input name="players[]" class="form-control" placeholder="Player for court <?= $i ?>" required>
      </div>
    <?php endfor; ?>
    <button class="btn btn-primary">Create Team</button>
    <a href="index.php" class="btn btn-link">Back</a>
  </form>
</div>
</body></html>
