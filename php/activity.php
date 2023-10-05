<?php
session_start();
include_once("../connection/connection.php");
$con = connection();

if($_SESSION["Role"]==null){
    header("Location: ../index.html");
}
else{
    if($_SESSION["Role"] == "user"){

    }
    else{
        header("Location: ../index.html");
    }
}

echo $_SESSION['UserID'];

if(isset($_POST['addActivity'])){
$name = $_POST['name'];
$date = $_POST['date'];
$time = $_POST['time'];
$ampm = $_POST['ampm'];
$location = $_POST['location'];
$ootd = $_POST['ootd'];
$userID = $_SESSION['UserID'];

$sql = "INSERT INTO activity (Name,Date,Time,Ampm,Location,Ootd,UserId) VALUES ('".$name."','".$date."','".$time."','".$ampm."','".$location."','".$ootd."','".$userID."')";
$con->query($sql) or die ($con->error);

echo header("Location: user.php");

}

// $sql = "SELECT * FROM activity WHERE UserId = " .$userID. "";
// $result = $con->query($sql);
// $row = $result->fetch_assoc();





?>