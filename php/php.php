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
    die("Email already exists");
}

// Hash the password before storing it
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Default image path
$image_path = 'default.png';

// Check if the image was uploaded
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
    $image_path = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];

    // Check file upload errors
    if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        // Move the uploaded file to the permanent location
        if (move_uploaded_file($tmp_name, "../uploads/$image_path")) {
            echo "Image uploaded successfully!";
        } else {
            die("Failed to move uploaded image.");
        }
    } else {
        die("File upload error: " . $_FILES["image"]["error"]);
    }
} else {
    echo "No image uploaded or an error occurred with the image upload.";
}

// Insert the new user into the database
$stmt2 = $pdo->prepare("INSERT INTO voting (email, password, address, image_path, type) VALUES (?, ?, ?, ?, ?)");
$stmt2->execute([$email, $hashedPassword, $address, $image_path, $type]);
echo "Registration successful!";
?>
