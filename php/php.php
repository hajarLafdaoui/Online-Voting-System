<?php
include('connection.php');

$form_type = $_POST['form_type'];

if ($form_type === 'sign_up') {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $address = $_POST["address"];
    $type = $_POST["type"];

    if ($password !== $confirmPassword) {
        die("Passwords do not match");
    }

    $stmt = $pdo->prepare("SELECT 1 FROM voting WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetchColumn()) {
        die("Email already exists");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $image_path = 'default.png';

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $image_path = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];

        if (!move_uploaded_file($tmp_name, "../uploads/$image_path")) {
            die("Failed to move uploaded image.");
        }
    }

    $stmt2 = $pdo->prepare("INSERT INTO voting (email, password, address, image_path, type) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$email, $hashedPassword, $address, $image_path, $type]);

} elseif ($form_type === 'sign_in') {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];

    $stmt = $pdo->prepare("SELECT password FROM voting WHERE email = ? AND type = ?");
    $stmt->execute([$email, $type]);
    $storedPassword = $stmt->fetchColumn();

    if (password_verify($password, $storedPassword)) {
        echo "Password verified!<br>";
        if ($type === 'voter') {
            header("Location: voter_home.php");
        } elseif ($type === 'group') {
            header("Location: group_home.php");
        }
        exit;
    } else {
        die("Invalid email, password, or type.");
    }
} else {
    die("Unknown form type");
}
?>
