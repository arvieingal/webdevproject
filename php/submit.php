<?php

session_start();
include_once("../connection/connection.php");
$con = connection();

if($_SESSION["Role"]==null){
    header("Location: ../index.html");
}
else{
    if($_SESSION["Role"] == "admin" || $_SESSION["Role"] == "user"){

    }
    else{
        header("Location: ../index.html");
    }
}


if(isset($_POST['register'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = 'user';
    $status= ' Active';

    $stmt = $con->prepare("INSERT INTO `user` (`Firstname`, `Lastname`, `Age`, `Address`, `Gender`, `Email`,`Password`, `Role`, `Status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstname, $lastname, $age, $address, $gender, $email,  $password, $role, $status);


    if ($stmt->execute()) {
        $sql = "SELECT * FROM user WHERE Email = '" . $email . "' and Password = '" . $password . "'";
        $result = $con->query($sql);
        $row = $result->fetch_assoc();

        echo "<script language='javascript'>
        alert('Registered Successfully!');
        </script>";

        $_SESSION['UserID'] = $row['UserID'];
        $_SESSION['Role'] = $row['Role'];

        header('Location: ../index.html');
        exit;
    } else {
        echo 'Error: ' . $stmt->error;
    }

$stmt->close();
$con->close();


}



?>