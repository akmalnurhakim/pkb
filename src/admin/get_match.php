<?php
require_once __DIR__.'/../functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT 
        m.*, 
        ta.name AS team_a, 
        tb.name AS team_b
    FROM matches m
    JOIN teams ta ON m.team_a_id = ta.id
    JOIN teams tb ON m.team_b_id = tb.id
    WHERE m.id = ?
      AND m.match_time > NOW()
");

$stmt->execute([$id]);
$match = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$match) {
    echo "<p class='text-danger'>Match not found.</p>";
    exit;
}
?>

<input type="hidden" name="id" value="<?= htmlspecialchars($match['id']) ?>">
<p><strong><?= htmlspecialchars($match['team_a']) ?> vs <?= htmlspecialchars($match['team_b']) ?></strong></p>

<div class="row mb-3">
  <div class="col">
    <label for="score_a" class="form-label">Team A Score</label>
    <input type="number" id="score_a" class="form-control" name="score_a" 
           value="<?= intval($match['score_a']) ?>" min="0" required>
  </div>
  <div class="col">
    <label for="score_b" class="form-label">Team B Score</label>
    <input type="number" id="score_b" class="form-control" name="score_b" 
           value="<?= intval($match['score_b']) ?>" min="0" required>
  </div>
</div>

<div class="mb-3">
  <label for="played_at" class="form-label">Played At</label>
  <input type="datetime-local" id="played_at" name="played_at" class="form-control"
         value="<?= $match['played_at'] ? date('Y-m-d\TH:i', strtotime($match['played_at'])) : '' ?>" required>
</div>
