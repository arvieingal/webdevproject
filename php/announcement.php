<?php
session_start();
if ( $_SESSION[ 'role' ] == null ) {
    header( 'Location: ../index.html' );
} else {
    if ( $_SESSION[ 'role' ] == 'admin' ) {

    } else {
        header( 'Location: ../index.html' );
    }
}

include_once( '../connection/connection.php' );
$con = connection();

// Check if the form is submitted
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    // Retrieve the announcement text from the form
    $title = $_POST[ 'title' ];
    $content = $_POST[ 'content' ];

    // Perform validation if needed

    // Insert the announcement into the database
    $insertQuery = "INSERT INTO announcement (title,content) VALUES ('$title','$content')";
    if ( $con->query( $insertQuery ) === TRUE ) {
        echo 'Announcement inserted successfully!';
    } else {
        echo 'Error: ' . $con->error;
    }
}
?>