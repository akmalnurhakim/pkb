<?php 
require_once '../config/db.php';
include '../functions.php';

$topScorers = getTopScorers($pdo, 10); // fetch top 10 players

?>

<div class="p-3">

</div>

<div class="container leaderboard-container mt-2">
  <div class="leaderboard-card p-3">
    <?php if (!empty($topScorers)): ?>
      <!-- Table Header -->
      <div class="row fw-bold text-muted mb-3 px-3">
        <div class="col-2 col-md-1 text-center">Rank</div>
        <div class="col-4 col-md-4">Player Name</div>
        <div class="col-4 col-md-3 text-center">Team</div>
        <div class="col-2 col-md-4 text-center">Points Diff</div>
      </div>

      <?php 
      $rank = 1;
      foreach ($topScorers as $r): 
        $name = $r['player_name'];
        $team = $r['team_name'];
        $pd = (int)$r['points_diff'];

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

            <!-- Player Name -->
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
                <div class="fw-bold text-truncate"><?= htmlspecialchars($name) ?></div>
              </div>
            </div>

            <!-- Team-->
            <div class="col-4 col-md-3 text-center">
              <div class="stat-value">
                <span class="text-success"><?= $team ?></span> 
              </div>
            </div>

            <!-- Point Diff -->
            <div class="col-2 col-md-4 text-center mt-2 mt-md-0">
              <div class="stat-value"><?= $pd ?></div>
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
