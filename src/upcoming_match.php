<?php
// upcoming_matches.php
require_once 'config/db.php';
require_once 'components/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upcoming Matches - Pickleball Tournament</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="./styles.css" rel="stylesheet">
  <style>
    .court-section { margin-bottom: 2rem; }
    .court-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 1.5rem; border-radius: 10px 10px 0 0; font-size: 1.3rem; font-weight: bold; }
    .match-list { border: 2px solid #667eea; border-top: none; border-radius: 0 0 10px 10px; background-color: white; }
    .match-item { padding: 1.5rem; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; justify-content: space-between; transition: background-color 0.2s; }
    .match-item:last-child { border-bottom: none; }
    .match-item:hover { background-color: #f8f9fa; }
    .team-info { flex: 1; text-align: center; }
    .team-name { font-size: 1.2rem; font-weight: bold; color: #2c3e50; margin-bottom: 0.25rem; }
    .player-list { font-size: 0.9rem; color: #6c757d; }
    .vs-divider { padding: 0 2rem; font-size: 1.5rem; font-weight: bold; color: #667eea; }
  </style>
</head>
<body>
    <div class="container leaderboard-container mt-5">
    <div class="header-card text-center ">
      <h1 class="display-4 mb-2">
        <i class="fas fa-clock"></i> Upcoming Matches
      </h1>
    </div>
  </div>

  <div class="container mb-5">
    <!-- Filter Buttons -->
    <div class="text-center mb-4">
      <div class="btn-group flex-wrap" id="courtButtons" role="group">
        <button class="btn btn-light active" data-court="0"><i class="fas fa-th"></i> All Courts</button>
        <?php for ($i = 1; $i <= 5; $i++): ?>
          <button class="btn btn-light btn-outline-warning" data-court="<?= $i ?>"><i class="fas fa-map-marker-alt"></i> Court <?= $i ?></button>
        <?php endfor; ?>
      </div>
    </div>

    <!-- Match Container -->
    <div id="matchContainer" class="fade">
      <!-- Content will be loaded here -->
      <div class="text-center text-muted py-5">
        <div class="spinner-border text-warning mb-3"></div>
        <p>Loading matches...</p>
      </div>
    </div>
  </div>

  <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">© 2025 Pickleball Tournament System</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const buttons = document.querySelectorAll('#courtButtons button');
      const container = document.getElementById('matchContainer');

      // Load initial (All Courts)
      loadMatches(0);

      buttons.forEach(btn => {
        btn.addEventListener('click', () => {
          buttons.forEach(b => b.classList.remove('active', 'btn-warning'));
          buttons.forEach(b => b.classList.add('btn-outline-warning'));
          btn.classList.remove('btn-outline-warning');
          btn.classList.add('btn-warning', 'active');
          loadMatches(btn.dataset.court);
        });
      });

      function loadMatches(court) {
        container.innerHTML = `
          <div class="text-center text-muted py-5">
            <div class="spinner-border text-warning mb-3"></div>
            <p>Loading matches...</p>
          </div>
        `;
        fetch(`fetch_matches.php?court=${court}`)
          .then(res => res.text())
          .then(html => {
            container.innerHTML = html;
            container.classList.add('show');
          })
          .catch(() => {
            container.innerHTML = `<p class="text-danger text-center py-4">Failed to load matches.</p>`;
          });
      }
    });
  </script>
</body>
</html>
