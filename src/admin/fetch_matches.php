<?php
require_once __DIR__ . '/../functions.php';
$pdo = $GLOBALS['pdo'];

$limit = 8;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

// Filters
$court = isset($_GET['court']) ? trim($_GET['court']) : '';
$team = isset($_GET['team']) ? trim($_GET['team']) : '';

// Build WHERE conditions dynamically
$where = [];
$params = [];

if ($court !== '') {
    $where[] = 'm.court_num = :court';
    $params[':court'] = $court;
}

if ($team !== '') {
    $where[] = '(ta.name LIKE :team OR tb.name LIKE :team)';
    $params[':team'] = "%$team%";
}

$whereSQL = '';
if (!empty($where)) {
    $whereSQL = 'WHERE ' . implode(' AND ', $where);
}

// ✅ Count total matches
$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM matches m
    JOIN teams ta ON m.team1_id = ta.id
    JOIN teams tb ON m.team2_id = tb.id
    $whereSQL
");
$countStmt->execute($params);
$totalMatches = $countStmt->fetchColumn();

// ✅ Fetch paginated matches
$stmt = $pdo->prepare("
    SELECT 
        m.id,
        m.court_num,
        m.team1_id,
        m.team2_id,
        m.team1_score,
        m.team2_score,
        m.match_time,
        m.status,
        ta.name AS team1_name,
        tb.name AS team2_name
    FROM matches m
    JOIN teams ta ON m.team1_id = ta.id
    JOIN teams tb ON m.team2_id = tb.id
    $whereSQL
    ORDER BY 
        (m.status = 1) DESC,   -- Upcoming first
        m.match_time ASC
    LIMIT :limit OFFSET :offset
");

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalPages = ceil($totalMatches / $limit);
?>

<table class="table table-hover align-middle" id="matchTable">
    <thead>
        <tr>
            <th scope="col">Court</th>
            <th scope="col">Teams</th>
            <th scope="col">Score</th>
            <th scope="col" class="d-none d-md-block">Status</th>
            <th scope="col" class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($matches)): ?>
            <?php foreach($matches as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['court_num'] ?? '-') ?></td>
                <td><?= htmlspecialchars($m['team1_name']) ?> vs <?= htmlspecialchars($m['team2_name']) ?></td>
                <td><strong><?= intval($m['team1_score']) ?> - <?= intval($m['team2_score']) ?></strong></td>
                <td class="d-none d-md-block">
                    <?php if ($m['status'] == 2): ?>
                        <span class="text-success fw-semibold">Completed</span>
                    <?php elseif ($m['status'] == 1): ?>
                        <span class="text-secondary">
                            Upcoming (<?= htmlspecialchars(date('M j, Y g:i A', strtotime($m['match_time']))) ?>)
                        </span>
                    <?php else: ?>
                        <span class="text-muted">Unknown</span>
                    <?php endif; ?>
                </td>
                <td class="text-end">
                    <button 
                        class="btn btn-sm btn-outline-primary editBtn" 
                        data-id="<?= $m['id'] ?>">
                        <i class="fas fa-edit me-1"></i>Edit
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted py-4">No matches found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($totalPages > 1): ?>
<nav aria-label="Match pagination">
  <ul class="pagination justify-content-center mt-3">
    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
      <a class="page-link page-btn" href="#" data-page="<?= $page - 1 ?>">Previous</a>
    </li>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
        <a class="page-link page-btn" href="#" data-page="<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
      <a class="page-link page-btn" href="#" data-page="<?= $page + 1 ?>">Next</a>
    </li>
  </ul>
</nav>
<?php endif; ?>
