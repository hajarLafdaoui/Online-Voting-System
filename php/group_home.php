<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Home</title>
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

    // Fetch all groups from the groups table
    $stmt = $pdo->prepare("SELECT * FROM voters WHERE group_id = ?");
    $stmt->execute([$user['id']]);
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <div class="div">
        <div class="user">
            <img src="<?= '../uploads/' . htmlspecialchars($user['image'], ENT_QUOTES, 'UTF-8') ?>" alt="User Image" class='user-img'>
            <p>Name: <span><?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?></span></p>
            <p>Email: <span><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></span></p>
            <p>Address: <span><?= htmlspecialchars($user['address'], ENT_QUOTES, 'UTF-8') ?></span></p>
            
        </div>

        <div class="groups">
            <?php 
            foreach ($voters as $voter) {
                ?>
                <div class="group">
                    <img src="<?= '../uploads/' . htmlspecialchars($voter['image'], ENT_QUOTES, 'UTF-8') ?>" alt="Group Image" class='user-img'>
                    <p>Name: <span><?= htmlspecialchars($voter['name'], ENT_QUOTES, 'UTF-8') ?></span></p>

                    <p>Email: <span><?= htmlspecialchars($voter['email']) ?></span></p>

                    <p>address: <span><?= htmlspecialchars($voter['address']) ?></span></p>
                   
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
            var button = $(this);
            var userStatusSpan = $('#user-status');

            $.ajax({
                url: 'vote.php',
                type: 'POST',
                data: { group_id: groupId },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert(data.success);
                        userStatusSpan.text('Voted'); // Update status immediately
                        button.prop('disabled', true); // Disable button after voting
                        var groupVotersSpan = $('#group-' + groupId);
                        groupVotersSpan.text(parseInt(groupVotersSpan.text()) + 1); // Update group voters count
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