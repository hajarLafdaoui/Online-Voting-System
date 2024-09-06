
<?php
include('connection.php');

<<<<<<< HEAD
$form_type = $_POST['form_type'];

if ($form_type === 'sign_up') {
=======
// Check which form was submitted
$form_type = $_POST['form_type'];

if ($form_type === 'sign_up') {
    // Handle sign-up logic
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $address = $_POST["address"];
    $type = $_POST["type"];

<<<<<<< HEAD
=======
    // Check if passwords match
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
    if ($password !== $confirmPassword) {
        die("Passwords do not match");
    }

<<<<<<< HEAD
=======
    // Check if the email already exists
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
    $stmt = $pdo->prepare("SELECT 1 FROM voting WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetchColumn()) {
        die("Email already exists");
    }

<<<<<<< HEAD
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $image_path = 'default.png';

=======
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Default image path
    $image_path = 'default.png';

    // Handle image upload
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $image_path = $_FILES["image"]["name"];
        $tmp_name = $_FILES["image"]["tmp_name"];

        if (!move_uploaded_file($tmp_name, "../uploads/$image_path")) {
            die("Failed to move uploaded image.");
        }
    }

<<<<<<< HEAD
    $stmt2 = $pdo->prepare("INSERT INTO voting (email, password, address, image_path, type) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$email, $hashedPassword, $address, $image_path, $type]);

} elseif ($form_type === 'sign_in') {
=======
    // Insert the new user into the database
    $stmt2 = $pdo->prepare("INSERT INTO voting (email, password, address, image_path, type) VALUES (?, ?, ?, ?, ?)");
    $stmt2->execute([$email, $hashedPassword, $address, $image_path, $type]);

    echo "Registration successful!";
} elseif ($form_type === 'sign_in') {
    // Handle sign-in logic
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];

<<<<<<< HEAD
=======
    // Retrieve the stored password hash and type
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
    $stmt = $pdo->prepare("SELECT password FROM voting WHERE email = ? AND type = ?");
    $stmt->execute([$email, $type]);
    $storedPassword = $stmt->fetchColumn();

<<<<<<< HEAD
    if (password_verify($password, $storedPassword)) {
        echo "Password verified!<br>";
=======
    if ($storedPassword && password_verify($password, $storedPassword)) {
        echo "Login successful!";
        // Redirect to the appropriate home page based on type
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
        if ($type === 'voter') {
            header("Location: voter_home.php");
        } elseif ($type === 'group') {
            header("Location: group_home.php");
        }
        exit;
    } else {
<<<<<<< HEAD
        die("Invalid email, password, or type.");
=======
        die("Invalid email, password, or type");
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
    }
} else {
    die("Unknown form type");
}
<<<<<<< HEAD
?>
=======
>>>>>>> 8caffe4a15af089561cdccb794fada7e3c5f5a99
