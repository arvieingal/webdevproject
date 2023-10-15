<?php

session_start();
include_once( '../connection/connection.php' );
$con = connection();

if ( $_SESSION[ 'role' ] == null ) {
    header( 'Location: ../index.html' );
} else {
    if ( $_SESSION[ 'role' ] == 'admin' || $_SESSION[ 'role' ] == 'user' ) {

    } else {
        header( 'Location: ../index.html' );
    }
}

if ( isset( $_POST[ 'register' ] ) ) {
    $firstname = $_POST[ 'firstname' ];
    $lastname = $_POST[ 'lastname' ];
    $gender = $_POST[ 'gender' ];
    $email = $_POST[ 'email' ];
    $password = $_POST[ 'password' ];
    $role = 'user';
    $status = 'active';

    $stmt = $con->prepare( 'INSERT INTO `user` (`firstName`, `lastName`, `gender`, `email`,`password`, `role`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?)' );
    $stmt->bind_param( 'sssssss', $firstname, $lastname, $gender, $email,  $password, $role, $status );

    if ( $stmt->execute() ) {
        $sql = "SELECT * FROM user WHERE email = '" . $email . "' and password = '" . $password . "'";
        $result = $con->query( $sql );
        $row = $result->fetch_assoc();

        $_SESSION[ 'userId' ] = $row[ 'userId' ];
        $_SESSION[ 'role' ] = $row[ 'role' ];

        header( 'Location: ../index.html' );
        exit;
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $con->close();

}

?>