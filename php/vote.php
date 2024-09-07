<?php
include('connection.php');
session_start();

if (isset($_POST['group_id']) && isset($_SESSION['user'])) {
    $groupId = $_POST['group_id'];
    $user = $_SESSION['user'];

    if ($user['status'] === 'Voted') {
        echo json_encode(['error' => 'You have already voted!']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE voting SET num_voters = num_voters + 1 WHERE id = ?");
    $stmt->execute([$groupId]);

    $stmt = $pdo->prepare("UPDATE voting   SET status = 'Voted' WHERE id = ?");
    $stmt->execute([$user['id']]);

    $_SESSION['user']['status'] = 'Voted';

    echo json_encode(['success' => 'Vote recorded!']);
} else {
    echo json_encode(['error' => 'No user logged in.']);
}
?>
<!-- JSON.parse in JavaScript
Purpose: Converts a JSON string into a JavaScript object.

json_encode in PHP
Purpose: Converts a PHP array or object into a JSON string. -->