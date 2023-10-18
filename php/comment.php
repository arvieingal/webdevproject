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

if ( isset( $_POST[ 'addComment' ] ) ) {
    $comment = $_POST['comment'];
    $userId = $_SESSION[ 'userId' ];
    $announcementId = $_SESSION[ 'announcementId' ];

    $sql = "INSERT INTO comment (content, userId, announcementId) VALUES ('".$comment."','".$userId."','".$announcementId."')";
    $con->query( $sql ) or die ( $con->error );

    
    if ( $_SESSION[ 'role' ] == 'admin' ) {
        header( 'Location: admin.php' );
    } else {
        header( 'Location: user.php' );
    }

}
?>