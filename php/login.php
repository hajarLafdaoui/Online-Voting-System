<?php 
include('connection.php');

// Retrieve form data
$email = $_POST["email"];
$password = $_POST["password"];
$type = $_POST["type"];

// Prepare the statement to fetch the user's data
$stmt = $pdo->prepare("SELECT password, type FROM voting WHERE email = ?");
$stmt->execute([$email]);

// Check if the email exists in the database
if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch();

    // Verify the password
    if (password_verify($password, $user['password']) && $user['type'] === $type) {
        // Start a session and store user info
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['type'] = $type;

        // Redirect user based on their type
        if ($type === 'voter') {
            header("Location: voter_dashboard.php");
        } elseif ($type === 'group') {
            header("Location: admin_dashboard.php");
        }
        exit();
    } else {
        // Invalid credentials
        echo "Invalid email, password, or user type.";
    }
} else {
    // Email does not exist
    echo "Email does not exist.";
}
?>
