<?php
include_once("../connection/connection.php");
$con = connection();

session_start();
if($_SESSION["Role"]==null){
    header("Location: ../login.html");
}
else{
    if($_SESSION["Role"] == "user"){

    }
    else{
        header("Location: ../login.html");
    }
}

$name = $_POST['name'];
$date = $_POST['date'];
$time = $_POST['time'];
$ampm = $_POST['ampm'];
$location = $_POST['location'];
$ootd = $_POST['ootd'];

$sql = "INSERT INTO activity (Name,Date,Time,Ampm,Location,Ootd) VALUES ('".$name."','".$date."','".$time."','".$ampm."','".$location."','".$ootd."')";
$con->query($sql) or die ($con->error);

echo header("Location: user.php");


?>