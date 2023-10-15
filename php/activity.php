<?php
session_start();
include_once( '../connection/connection.php' );
$con = connection();

if ( $_SESSION[ 'role' ] == null ) {
    header( 'Location: ../index.html' );
} else {
    if ( $_SESSION[ 'role' ] == 'user' ) {

    } else {
        header( 'Location: ../index.html' );
    }
}

if ( isset( $_POST[ 'addActivity' ] ) ) {
    $name = $_POST[ 'activityname' ];
    $date = $_POST[ 'date' ];
    $time = $_POST[ 'time' ];
    $location = $_POST[ 'location' ];
    $ootd = $_POST[ 'ootd' ];
    $remark = $_POST[ 'remark' ];
    $userId = $_SESSION[ 'userId' ];

    $sql = "INSERT INTO activity (activityName,date,time,location,ootd,remarks,userId) VALUES ('".$name."','".$date."','".$time."','".$location."','".$ootd."','".$remark."','".$userId."')";
    $con->query( $sql ) or die ( $con->error );

    echo header( 'Location: user.php' );

}

// $sql = 'SELECT * FROM activity WHERE UserId = ' .$userID. '';
// $result = $con->query( $sql );
// $row = $result->fetch_assoc();

?>