<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    $stmt = $pdo->prepare("SELECT * FROM voting WHERE type = ?");
    $stmt->execute(['group']);
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="div">

        <div class="user">
            <img src="<?= '../uploads/' . $user['image_path'] ?>" alt="User Image" class='user-img'>
            <p>Name: <span><?= $user['name'] ?></span> </p>
            <p>Email: <span> <?= $user['email'] ?> </span></p>
            <p>Address: <span> <?= $user['address'] ?> </span></p>
            <p>Status: <span></span> </p>
        </div>

    <div class="groups">
        <?php 
        foreach ($groups as $group) {
            ?>
            <div class="group">
                <img src="<?= '../uploads/' . $group['image_path'] ?>" alt="User Image" class='user-img'>
                <p>Name: <span><?= $group['name'] ?></span> </p>
                <p>Email: <span> <?= $group['email'] ?> </span></p>
                <p>Address: <span> <?= $group['address'] ?> </span></p>
                <p>Number of voters: <span> </span></p>
                <button class='voter-btn'>Vote</button>
            </div>
            <?php         
            }
            ?> 
    </div>
    
</body>
</html>
