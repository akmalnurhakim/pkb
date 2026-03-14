<?php
require_once 'config/db.php';

$court = isset($_GET['court']) ? intval($_GET['court']) : 0;

// Query matches
$sql = "SELECT 
            m.id,
            m.court_num,
            m.team_a_id,
            m.team_b_id,
            m.played_at,
            m.last_updated,
            ta.name AS team_a_name,
            tb.name AS team_b_name
        FROM matches m
        JOIN teams ta ON m.team_a_id = ta.id
        JOIN teams tb ON m.team_b_id = tb.id
        WHERE 1=1";

if ($court > 0) {
    $sql .= " AND m.court_num = :court";
}
$sql .= " ORDER BY m.court_num ASC, m.played_at DESC";

$stmt = $pdo->prepare($sql);
if ($court > 0) $stmt->bindValue(':court', $court, PDO::PARAM_INT);
$stmt->execute();
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Fetch both name and court_pos
function getPlayers($pdo, $team_id) {
    $stmt = $pdo->prepare("SELECT name, court_pos FROM players WHERE team_id = :team_id ORDER BY court_pos ASC");
    $stmt->execute([':team_id' => $team_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Group matches by court
$matches_by_court = [];
foreach ($matches as $match) {
    $match['team_a_players'] = getPlayers($pdo, $match['team_a_id']);
    $match['team_b_players'] = getPlayers($pdo, $match['team_b_id']);
    $matches_by_court[$match['court_num']][] = $match;
}
?>

<?php if (empty($matches)): ?>
  <div class="text-center text-muted py-5">
    <i class="fas fa-calendar-times fa-5x mb-3"></i>
    <h4>No Matches Found</h4>
  </div>
<?php else: ?>
  <?php foreach ($matches_by_court as $court_num => $court_matches): ?>
    <div class="court-section mb-4">
      <div class="court-header mb-2">
        <i class="fas fa-map-marker-alt"></i> Court <?= htmlspecialchars($court_num) ?>
        <span class="badge bg-light text-dark float-end">
          <?= count($court_matches) ?> Match<?= count($court_matches) > 1 ? 'es' : '' ?>
        </span>
      </div>

      <div class="match-list">
        <?php foreach ($court_matches as $match): ?>
          <?php
            $team_a_filtered = array_filter($match['team_a_players'], fn($p) => $p['court_pos'] == $court_num);
            $team_b_filtered = array_filter($match['team_b_players'], fn($p) => $p['court_pos'] == $court_num);
          ?>

          <div class="match-item d-flex justify-content-between align-items-center border p-2 rounded mb-2 bg-light">
            <div class="team-info text-center flex-fill">
              <div class="team-name fw-bold"><?= htmlspecialchars($match['team_a_name']) ?></div>
              <div class="player-list text-muted small">
                <?= htmlspecialchars(implode(', ', array_column($team_a_filtered, 'name'))) ?: '<em>No players assigned</em>' ?>
              </div>
            </div>

            <div class="vs-divider text-center fw-bold">VS</div>

            <div class="team-info text-center flex-fill">
              <div class="team-name fw-bold"><?= htmlspecialchars($match['team_b_name']) ?></div>
              <div class="player-list text-muted small">
                <?= htmlspecialchars(implode(', ', array_column($team_b_filtered, 'name'))) ?: '<em>No players assigned</em>' ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <p class="text-muted mt-2">
        <small>Last Updated: 
          <?= isset($court_matches[0]['last_updated']) 
                ? date('F j, Y g:i A', strtotime($court_matches[0]['last_updated'])) 
                : 'N/A' ?>
        </small>
      </p>
    </div>
  <?php endforeach; ?>
<?php endif; ?>
