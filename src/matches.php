<?php
require_once 'config/db.php';
require_once 'components/navbar.php';
require_once 'config/no_cache.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pickleball Tournament - Matches</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 40px 0;
    }
    .container-main {
      max-width: 1200px;
      margin: 0 auto;
    }
    .view-btn {
      border-radius: 8px;
      padding: 12px 20px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .view-btn.active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
    }
    .match-card {
      background: white;
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .court-badge {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 8px 20px;
      border-radius: 50px;
      font-weight: bold;
      display: inline-block;
    }
  </style>
</head>
<body>
<div class="container-main text-center mt-5">
  <h1 class="text-white mb-4">Matches</h1>

  <div class="btn-group mb-4 border border-dark" role="group" aria-label="Match Views">
    <button class="btn btn-light view-btn active" data-view="ongoing_match">🟢 Ongoing</button>
    <button class="btn btn-light view-btn" data-view="upcoming_match">🕓 Scheduled</button>
    <button class="btn btn-light view-btn" data-view="match_history">📜 History</button>
  </div>

  <div id="match-view-container" class="mt-4"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  loadView('ongoing_match');

  $('.view-btn').on('click', function() {
    $('.view-btn').removeClass('active');
    $(this).addClass('active');
    const view = $(this).data('view');
    loadView(view);
  });

  function loadView(view) {
    $('#match-view-container').html('<div class="text-center py-4"><div class="spinner-border text-light"></div><p class="text-light">Loading...</p></div>');
    $.ajax({
      url: `views/${view}.php`,
      success: function(data) {
        $('#match-view-container').hide().html(data).fadeIn(300);
      },
      error: function() {
        $('#match-view-container').html('<div class="alert alert-danger">Failed to load content.</div>');
      }
    });
  }
});
</script>
</body>
</html>
