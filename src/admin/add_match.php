<?php
require_once __DIR__ . '/../functions.php';
require_login();
header('Content-Type: application/json');

$pdo = $GLOBALS['pdo'];

// Collect and sanitize inputs
$court = (int)($_POST['court_num'] ?? 0);
$stage = trim($_POST['stage'] ?? '');
$team1 = (int)($_POST['team1'] ?? 0);
$team2 = (int)($_POST['team2'] ?? 0);
$match_time = $_POST['match_time'] ?? null;

// Validate inputs
if ($court < 1 || $court > 5 || !$team1 || !$team2 || $team1 === $team2 || !$stage || !$match_time) {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

// ✅ Insert new match
$stmt = $pdo->prepare("
    INSERT INTO matches (court_num, stage, team1_id, team2_id, match_time, status)
    VALUES (?, ?, ?, ?, ?, 1)
");
$stmt->execute([$court, $stage, $team1, $team2, $match_time]);

$match_id = $pdo->lastInsertId();

// ✅ Fetch team names for frontend display
$ta = $pdo->prepare("SELECT name FROM teams WHERE id = ?");
$tb = $pdo->prepare("SELECT name FROM teams WHERE id = ?");
$ta->execute([$team1]);
$tb->execute([$team2]);

$team1Name = $ta->fetchColumn();
$team2Name = $tb->fetchColumn();

echo json_encode([
    'success' => true,
    'message' => 'Match scheduled successfully!',
    'match' => [
        'id' => $match_id,
        'court_num' => $court,
        'stage' => $stage,
        'team1' => $team1Name,
        'team2' => $team2Name,
        'match_time' => $match_time,
        'status' => 'Upcoming'
    ]
]);
