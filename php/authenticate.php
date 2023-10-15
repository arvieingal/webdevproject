<?php
session_start();

include_once( '../connection/connection.php' );
$con = connection();

$email = $_POST[ 'email' ];
$password = $_POST[ 'password' ];

$sql = "SELECT * FROM user WHERE Email = '".$email."' and Password = '".$password."'";
$result = $con->query( $sql );
$row = $result->fetch_assoc();

if ( $row[ 'email' ] == $email && $row[ 'password' ] == $password ) {
    if ( $row[ 'role' ] == 'admin' ) {
        header( 'Location: admin.php' );
    } else {
        header( 'Location: user.php' );

    }
    $_SESSION[ 'role' ] = $row[ 'role' ];
    $_SESSION[ 'userId' ] = $row[ 'userId' ];
} else {
    header( 'Location: ../index.html' );
}
closeConnection();

?>