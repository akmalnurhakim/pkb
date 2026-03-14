<?php
// functions.php
session_start();
require_once __DIR__ . '/config/db.php';

function is_logged_in() {
    return !empty($_SESSION['admin_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /login.php');
        exit;
    }
}

function getTeamsByGroup($pdo, $group) {
    $stmt = $pdo->prepare("
        SELECT 
            t.id,
            t.name,
            t.`group`,
            t.match_played,
            t.match_won,
            t.match_loss,
            t.total_points,
            COALESCE(SUM(p.total_points), 0) AS points_scored,
            COALESCE(SUM(p.points_against), 0) AS points_against,
            MAX(p.name) AS captain_name
        FROM teams t
        LEFT JOIN players p ON p.team_id = t.id
        WHERE t.`group` = :group
        GROUP BY t.id
        ORDER BY t.total_points DESC, t.match_won DESC
    ");
    $stmt->execute(['group' => $group]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch top scorers
function getTopScorers($pdo, $limit = 10) {
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.name AS player_name,
            t.name AS team_name,
            t.`group` AS team_group,
            p.match_played,
            p.total_points,
            (p.total_points - p.points_against) AS points_diff
        FROM players p
        INNER JOIN teams t ON t.id = p.team_id
        WHERE p.match_played > 0
        ORDER BY 
            (p.total_points < 0),   -- negative points go last
            p.total_points DESC     -- then sort by total_points
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

