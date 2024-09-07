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
            <p>Status: <span><?= $user['status'] ?></span> </p>
        </div>

        <div class="groups">
            <?php 
            foreach ($groups as $group) {
                ?>
                <div class="group">
                    <img src="<?= '../uploads/' . $group['image_path'] ?>" alt="Group Image" class='user-img'>
                    <p>Name: <span><?= $group['name'] ?></span> </p>
                    <p>Email: <span> <?= $group['email'] ?> </span></p>
                    <p>Address: <span> <?= $group['address'] ?> </span></p>
                    <p>Number of voters: <span><?= $group['num_voters'] ?></span></p>
                    <button class='voter-btn' data-group-id="<?= $group['id'] ?>">Vote</button>
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
            var groupId = $(this).data('group-id'); 

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
