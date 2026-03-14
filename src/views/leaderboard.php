<?php

require_once '../config/db.php';
include '../functions.php';
$groupA = getTeamsByGroup($pdo, 'A');
$groupB = getTeamsByGroup($pdo, 'B');

?> 

<!-- group A -->
<div class="container leaderboard-container mt-2">
  <div class="leaderboard-card p-3">

  <div class="mb-3">
      <h4 class="fw-bold text-center mb-4">Group A</h4>
      <hr>
    </div>

    <?php if (count($groupA) > 0): ?>
      <!-- Table Header -->
      <div class="row fw-bold text-muted mb-3 px-3">
        <div class="col-2 col-md-1 text-center">Rank</div>
        <div class="col-4 col-md-4">Team</div>
        <div class="col-4 col-md-3 text-center">W-L</div>
        <div class="col-2 col-md-4 text-center mt-2 mt-md-0">Points</div>
      </div>

      <!-- Team groupA -->
      <?php 
      $rank = 1;
      foreach ($groupA as $r): 
        $mp = (int)$r['match_played'];
        $w = (int)$r['match_won'];
        $l = (int)$r['match_loss'];
        $tp = (int)$r['total_points'];

        // Determine rank color class
        $rankClass = 'default-rank';
        if ($rank == 1) $rankClass = 'gold';
        elseif ($rank == 2) $rankClass = 'silver';
        elseif ($rank == 3) $rankClass = 'bronze';
      ?>
        <div 
        class="team-row border rounded p-2 mb-2 <?php echo ($rank <= 3) ? 'top-three rank-' . $rank : ''; ?>" 
        data-team-id="<?= $r['id'] ?>"
        >

          <div class="row align-items-center">
            <!-- Rank -->
            <div class="col-2 col-md-1 text-center">
              <div class="rank-badge <?php echo $rankClass; ?>">
                <?= $rank ?>
              </div>
            </div>

            <!-- Team -->
            <div class="col-4 col-md-4">
              <div class="d-flex align-items-center">
                <?php if ($rank <= 3): ?>
                  <span class="me-2 fs-5">
                    <?php 
                      if ($rank == 1) echo "🏆";
                      elseif ($rank == 2) echo "🥈";
                      elseif ($rank == 3) echo "🥉";
                    ?>
                  </span>
                <?php endif; ?>
                <div class="fw-bold text-truncate"><?= htmlspecialchars($r['name']) ?></div>
              </div>
            </div>

            <!-- Wins-Losses -->
            <div class="col-4 col-md-3 text-center">
              <div class="stat-value">
                <span class="text-success"><?= $w ?></span> -
                <span class="text-danger"><?= $l ?></span>
              </div>
              <div class="stat-label">W-L</div>
            </div>

            <!-- Total Points -->
            <div class="col-2 col-md-4 text-center mt-2 mt-md-0">
              <div class="stat-value"><?= $tp ?></div>
            </div>
          </div>
        </div>

        <!-- Placeholder for expansion -->
        <div class="team-matches-container" id="matches-<?= $r['id'] ?>"></div>
      <?php 
        $rank++;
      endforeach; 
      ?>

    <?php else: ?>
      <div class="alert alert-info text-center">
        <i class="fas fa-info-circle me-2"></i>No teams found. Add scores to populate the leaderboard!
      </div>
    <?php endif; ?>
  </div>
</div>

<!--group B -->
<div class="container leaderboard-container mt-2">
  <div class="leaderboard-card p-3">
    <div class="mb-3">
      <h4 class="fw-bold text-center mb-4">Group B</h4>
      <hr>
    </div>
    <?php if (count($groupB) > 0): ?>
      <!-- Table Header -->
      <div class="row fw-bold text-muted mb-3 px-3">
        <div class="col-2 col-md-1 text-center">Rank</div>
        <div class="col-4 col-md-4">Team</div>
        <div class="col-4 col-md-3 text-center">W-L</div>
        <div class="col-2 col-md-4 text-center mt-2 mt-md-0">Points</div>
      </div>

      <!-- Team groupB -->
      <?php 
      $rank = 1;
      foreach ($groupB as $r): 
        $mp = (int)$r['match_played'];
        $w = (int)$r['match_won'];
        $l = (int)$r['match_loss'];
        $tp = (int)$r['total_points'];

        // Determine rank color class
        $rankClass = 'default-rank';
        if ($rank == 1) $rankClass = 'gold';
        elseif ($rank == 2) $rankClass = 'silver';
        elseif ($rank == 3) $rankClass = 'bronze';
      ?>
        <div 
        class="team-row border rounded p-2 mb-2 <?php echo ($rank <= 3) ? 'top-three rank-' . $rank : ''; ?>" 
        data-team-id="<?= $r['id'] ?>"
        >

          <div class="row align-items-center">
            <!-- Rank -->
            <div class="col-2 col-md-1 text-center">
              <div class="rank-badge <?php echo $rankClass; ?>">
                <?= $rank ?>
              </div>
            </div>

            <!-- Team -->
            <div class="col-4 col-md-4">
              <div class="d-flex align-items-center">
                <?php if ($rank <= 3): ?>
                  <span class="me-2 fs-5">
                    <?php 
                      if ($rank == 1) echo "🏆";
                      elseif ($rank == 2) echo "🥈";
                      elseif ($rank == 3) echo "🥉";
                    ?>
                  </span>
                <?php endif; ?>
                <div class="fw-bold text-truncate"><?= htmlspecialchars($r['name']) ?></div>
              </div>
            </div>

            <!-- Wins-Losses -->
            <div class="col-4 col-md-3 text-center">
              <div class="stat-value">
                <span class="text-success"><?= $w ?></span> -
                <span class="text-danger"><?= $l ?></span>
              </div>
              <div class="stat-label">W-L</div>
            </div>

            <!-- Total Points -->
            <div class="col-2 col-md-4 text-center mt-2 mt-md-0">
              <div class="stat-value"><?= $tp ?></div>
            </div>
          </div>
        </div>

        <!-- Placeholder for expansion -->
        <div class="team-matches-container" id="matches-<?= $r['id'] ?>"></div>
      <?php 
        $rank++;
      endforeach; 
      ?>

    <?php else: ?>
      <div class="alert alert-info text-center">
        <i class="fas fa-info-circle me-2"></i>No teams found. Add scores to populate the leaderboard!
      </div>
    <?php endif; ?>
  </div>
</div>


  <script>
    $(document).on('click', '.team-clickable', function() {
    const teamId = $(this).data('team-id');
    const container = $(`#matches-${teamId}`);

    // Collapse if already open
    if (container.is(':visible')) {
        container.slideUp(200, () => container.empty());
        return;
    }

    // Close others first
    $('.team-matches-container').slideUp(200).empty();

    // Show loading state
    container.html('<div class="text-center py-2"><div class="spinner-border text-primary spinner-border-sm" role="status"></div> Loading matches...</div>').slideDown(200);

    // Fetch matches via AJAX
    $.ajax({
        url: `views/team_matches.php?id=${teamId}`,
        method: 'GET',
        success: function(response) {
        container.html(response);
        },
        error: function() {
        container.html('<div class="alert alert-danger py-2">Failed to load matches.</div>');
        }
    });
    });

  </script>