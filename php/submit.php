<?php

session_start();
include_once("../connection/connection.php");
$con = connection();

if ($_SESSION["Role"] == null) {
    header("Location: ../index.html");
} else {
    if ($_SESSION["Role"] == "admin" || $_SESSION["Role"] == "user") {

    } else {
        header("Location: ../index.html");
    }
}

if (isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];

    // Check if the entered firstname, lastname, and email already exist in the database
    $check_query = "SELECT * FROM user WHERE Firstname = ? AND Lastname = ? AND Email = ?";
    $stmt_check = $con->prepare($check_query);
    $stmt_check->bind_param("sss", $firstname, $lastname, $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Duplicate entry found, send an error response to the client-side JavaScript
        echo "Error: The data you just input is already in use";
        ?><a href="../index.html"><br>Go Back</a><?php
        exit;

    }

    // Continue with inserting the data into the database since there are no duplicates
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $role = 'user';
    $status = 'Active';

    $stmt = $con->prepare("INSERT INTO `user` (`Firstname`, `Lastname`, `Age`, `Address`, `Gender`, `Email`,`Password`, `Role`, `Status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstname, $lastname, $age, $address, $gender, $email,  $password, $role, $status);

    if ($stmt->execute()) {
        // Data inserted successfully, send a success response to the client-side JavaScript
        echo "success";
    } else {
        // Error occurred during insertion, send an error response to the client-side JavaScript
        echo "error";
    }

    $stmt->close();
    $stmt_check->close();
    $con->close();
}
?>