<?php
require_once 'config/db.php';
require_once 'components/navbar.php';
require_once 'config/no_cache.php';



?>
<!doctype html>
<html>
<head>
  <title>Pickleball Tournament - Leaderboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="./styles.css" rel="stylesheet">
    <style>
      body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 40px 0;
      }
      .container-main { max-width: 1200px; margin: 0 auto; }
      .header-card, .filter-card, .match-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      }
      .court-selector { display: flex; gap: 10px; flex-wrap: wrap; }
      .court-btn {
        padding: 20px 10px;
        border: 2px solid #667eea;
        border-radius: 10px;
        background: white;
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 0;
      }
      .court-btn:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
      }
      .court-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: #764ba2;
      }
      .match-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
      }
      .match-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }
      .team-name { font-weight: bold; font-size: 1.1rem; }
      .score-display { font-size: 1.6rem; font-weight: bold; color: #667eea; }
      .vs-text { color: #6c757d; font-weight: 600; }
      .match-time { color: #6c757d; font-size: 0.9rem; }
      .court-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: bold;
      }
      .empty-state { text-align: center; padding: 60px 20px; color: #6c757d; }
      .btn-back {
        border: 2px solid #667eea;
        color: #667eea;
        font-weight: 600;
        transition: all 0.2s ease;
      }
      .btn-back:hover { background: #667eea; color: white; }
      .rank-badge.default-rank {
        background-color: #495057; /* Dark gray for others */
      }
      .btn-group{
        min-width: 150px;
      }
    </style>
</head>
<body class="bg-white">
  <div class="container leaderboard-container mt-5">
    <div class="header-card text-center ">
      <h1 class="display-4 mb-2">
        <i class="fas fa-table-tennis"></i> Pickleball Tournament
      </h1>
      <p class="lead text-muted mb-0">Track your pickleball tournament progress</p>
      <p class="text-muted mt-2">
      <small>
        Last Updated: no updates yet
        <?php
          /*echo $lastUpdate
          ? date('F j, Y g:i A', strtotime($lastUpdate))
          : 'No updates yet'; */
          ?>
        </small>
      </p>

      <div class="text-center mb-3 mt-7">
        <div class="btn-group w-100 mt-5" role="group" aria-label="Basic example">
          <button type="button" class="btn btn-primary view-btn active flex-fill court-btn" data-view="leaderboard">
            🏆 Team Leaderboard
          </button>
          <button type="button" class="btn btn-primary view-btn flex-fill court-btn" data-view="semifinal">
            ⚔️ Player Leaderboard
          </button>
        </div>
      </div>
    </div>
  </div>



<div id="view-container"></div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
  // Default load leaderboard
  loadView('leaderboard');

  // Handle button clicks
  $('.view-btn').on('click', function() {
    $('.view-btn').removeClass('active');
    $(this).addClass('active');
    const view = $(this).data('view');
    loadView(view);
  });

  function loadView(view) {
    $('#view-container').html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p>Loading...</p></div>');
    $.ajax({
      url: `views/${view}.php`,
      method: 'GET',
      success: function(response) {
        $('#view-container').html(response);
      },
      error: function() {
        $('#view-container').html('<div class="alert alert-danger">Failed to load view.</div>');
      }
    });
  }
});
</script>

</body>
</html>

