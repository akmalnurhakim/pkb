<?php
require_once './config/db.php';

$court = isset($_GET['court']) ? trim($_GET['court']) : '';
$team  = isset($_GET['team']) ? trim($_GET['team']) : '';
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // matches per page
$offset = ($page - 1) * $limit;

$sql = "
  SELECT 
    m.court_num,
    ta.name AS team_a,
    tb.name AS team_b,
    m.match_time,
    m.team1_score,
    m.team2_score,
    m.stage
  FROM matches m
  JOIN teams ta ON ta.id = m.team1_id
  JOIN teams tb ON tb.id = m.team2_id
  WHERE m.winner_id IS NOT NULL
";

$conditions = [];
$params = [];

if ($court !== '') {
  $conditions[] = "m.court_num = :court";
  $params[':court'] = $court;
}

if ($team !== '') {
  $conditions[] = "(ta.name = :team OR tb.name = :team)";
  $params[':team'] = $team;
}

if ($conditions) {
  $sql .= " AND " . implode(" AND ", $conditions);
}

// Count total matches for pagination
$countSql = "SELECT COUNT(*) FROM ($sql) AS count_table";
$stmt = $pdo->prepare($countSql);
$stmt->execute($params);
$totalMatches = $stmt->fetchColumn();
$totalPages = ceil($totalMatches / $limit);

// Add ordering and limit
$sql .= " ORDER BY m.court_num ASC, m.match_time DESC LIMIT :offset, :limit";
$stmt = $pdo->prepare($sql);

// Bind params
foreach ($params as $key => $val) {
  $stmt->bindValue($key, $val);
}
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

$stmt->execute();
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($matches)): ?>
  <?php foreach ($matches as $m): ?>
    <div class="match-card mb-3 p-3 border rounded bg-light">
      <div class="court-badge">Court <?= htmlspecialchars($m['court_num']) ?></div>
      
      <?php
      $scoreA = (int)$m['team1_score'];
      $scoreB = (int)$m['team2_score'];

      $teamAClass = 'text-secondary';
      $teamBClass = 'text-secondary';

      if ($scoreA > $scoreB) {
        $teamAClass = 'text-success fw-bold';
        $teamBClass = 'text-danger';
      } elseif ($scoreB > $scoreA) {
        $teamAClass = 'text-danger';
        $teamBClass = 'text-success fw-bold';
      }
      ?>

      <h5 class="mt-2 mb-1">
        <span class="<?= $teamAClass ?>"><?= htmlspecialchars($m['team_a']) ?> (<?= $scoreA ?>)</span>
        &nbsp;vs&nbsp;
        <span class="<?= $teamBClass ?>">(<?= $scoreB ?>) <?= htmlspecialchars($m['team_b']) ?></span>
      </h5>

      <p class="text-muted mb-1">
        Scheduled: <?= date('M j, g:i A', strtotime($m['match_time'])) ?>
      </p>
    </div>
  <?php endforeach; ?>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
    <div class="pagination text-center mt-3">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <button class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-secondary' ?> page-btn" data-page="<?= $i ?>">
          <?= $i ?>
        </button>
      <?php endfor; ?>
    </div>
  <?php endif; ?>

<?php else: ?>
  <div class="alert alert-info text-center">No matches found for your filter.</div>
<?php endif; ?>
