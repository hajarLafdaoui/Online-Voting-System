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
    $stmt->execute(['voter']);
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="div">
        <div class="user">
            <img src="<?= '../uploads/' . $user['image_path'] ?>" alt="User Image" class='user-img'>
            <p>Name: <span><?= $user['name'] ?></span> </p>
            <p>Email: <span> <?= $user['email'] ?> </span></p>
            <p>Address: <span> <?= $user['address'] ?> </span></p>
            <p>number of voters: <span> <?= $user['num_voters'] ?></span> </p>
        </div>

        <div class="groups">
            <?php 
            foreach ($voters as $voter) {
                ?>
                <div class="group">
                    <img src="<?= '../uploads/' . $voter['image_path'] ?>" alt="Group Image" class='user-img'>
                    <p>Name: <span><?= $voter['name'] ?></span> </p>
                    <p>Email: <span> <?= $voter['email'] ?> </span></p>
                    <p>Address: <span> <?= $voter['address'] ?> </span></p>
                </div>
                <?php         
            }
            ?> 
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.voter-btn').on('click', function() {
            var groupId = $(this).data('group-id'); // Get the group ID

            $.ajax({
                url: 'vote.php',
                type: 'POST',
                data: { group_id: groupId },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert(data.success);
                        location.reload();
                    } else {
                        alert(data.error);
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
