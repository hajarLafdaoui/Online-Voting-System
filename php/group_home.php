<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Home</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php
    include('connection.php');
    session_start();

    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        echo "No user data available.";
        exit;
    }

    // Ensure the user is a group
    if ($user['type'] !== 'group') {
        echo "Access denied.";
        exit;
    }

    // Get the logged-in user's group ID
    $group_id = $user['group_id'];

    // Fetch group information
    $stmt = $pdo->prepare("SELECT * FROM groups WHERE id = ?");
    $stmt->execute([$group_id]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: Print fetched group data
    echo "<h3>Group Data:</h3>";
    echo "<pre>";
    print_r($group);
    echo "</pre>";

    if (!$group) {
        echo "Group not found.";
        exit;
    }

    // Fetch voters for the group
    $stmt2 = $pdo->prepare("SELECT * FROM voting WHERE group_id = ? AND status = 'Voted'");
    $stmt2->execute([$group_id]);

    // Check if the query executed successfully
    if ($stmt2->errorCode() !== '00000') {
        echo "SQL Error: " . implode(", ", $stmt2->errorInfo());
    }

    $voters = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Debugging: Print fetched voters data
    echo "<h3>Voters Data:</h3>";
    echo "<pre>";
    print_r($voters);
    echo "</pre>";
    ?>
    <div class="div">
        <!-- Display group information -->
        <div class="group">
            <h1>Group Information</h1>
            <img src="<?= '../uploads/' . $group['image_path'] ?>" alt="Group Image" class='group-img'>
            <p>Name: <span><?= $group['name'] ?></span></p>
            <p>Description: <span><?= $group['description'] ?></span></p>
            <p>Number of Voters: <span><?= $group['num_voters'] ?></span></p>
        </div>

        <!-- Display voters -->
        <div class="voters">
            <h2>Voters for Group ID: <?= $group_id ?></h2>
            <?php 
            if ($voters) {
                foreach ($voters as $voter) {
                    ?>
                    <div class="voter">
                        <img src="<?= '../uploads/' . $voter['image_path'] ?>" alt="Voter Image" class='voter-img'>
                        <p>Name: <span><?= $voter['name'] ?></span></p>
                        <p>Email: <span><?= $voter['email'] ?></span></p>
                        <p>Address: <span><?= $voter['address'] ?></span></p>
                        <p>Status: <span><?= $voter['status'] ?></span></p>
                    </div>
                    <?php         
                }
            } else {
                echo "<p>No voters have voted for this group.</p>";
            }
            ?> 
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
