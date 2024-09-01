<?php
include('connection.php');

// Retrieve form data
$email = $_POST["email"];
$password = $_POST["password"];
$confirmPassword = $_POST["confirmPassword"];
$address = $_POST["address"];
$type = $_POST["type"];

// Check if passwords match
if ($password !== $confirmPassword) {
    die("Passwords do not match");
}

// Check if the email already exists
$stmt = $pdo->prepare("SELECT * FROM voting WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo "Email already exists";
} else {
    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Default image path
   
    // Insert the new user into the database
    $stmt2 = $pdo->prepare("INSERT INTO voting (email, password, address, image_path, type) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$email, $hashedPassword, $address, $image_path, $type]);
    echo "Registration successful!";
}
?>
