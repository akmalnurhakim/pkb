<?php
require_once __DIR__.'/../functions.php';
require_login();

$id = (int)($_POST['id'] ?? 0);
$score_a = (int)($_POST['score_a'] ?? 0);
$score_b = (int)($_POST['score_b'] ?? 0);
$played_at = $_POST['played_at'] ?: null;

$stmt = $pdo->prepare("UPDATE matches SET score_a=?, score_b=?, played_at=? WHERE id=?");
if ($stmt->execute([$score_a, $score_b, $played_at, $id])) {
    echo "✅ Score updated successfully.";
} else {
    echo "❌ Failed to update score.";
}
