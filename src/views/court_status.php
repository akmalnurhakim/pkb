<?php
require_once '../config/db.php';

// Fetch ongoing matches (assuming 15 min per match)
$ongoingSql = "
  SELECT 
    m.court_num,
    ta.name AS team_a,
    tb.name AS team_b,
    m.scheduled_at,
    DATE_ADD(m.scheduled_at, INTERVAL 15 MINUTE) AS estimated_end
  FROM matches m
  JOIN teams ta ON ta.id = m.team_a_id
  JOIN teams tb ON tb.id = m.team_b_id
  WHERE 
    m.played_at IS NULL
    AND NOW() BETWEEN m.scheduled_at AND DATE_ADD(m.scheduled_at, INTERVAL 15 MINUTE)
  ORDER BY m.court_num;
";
$ongoing = $pdo->query($ongoingSql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);

// Fetch next matches per court
$nextSql = "
  SELECT 
    m.court_num,
    ta.name AS team_a,
    tb.name AS team_b,
    m.scheduled_at
  FROM matches m
  JOIN teams ta ON ta.id = m.team_a_id
  JOIN teams tb ON tb.id = m.team_b_id
  WHERE 
    m.played_at IS NULL
    AND m.scheduled_at > NOW()
  ORDER BY m.court_num, m.scheduled_at;
";
$nextMatches = $pdo->query($nextSql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC);
?>

<div class="container court-status mt-4">
  <h2 class="mb-4 text-center">🏟️ Court Status</h2>

  <?php
  // Collect all courts that have ongoing or upcoming matches
  $courts = array_unique(array_merge(array_keys($ongoing), array_keys($nextMatches)));
  sort($courts);

  if (empty($courts)): ?>
    <div class="alert alert-info text-center">No active or upcoming matches found.</div>
  <?php else:
    foreach ($courts as $court):
      $current = $ongoing[$court][0] ?? null;
      $next = $nextMatches[$court][0] ?? null;
  ?>
      <div class="match-card mb-4 p-3 border rounded shadow-sm bg-white">
        <h4 class="text-center court-badge mb-3">Court <?= htmlspecialchars($court) ?></h4>

        <?php if ($current): ?>
          <div class="ongoing bg-light p-3 rounded mb-2 border-start border-success border-4">
            <h5>🟢 Ongoing Match</h5>
            <p><strong><?= htmlspecialchars($current['team_a']) ?></strong> vs <strong><?= htmlspecialchars($current['team_b']) ?></strong></p>
            <p class="text-muted small">
              <?= date('g:i A', strtotime($current['scheduled_at'])) ?> - 
              <?= date('g:i A', strtotime($current['estimated_end'])) ?>
            </p>
          </div>
        <?php else: ?>
          <div class="text-muted small mb-2">No ongoing match.</div>
        <?php endif; ?>

        <?php if ($next): ?>
          <div class="next bg-light p-3 rounded border-start border-primary border-4">
            <h5>🕒 Next Match</h5>
            <p><strong><?= htmlspecialchars($next['team_a']) ?></strong> vs <strong><?= htmlspecialchars($next['team_b']) ?></strong></p>
            <p class="text-muted small">Starts at <?= date('g:i A', strtotime($next['scheduled_at'])) ?></p>
          </div>
        <?php else: ?>
          <div class="text-muted small">No next match scheduled.</div>
        <?php endif; ?>
      </div>
  <?php endforeach; endif; ?>
</div>
