<?php
session_start();
include_once( '../connection/connection.php' );
$con = connection();

if ( $_SESSION[ 'Role' ] == null ) {
    header( 'Location: ../index.html' );
} else {
    if ( $_SESSION[ 'Role' ] == 'user' ) {

    } else {
        header( 'Location: ../index.html' );
    }
}

if ( isset( $_POST[ 'addActivity' ] ) ) {
    $name = $_POST[ 'name' ];
    $date = $_POST[ 'date' ];
    $time = $_POST[ 'time' ];
    $location = $_POST[ 'location' ];
    $ootd = $_POST[ 'ootd' ];
    $remark = $_POST[ 'remark' ];
    $userID = $_SESSION[ 'UserID' ];

    $sql = "INSERT INTO activity (Name,Date,Time,Location,Ootd,Remark,UserId) VALUES ('".$name."','".$date."','".$time."','".$location."','".$ootd."','".$remark."','".$userID."')";
    $con->query( $sql ) or die ( $con->error );

    echo header( 'Location: user.php' );

}

// $sql = 'SELECT * FROM activity WHERE UserId = ' .$userID. '';
// $result = $con->query( $sql );
// $row = $result->fetch_assoc();

?>