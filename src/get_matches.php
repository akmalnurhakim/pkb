<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// get_matches.php
include 'config/db.php'; // includes your PDO connection ($pdo)

header('Content-Type: application/json; charset=utf-8');

$court = isset($_GET['court']) ? intval($_GET['court']) : 0;

if ($court > 0) {
    try {
        $query = "
            SELECT 
                m.id,
                t1.name AS team1,
                t2.name AS team2,
                m.match_time,
                m.court_num
            FROM matches m
            JOIN teams t1 ON m.team1_id = t1.id
            JOIN teams t2 ON m.team2_id = t2.id
            WHERE m.status = 1 AND m.court_num = :court
            ORDER BY m.match_time ASC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':court', $court, PDO::PARAM_INT);
        $stmt->execute();

        $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($matches);

    } catch (Exception $e) {
        echo json_encode([
            'error' => true,
            'message' => $e->getMessage()
        ]);
    }

} else {
    echo json_encode([]);
}
?>
