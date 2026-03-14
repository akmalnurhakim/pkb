<?php
require_once __DIR__ . '/../functions.php';
require_once 'navbar.php';
require_login();
$pdo = $GLOBALS['pdo'];

// Fetch teams
$teams = $pdo->query("SELECT * FROM teams ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Fetch matches
$matches = $pdo->query("
  SELECT 
    m.*, 
    ta.name AS team1_name, 
    tb.name AS team2_name,
    CASE 
      WHEN m.status = 1 THEN 'Upcoming'
      WHEN m.status = 2 THEN 'Completed'
      ELSE 'Unknown'
    END AS status_text
  FROM matches m
  JOIN teams ta ON m.team1_id = ta.id
  JOIN teams tb ON m.team2_id = tb.id
  ORDER BY 
    (m.status = 1) DESC,   -- Upcoming first (status = 1)
    m.match_time ASC        -- Then sort by match time
")->fetchAll(PDO::FETCH_ASSOC);


?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Organizer Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
        height: auto;
        min-height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
        }

        body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #333;
        padding: 40px 0;
        }

        .container-main { max-width: 1200px; margin: 0 auto; }
        .card-main { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); padding: 20px; margin-bottom: 30px; width: 100%; overflow-x: auto; }
        .table thead { background: #f1f1f1; color: #555; }
        .header-title { font-weight: 700; background: -webkit-linear-gradient(#667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .modal-dialog { max-height: 90vh; overflow-y: auto; }
    </style>

</head>
<body>
  <div class="container container-main">

    <!-- Header -->
    <div class="card-main mt-5 mb-4 text-center ">
            <h1 class="display-6 mb-2 fw-bold text-dark">
            <i class="fas fa-user-shield me-2 text-primary"></i>Organizer
            </h1>
            <p class="lead text-muted mb-2">Manage Teams & Matches</p>
            
            <div class="d-flex justify-content-center gap-3 mt-3">
                <button class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                <i class="fas fa-users me-2"></i>Add Team
                </button>
                <a href="../logout.php" class="btn btn-outline-danger px-4">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </div>
    </div>

    <!-- Alert -->
    <div id="alert-box"></div>

    <!-- Matches Section -->
        <div class="card-main">
            <div class="row mb-3 text-center">
                <h4 class="mb-3">
                    <i class="fas fa-table-tennis-paddle-ball me-2 text-success"></i>Matches
                </h4>
                <button class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#addMatchModal">
                    <i class="fas fa-calendar-plus me-2"></i>Schedule Match
                </button>
            </div>

            <!-- Filter Controls -->
            <div class="row mb-3">
                <div class="col-md-3 mb-2">
                    <select id="courtFilter" class="form-select">
                        <option value="">All Courts</option>
                        <option value="1">Court 1</option>
                        <option value="2">Court 2</option>
                        <option value="3">Court 3</option>
                        <option value="4">Court 4</option>
                        <option value="5">Court 5</option>
                    </select>
                </div>
                <div class="col-md-4">
                <input type="text" id="teamFilter" class="form-control" placeholder="Search by team name...">
                </div>
            </div>

            <!-- Match Table -->
            <div id="matchTableContainer" class="table-responsive">
                <!-- Table will load here via AJAX -->
                <div class="text-center text-muted py-4">Loading matches...</div>
            </div>
        </div>
  </div>

  <!-- MODAL -->
  <div class="modal fade" id="addMatchModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="matchForm">
          <div class="modal-header">
            <h5 class="modal-title">Schedule Match</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Court (1-5)</label>
              <input type="number" name="court_num" class="form-control" min="1" max="5" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Stage</label>
              <select name="stage" class="form-select" required>
                <option value="">-- Select Stage --</option>
                  <option value="group">Group</option>
                  <option value="semifinal">Semifinal</option>
                  <option value="final">Final</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Team A</label>
              <select name="team1" class="form-select" required>
                <option value="">-- Select Team --</option>
                <?php foreach($teams as $t): ?>
                  <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Team B</label>
              <select name="team2" class="form-select" required>
                <option value="">-- Select Team --</option>
                <?php foreach($teams as $t): ?>
                  <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Scheduled Time</label>
                <input type="time" name="match_time" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" type="submit">Save</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal: Edit Match -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel"><i class="fas fa-pen me-2"></i>Edit Match</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMatchForm">
                <div class="modal-body">
                <div id="modalContent"></div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-success">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Add Team Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addTeamModalLabel">
          <i class="fas fa-users me-2"></i>Add New Team
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="post" action="add_team.php">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Team Name</label>
            <input name="name" class="form-control" placeholder="Enter team name" required>
          </div>

          <div class="row g-2">
            <?php for ($i = 1; $i <= 5; $i++): ?>
              <div class="col-12 col-md-6">
                <label class="form-label">Player for Court <?= $i ?></label>
                <input name="players[]" class="form-control" placeholder="Player name" required>
              </div>
            <?php endfor; ?>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-check me-1"></i>Create Team
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('matchForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        const res = await fetch('add_match.php', {
        method: 'POST',
        body: formData
        });

        const data = await res.json();
        const alertBox = document.getElementById('alert-box');

        if (data.success) {
        alertBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        document.querySelector('#addMatchModal .btn-close').click();

        // Add new row dynamically
        const table = document.querySelector('#matchTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${data.match.court_num}</td>
            <td>${data.match.team_a} vs ${data.match.team_b}</td>
            <td><strong>0 - 0</strong></td>
            <td>-</td>
        `;
        table.prepend(row);
        this.reset();
        } else {
        alertBox.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    });
  </script>

  <!-- Filtering Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const courtFilter = document.getElementById('courtFilter');
        const teamFilter = document.getElementById('teamFilter');
        const rows = document.querySelectorAll('#matchTable tbody tr');

        function filterTable() {
            const courtValue = courtFilter.value.trim();
            const teamValue = teamFilter.value.toLowerCase().trim();

            rows.forEach(row => {
            const court = row.children[0].textContent.trim();
            const teams = row.children[1].textContent.toLowerCase();

            const matchCourt = !courtValue || court === courtValue;
            const matchTeam = !teamValue || teams.includes(teamValue);

            row.style.display = (matchCourt && matchTeam) ? '' : 'none';
            });
        }

        courtFilter.addEventListener('change', filterTable);
        teamFilter.addEventListener('input', filterTable);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const courtFilter = document.getElementById('courtFilter');
        const teamFilter = document.getElementById('teamFilter');
        const rows = document.querySelectorAll('#matchTable tbody tr');

        function filterTable() {
            const courtValue = courtFilter.value.trim();
            const teamValue = teamFilter.value.toLowerCase().trim();
            rows.forEach(row => {
            const court = row.children[0].textContent.trim();
            const teams = row.children[1].textContent.toLowerCase();
            const matchCourt = !courtValue || court === courtValue;
            const matchTeam = !teamValue || teams.includes(teamValue);
            row.style.display = (matchCourt && matchTeam) ? '' : 'none';
            });
        }

        courtFilter.addEventListener('change', filterTable);
        teamFilter.addEventListener('input', filterTable);

        // Handle Edit Button
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const res = await fetch(`get_match.php?id=${id}`);
            const html = await res.text();
            document.getElementById('modalContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
            });
        });

         // Handle Save via AJAX
        document.getElementById('editMatchForm').addEventListener('submit', async e => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const res = await fetch('update_match.php', {
            method: 'POST',
            body: formData
        });
        const text = await res.text();
        alert(text);
        location.reload();
        });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('matchTableContainer');
  const courtFilter = document.getElementById('courtFilter');
  const teamFilter = document.getElementById('teamFilter');
  let currentPage = 1;
  let debounceTimer;

  async function loadMatches(page = 1) {
    currentPage = page;
    const court = courtFilter.value;
    const team = teamFilter.value;

    container.innerHTML = `<div class="text-center py-4 text-muted">Loading...</div>`;
    const res = await fetch(`fetch_matches.php?page=${page}&court=${encodeURIComponent(court)}&team=${encodeURIComponent(team)}`);
    const html = await res.text();
    container.innerHTML = html;

    attachPaginationEvents();
    attachEditEvents();
  }

  function attachPaginationEvents() {
    document.querySelectorAll('.page-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        e.preventDefault();
        const page = parseInt(btn.dataset.page);
        if (!isNaN(page) && page > 0) loadMatches(page);
      });
    });
  }

  function attachEditEvents() {
    document.querySelectorAll('.editBtn').forEach(btn => {
      btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const res = await fetch(`get_match.php?id=${id}`);
        const html = await res.text();
        document.getElementById('modalContent').innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
      });
    });
  }

  // Debounce search input
  teamFilter.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => loadMatches(1), 400);
  });

  courtFilter.addEventListener('change', () => loadMatches(1));

  // Load initial
  loadMatches();
});
</script>


</body>
</html>
