<?php
require_once '../config/db.php';

// Fetch all teams for dropdown
$teams = $pdo->query("SELECT id, name FROM teams ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
  <h3 class="mb-3 text-white text-center">🏁 Match History</h3>

  <!-- Filters -->
  <form id="filterForm" class="mb-4 text-center">
    <div class="row justify-content-center">
      <div class="col-md-3 mb-2">
        <select name="court" id="court" class="form-select">
          <option value="">All Courts</option>
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>">Court <?= $i ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-md-3 mb-2">
        <select name="team" id="team" class="form-select">
          <option value="">All Teams</option>
          <?php foreach ($teams as $t): ?>
            <option value="<?= htmlspecialchars($t['name']) ?>">
              <?= htmlspecialchars($t['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2 mb-2">
        <button type="button" id="filterBtn" class="btn btn-primary w-100">Filter</button>
      </div>
      <div class="col-md-2 mb-2">
        <button type="button" id="resetBtn" class="btn btn-secondary w-100">Reset</button>
      </div>
    </div>
  </form>

  <!-- Match List -->
  <div id="matchResults">
    <div class="text-center text-muted">Loading match history...</div>
  </div>
</div>

<!-- jQuery (for simplicity) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Function to load match data
  function loadMatches(page = 1) {
    const court = $('#court').val();
    const team = $('#team').val();

    $.ajax({
        url: 'fetch_history.php',
        type: 'GET',
        data: { court: court, team: team, page: page },
        beforeSend: function() {
            $('#matchResults').html('<div class="text-center text-muted">Loading...</div>');
        },
        success: function(data) {
            $('#matchResults').html(data);
        },
        error: function() {
            $('#matchResults').html('<div class="alert alert-danger text-center">Error loading matches.</div>');
        }
    });
}

// Handle pagination button clicks (delegated for dynamically added buttons)
$(document).on('click', '.page-btn', function() {
    const page = $(this).data('page');
    loadMatches(page);
});


  // Initial load
  loadMatches();

  // When filter button clicked
  $('#filterBtn').click(function() {
    loadMatches();
  });

  // Reset filters
  $('#resetBtn').click(function() {
    $('#court').val('');
    $('#team').val('');
    loadMatches();
  });
});
</script>
