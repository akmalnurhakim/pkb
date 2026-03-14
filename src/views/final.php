<?php 
require_once '../config/db.php';

$stmt = $pdo->query("
  SELECT m.*, ta.name AS team_a_name, tb.name AS team_b_name
  FROM matches m
  JOIN teams ta ON ta.id = m.team_a_id
  JOIN teams tb ON tb.id = m.team_b_id
  WHERE m.stage = 'final'
  ORDER BY m.played_at DESC
");
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="p-3">

</div>

<div class="container leaderboard-container mt-2">

  <div class="leaderboard-card p-3">
        <h3 class="text-center mb-4">🎯 Final Match</h3>
        <?php if ($matches): ?>
            <?php foreach ($matches as $m): ?>
            <div class="match-item d-flex justify-content-center align-items-center border rounded p-3 mb-3" style="gap: 40px;">
                <!-- Team A -->
                <div class="text-center">
                <div class="fw-bold fs-5"><?= htmlspecialchars($m['team_a_name']) ?></div>
                <?php if (!empty($m['played_at'])): ?>
                        <div class="fw-bold fs-4 text-primary"><?= htmlspecialchars($m['score_a']) ?></div>
                    <?php else: ?>
                        <div class="fw-bold fs-4 text-primary"> - </div>
                <?php endif; ?>
                </div>

                <!-- VS + Time -->
                <div class="text-center">
                <div class="text-muted fs-6">vs</div>
                <?php if (!empty($m['scheduled_at'])): ?>
                    <small class="text-secondary">(<?= date('H:i', strtotime($m['scheduled_at'])) ?>)</small>
                <?php endif; ?>
                </div>

                <!-- Team B -->
                <div class="text-center">
                    <div class="fw-bold fs-5"><?= htmlspecialchars($m['team_b_name']) ?></div>
                    <?php if (isset($m['played_at'])): ?>
                        <div class="fw-bold fs-4 text-primary"><?= htmlspecialchars($m['score_b']) ?></div>
                    <?php else: ?>
                        <div class="fw-bold fs-4 text-primary"> - </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="match-item d-flex justify-content-center border rounded p-3 mb-2">
                <div class="text-center text-muted">No match scheduled yet.</div>
            </div>
        <?php endif; ?>
  </div>
  </div>