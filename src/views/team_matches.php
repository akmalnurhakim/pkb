<?php
require_once '../config/db.php';

$team_id = $_GET['id'] ?? null;
if (!$team_id) {
  echo '<div class="alert alert-warning">No team selected.</div>';
  exit;
}

// Query for upcoming matches
$sql = "
  SELECT m.id, 
         ta.name AS team_a, 
         tb.name AS team_b, 
         m.scheduled_at, 
         m.court_num
  FROM matches m
  JOIN teams ta ON m.team_a_id = ta.id
  JOIN teams tb ON m.team_b_id = tb.id
  WHERE (m.team_a_id = :id OR m.team_b_id = :id)
    AND m.played_at IS NULL
  ORDER BY m.scheduled_at ASC
";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $team_id]);
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($matches) === 0) {
  echo '<div class="text-center text-muted py-2">No upcoming matches for this team.</div>';
  exit;
}

foreach ($matches as $m) {
  echo "
  <div class='match-item mb-2 p-2 border-start border-4 border-primary bg-light rounded'>
    <div class='d-flex justify-content-between'>
      <div><strong>{$m['team_a']}</strong> vs <strong>{$m['team_b']}</strong></div>
      <div class='text-muted small'>Court: {$m['court_num']}</div>
    </div>
    <div class='text-muted small mt-1'>Scheduled: " . date('M j, g:i A', strtotime($m['scheduled_at'])) . "</div>
  </div>
  ";
}
?>
