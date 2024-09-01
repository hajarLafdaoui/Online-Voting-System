<?php
include('connection.php');

$email = $_POST["email"];
$password = $_POST["password"];
$confirmPassword = $_POST["confirmPassword"];
$address = $_POST["address"];
$type = $_POST["type"];


$image_path = $_FILES["image"]["name"];
$tmp_name = $_FILES["image"]["tmp_name"];


if($password !== $confirmPassword){
    die ("pasword do not match").
}

$stmt = $pdo->prepare("SELECT * FROM voting WHERE email = ? AND password= ?");
$stmt->execute([$email, $password]);
if ($stmt->rowCount() > 0){
    echo "email already exists";
}
else{
 
//  is used to check if the uploaded file was successfully moved from its temporary location to a permanent directory on your server.
        if(move_uploaded_file($tmp_name, "../uploads/$image_path") ){
            $stmt2 = $pdo->prepare("INSERT INTO voting (email, password, confirmPassword, address,image_path, type) VALUES (?,?,?,?,?,?)");
            $stmt2->execute([$email, $password, $confirmPassword, $address, $image_path, $type]);
        }      
        

    };