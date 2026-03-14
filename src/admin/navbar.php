<?php
// components/navbar.php
$isLoggedIn = isset($_SESSION['admin_id']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">🏓 GITD Pickleball</a>

    <!-- Toggler -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar items -->
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav align-items-lg-center text-center">
        <li class="nav-item mx-lg-2">
          <a class="nav-link btn text-white px-3 py-1 w-100" href="../">
            <i class="fas fa-home me-1"></i> Home
          </a>
        </li>
        <li class="nav-item mx-lg-2">
          <a class="nav-link btn text-white px-3 py-1 w-100" href="../matches.php">
            <i class="fas fa-history me-1"></i> Matches
          </a>
        </li>

        <?php if ($isLoggedIn): ?>
          <li class="nav-item mx-lg-2">
            <a class="nav-link btn btn-success text-white px-3 py-1 w-100" href="index.php">
              <i class="fas fa-user-shield me-1"></i> Admin Dashboard
            </a>
          </li>
          <li class="nav-item mx-lg-2">
            <a class="nav-link btn btn-danger text-white px-3 py-1 w-100" href="../logout.php">
              <i class="fas fa-sign-out-alt me-1"></i> Logout
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item mx-lg-2">
            <a class="nav-link btn btn-primary text-white px-3 py-1 w-100" href="./login.php">
              <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
