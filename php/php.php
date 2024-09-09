<?php
include('connection.php');
session_start();

$form_type = $_POST['form_type'];

if ($form_type === 'sign_up') {
    $name = $_POST['name'];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $address = $_POST["address"];
    $type = $_POST["type"];

    if ($password !== $confirmPassword) {
        die("Passwords do not match");
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT 1 FROM groups WHERE email = ?");
    $stmt->execute([$email]);
    $stmt2 = $pdo->prepare("SELECT 1 FROM voters WHERE email = ?");
    $stmt2->execute([$email]);

    if ($stmt->fetchColumn() || $stmt2->fetchColumn()) {
        die("Email already exists");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $image_path = 'default.png';

    // Handle image upload
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $image_path = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];

        if (!move_uploaded_file($tmp_name, "../uploads/$image_path")) {
            die("Failed to move uploaded image.");
        }
    }

    if ($type === 'group') {
        // Insert into groups table
        $stmt = $pdo->prepare("INSERT INTO groups (name, email, password, address, image, type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $address, $image_path, $type]);

    } elseif ($type === 'voter') {
        // Insert into voters table
        $stmt = $pdo->prepare("INSERT INTO voters (name, email, password, address, image, type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword, $address, $image_path, $type]);
    }

    header("Location: ../index.html");
    exit;

} elseif ($form_type === 'sign_in') {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];

    // Fetch user data based on email and type
    if ($type === 'group') {
        $stmt = $pdo->prepare("SELECT * FROM groups WHERE email = ?");
    } elseif ($type === 'voter') {
        $stmt = $pdo->prepare("SELECT * FROM voters WHERE email = ?");
    }

    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            $_SESSION['user_id'] = $user['id']; // Store user ID in session

            // Redirect based on user type
            if ($type === 'voter') {
                header("Location: voter_home.php");
            } elseif ($type === 'group') {
                header("Location: group_home.php");
            }
            exit;
        } else {
            die("Invalid password.");
        }
    } else {
        die("Invalid email or type.");
    }

} else {
    die("Unknown form type");
}
?>
