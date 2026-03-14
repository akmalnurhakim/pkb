<?php
require_once __DIR__.'/../functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }

$stmt = $pdo->prepare("SELECT m.*, ta.name AS team_a, tb.name AS team_b FROM matches m JOIN teams ta ON m.team_a_id=ta.id JOIN teams tb ON m.team_b_id=tb.id WHERE m.id=?");
$stmt->execute([$id]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$match) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $score_a = (int)$_POST['score_a'];
    $score_b = (int)$_POST['score_b'];
    $played_at = $_POST['played_at']?: null;
    $up = $pdo->prepare("UPDATE matches SET score_a=?, score_b=?, played_at=? WHERE id=?");
    $up->execute([$score_a, $score_b, $played_at, $id]);
    echo "<script>alert('Score updated.'); window.location='/admin/index.php';</script>";
    exit;
}
?>
<!doctype html>
<html><head><title>Edit Score</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="p-4">
<div class="container">
  <h4>Edit Score — Court <?= $match['court_num'] ?></h4>
  <p><?=htmlspecialchars($match['team_a'])?> vs <?=htmlspecialchars($match['team_b'])?></p>
  <form method="post">
    <div class="row mb-2">
      <div class="col"><input type="number" name="score_a" value="<?=intval($match['score_a'])?>" class="form-control" min="0"></div>
      <div class="col"><input type="number" name="score_b" value="<?=intval($match['score_b'])?>" class="form-control" min="0"></div>
    </div>
    <div class="mb-2">
      <label>Played at (optional)</label>
      <input type="datetime-local" name="played_at" class="form-control" value="<?= $match['played_at'] ? date('Y-m-d\TH:i', strtotime($match['played_at'])) : '' ?>">
    </div>
    <button class="btn btn-primary">Save</button>
    <a class="btn btn-link" href="index.php">Back</a>
  </form>
</div>
</body></html>
