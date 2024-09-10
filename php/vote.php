<?php
include('connection.php');
session_start();

if (isset($_POST['group_id'])) {
    $groupId = $_POST['group_id'];
    $userId = $_SESSION['user']['id'];

    // Check if the user has already voted
    $stmt = $pdo->prepare("SELECT status FROM voters WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['status'] === 'Voted') {
        echo json_encode(['error' => 'You have already voted.']);
        exit;
    }

    // Increment the number of voters for the group
    $stmt = $pdo->prepare("UPDATE groups SET num_voters = num_voters + 1 WHERE id = ?");
    $stmt->execute([$groupId]);

    // Update the voter's status
    $stmt2 = $pdo->prepare("UPDATE voters SET status = 'Voted' WHERE id = ?");
    $stmt2->execute([$userId]);

    // Update session data (optional, if you want immediate reflection in the session)
    $_SESSION['user']['status'] = 'Voted';

    echo json_encode(['success' => 'Your vote has been recorded']);
} else {
    echo json_encode(['error' => 'Invalid group ID']);
}
?>
