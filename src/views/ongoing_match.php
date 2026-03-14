<?php
require_once '../config/db.php';

$sql = "
  SELECT m.*, 
         ta.name AS team_a, 
         tb.name AS team_b
  FROM matches m
  JOIN teams ta ON m.team1_id = ta.id
  JOIN teams tb ON m.team2_id = tb.id
  WHERE m.status = 1
    AND m.match_time <= NOW()
    AND m.match_time > NOW() - INTERVAL 15 MINUTE
";


$matches = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
  <h3 class="mb-3 text-white text-center">🟢 Ongoing Matches</h3>

  <?php if (count($matches)): ?>
    <?php
      // Prepare once for reuse
      $playerStmt = $pdo->prepare("
        SELECT name 
        FROM players 
        WHERE court_num = :court 
          AND team_id = :team
        ORDER BY name
      ");
    ?>

    <?php foreach ($matches as $m): ?>
      <?php
        // Fetch players for Team A
        $playerStmt->execute([
          ':court' => $m['court_num'],
          ':team' => $m['team1_id']
        ]);
        $teamAPlayers = $playerStmt->fetchAll(PDO::FETCH_COLUMN);

        // Fetch players for Team B
        $playerStmt->execute([
          ':court' => $m['court_num'],
          ':team' => $m['team2_id']
        ]);
        $teamBPlayers = $playerStmt->fetchAll(PDO::FETCH_COLUMN);
      ?>


      <div class="match-card">
        <div class="court-badge bg-success text-white px-2 py-1 rounded d-inline-block mb-2">
          Court <?= htmlspecialchars($m['court_num']) ?>
        </div>

        <div class="teams d-flex justify-content-between flex-wrap">
          <!-- Team A -->
          <div class="team-block flex-fill text-center">
            <h4 class="team-name mb-2"><?= htmlspecialchars($m['team_a']) ?></h4>
            <div class="players-list">
              <?php if ($teamAPlayers): ?>
                <?php foreach ($teamAPlayers as $p): ?>
                  <span class="badge bg-light text-dark me-1 mb-1"><?= htmlspecialchars($p) ?></span>
                <?php endforeach; ?>
              <?php else: ?>
                <span class="text-muted small">No players listed</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="vs align-self-center fw-bold mx-3">vs</div>

          <!-- Team B -->
          <div class="team-block flex-fill text-center">
            <h4 class="team-name mb-2"><?= htmlspecialchars($m['team_b']) ?></h4>
            <div class="players-list">
              <?php if ($teamBPlayers): ?>
                <?php foreach ($teamBPlayers as $p): ?>
                  <span class="badge bg-light text-dark me-1 mb-1"><?= htmlspecialchars($p) ?></span>
                <?php endforeach; ?>
              <?php else: ?>
                <span class="text-muted small">No players listed</span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="mt-3 text-center">
          <p class="text-muted mb-1">Started: <?= date('g:i A', strtotime($m['match_time'])) ?></p>
        </div>
      </div>
    <?php endforeach; ?>

  <?php else: ?>
    <div class="alert alert-info text-center">No ongoing matches right now.</div>
  <?php endif; ?>
</div>

<!-- 💄 Some quick styling -->
<style>

  .team-name {
    font-size: 1.5rem; /* Larger team name */
    font-weight: 600;
  }

  .players-list {
    margin-top: 0.5rem;
  }

  .badge {
    font-size: 0.85rem;
  }

  .vs {
    font-size: 1.2rem;
    color: #aaa;
  }
</style>
