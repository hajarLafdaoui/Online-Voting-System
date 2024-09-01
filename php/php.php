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
$stmt = $pdo->prepare("SELECT 1 FROM voting WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetchColumn()) {
    die("Email already exists");
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Default image path
$image_path = 'default.png';

// image upload
if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
    $image_path = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    
    if (!move_uploaded_file($tmp_name, "../uploads/$image_path")) {
        die("Failed to move uploaded image.");
    }
}

// Insert the new user into the database
$stmt2 = $pdo->prepare("INSERT INTO voting (email, password, address, image_path, type) VALUES (?, ?, ?, ?, ?)");
$stmt2->execute([$email, $hashedPassword, $address, $image_path, $type]);

echo "Registration successful!";
?>
