<?php
session_start();

include_once("../connection/connection.php");
$con = connection();

$email = $_POST["email"];
$password = $_POST["password"];

$sql = "SELECT * FROM User WHERE Email = '".$email."' and Password = '".$password."'";
$result = $con->query($sql);
$row = $result->fetch_assoc();

if($row["Email"] == $email && $row["Password"] == $password){
    if($row["Role"]=="admin"){
        header("Location: admin.php");
    }
    else{
        header("Location: user.php");
    }
    $_SESSION["Role"] = $row["Role"];
}
else{
    header("Location: ../index.html");
}
closeConnection();

?>