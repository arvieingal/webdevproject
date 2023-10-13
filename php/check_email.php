<?php
include_once( '../connection/connection.php' );
$con = connection();

if ( isset( $_POST[ 'email' ] ) ) {
    $email = $_POST[ 'email' ];

    // Check if the email exists in the database
    $sql = "SELECT * FROM user WHERE Email = '$email'";
    $result = $con->query( $sql );

    if ( $result->num_rows > 0 ) {
        echo "<span style='color: red;'>Email already exists. Please choose a different email.</span>";
    } else {
        echo "<span style='color: green;'>Email available!</span>";
    }
}
$con->close();
?>
