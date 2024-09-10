<?php
include('connection.php');
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode(array('error' => 'User not logged in.'));
    exit;
}

$user = $_SESSION['user'];
$groupId = $_POST['group_id'];

// Check if the user has already voted
if ($user['status'] == 'Voted') {
    echo json_encode(array('error' => 'You have already voted.'));
    exit;
}

// If the user hasn't voted yet, proceed to vote
$stmt = $pdo->prepare("UPDATE groups SET num_voters = num_voters + 1 WHERE id = ?");
$stmt->execute([$groupId]);

// Update the voter's status and group_id
$stmt = $pdo->prepare("UPDATE voters SET status = 'Voted', group_id = ? WHERE id = ?");
$stmt->execute([$groupId, $user['id']]);

// Update session data to reflect the new status
$_SESSION['user']['status'] = 'Voted';

echo json_encode(array('success' => 'Vote successful.'));
?>
