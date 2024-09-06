<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <?php
    include('connection.php');
    session_start();

    // Check if the session variable 'user' is set
    if (isset($_SESSION['user'])) {
        // Retrieve user data from session
        $user = $_SESSION['user'];
        var_dump($user); // Display the user data for debugging
    } else {
        echo "No user data available.";
    }
    ?>
</body>
</html>
