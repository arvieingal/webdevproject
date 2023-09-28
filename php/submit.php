<?php

include_once("../connection/connection.php");
$con = connection();

session_start();
if($_SESSION["Role"]==null){
    header("Location: ../login.html");
}
else{
    if($_SESSION["Role"] == "admin" || $_SESSION["Role"] == "user"){

    }
    else{
        header("Location: ../login.html");
    }
}

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$email = $_POST['email'];
$password = $_POST['password'];

$sql = "insert into user(Firstname,Lastname,Age,Gender,Address,Email,Password) values ('".$firstname."','".$lastname."','".$age."','".$gender."','".$address."','".$email."','".$password."')";
$con->query($sql) or die ($con->error);

echo header("Location: login.html");
?>