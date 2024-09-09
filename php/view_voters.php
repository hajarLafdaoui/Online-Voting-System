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

    // Fetch the group associated with the logged-in user
    $stmt = $pdo->prepare("SELECT g.* FROM groups g
                           JOIN user_groups ug ON g.id = ug.group_id
                           WHERE ug.user_id = ?");
    $stmt->execute([$user['id']]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$group) {
        echo "No group found for the logged-in user.";
        exit;
    }

    // Fetch all voters for the selected group
    $stmt = $pdo->prepare("SELECT * FROM voting WHERE group_id = ?");
    $stmt->execute([$group['id']]);
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="div">
        <div class="user">
            <img src="<?= '../uploads/' . $user['image_path'] ?>" alt="User Image" class='user-img'>
            <p>Name: <span><?= $user['name'] ?></span> </p>
            <p>Email: <span> <?= $user['email'] ?> </span></p>
            <p>Address: <span> <?= $user['address'] ?> </span></p>
            <p>Status: <span><?= $user['status'] ?></span> </p>
        </div>

        <div class="group">
            <h2>Group Information</h2>
            <img src="<?= '../uploads/' . $group['image_path'] ?>" alt="Group Image" class='user-img'>
            <p>Name: <span><?= $group['name'] ?></span> </p>
            <p>Description: <span> <?= $group['description'] ?> </span></p>
            <p>Number of voters: <span><?= $group['num_voters'] ?></span></p>
        </div>

        <div class="voters">
            <h2>Voters for Group ID: <?= $group['id'] ?></h2>
            <?php 
            if ($voters) {
                foreach ($voters as $voter) {
                    ?>
                    <div class="voter">
                        <img src="<?= '../uploads/' . $voter['image_path'] ?>" alt="Voter Image" class='user-img'>
                        <p>Name: <span><?= $voter['name'] ?></span> </p>
                        <p>Email: <span> <?= $voter['email'] ?> </span></p>
                        <p>Address: <span> <?= $voter['address'] ?> </span></p>
                        <p>Status: <span><?= $voter['status'] ?></span> </p>
                    </div>
                    <?php         
                }
            } else {
                echo "<p>No voters found for this group.</p>";
            }
            ?> 
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
