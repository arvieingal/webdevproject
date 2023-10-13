<?php
include_once( '../connection/connection.php' );
$con = connection();

session_start();
if ( $_SESSION[ 'Role' ] == null )
 {

    header( 'Location: ../index.html' );
} else {
    if ( $_SESSION[ 'Role' ] == 'user' )
 {
    } else {
        header( 'Location: ../index.html' );
    }

}

$userId = $_SESSION[ 'UserID' ];

$sql = "SELECT * FROM activity WHERE UserId = $userId ";
$show = $con->query( $sql ) or die( $con->error );

$data = [];
while( $row = $show->fetch_assoc() ) {
    $data[] = $row;
}

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset( $_POST[ 'edit' ] ) ) {
    $selectedId = $_POST[ 'selected_id' ];

    $select_sql = "SELECT * FROM activity WHERE Id = '$selectedId'";

    $result = $con->query( $select_sql );

    if ( $result->num_rows == 1 ) {
        $selectedRow = $result->fetch_assoc();
    } else {
        echo 'Record not fouond.';
    }
}

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset( $_POST[ 'update' ] ) ) {
    // Get the selected user's ID and updated information from the POST data.
        $selectedId = $_POST['selected_id'];
        $newName = $_POST['name'];
        $newDate = $_POST['date'];
        $newTime = $_POST['time'];
        $newLocation = $_POST['location'];
        $newOotd = $_POST['ootd'];
        $newStatus = $_POST['status'];
        $newRemarks = $_POST['remarks'];
    
        // Define an SQL query to update the user's information in the database.
    $update_sql = "UPDATE activity SET Name = '$newName', Date = '$newDate', Time = '$newTime',Location = '$newLocation', Ootd = '$newOotd', Status = '$newStatus', Remarks = '$newRemarks' WHERE Id = '$selectedId'";

    // Execute the SQL query to update the user's information.
        if ($con->query($update_sql)) {
            // Redirect to the 'update.php' page upon successful update.
            header("Location: user.php");
            exit();
        } else {
            // Display an error message if the update fails.
            echo "Error updating record: " . $con->error;
        }
    }


    if(isset($_POST['done_id']) && !empty($_POST['done_id'])){
        $done_id = $_POST['done_id'];

        $done_id = "UPDATE activity SET Status = 'Done' WHERE Id = '$done_id'";

        if($con->query($done_id)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Marking done the Activity: ".$con->error;
        }
    }

    if(isset($_POST['remark_id']) && !empty($_POST['remark_id'])){
        $remark_id = $_POST['remark_id'];
        $remark = $_POST['remark'];

        $sql = "UPDATE activity SET Remarks = '$remark' WHERE Id = '$remark_id'";

        if($con->query($sql)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Marking done the Activity: ".$con->error;
        }
    }

    if(isset($_POST['cancel_id']) && !empty($_POST['cancel_id'])){
        $cancel_id = $_POST['cancel_id'];

        $cancel_id = "UPDATE activity SET Status = 'Cancelled' WHERE Id = '$cancel_id'";

        if($con->query($cancel_id)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Cancelling the Activity: ".$con->error;
        }
    }



    if(isset($_POST['delete_id']) && !empty($_POST['delete_id'])){
        $delete_id = $_POST['delete_id'];

        $delete_sql = "DELETE FROM activity WHERE Id = '$delete_id'";

        if($con->query($delete_sql)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Deleting Activity: ".$con->error;
        }
    }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_submit'])) {
    $comment = $_POST['comment'];
    $UserID = $_SESSION[ 'UserID' ];

    // Assuming you have a 'comments' table with columns 'content' and 'createdAt'
    $sql = "INSERT INTO comments (content, createdAt, UserId) VALUES ('$comment', NOW(), '$UserID')";

    if ($con->query($sql) === TRUE) {
        header ("Location: user.php");
    } else {
        echo "Error: " . $sql . "<br>" . $con->error;
    }
}



?>
    <?php include_once("header.php") ?>
    <!-- Masthead-->
    <header class="masthead">
    <h1>Edit your Activity</h1>

<form action="" method="post">
    <label for="">Select Activity:</label>
    <select name="selected_id" id="selected_id">
        <?php foreach($data as $row): ?>
            <option value="<?php echo $row['Id'];?>"><?php echo $row['Name'];?></option>
        <?php endforeach;?>
    </select>
    <button type="submit" name="edit">Edit</button>
</form>

<?php if (isset($selectedRow)): ?>
<form method="post" action="">
    <!-- Hidden input to store the selected user's ID. -->
    <input required type = 'hidden' name = 'selected_id' value = "<?php echo $selectedRow['Id']; ?>">
    Activity Name:
    <input required type = 'text' name = 'name' value = "<?php echo $selectedRow['Name']; ?>"><br>
    Date:
    <input required type = 'text' name = 'date' value = "<?php echo $selectedRow['Date']; ?>"><br>
    Time:
    <input required type = 'text' name = 'time' value = "<?php echo $selectedRow['Time']; ?>"><br>
    Location:
    <input required type = 'text' name = 'location' value = "<?php echo $selectedRow['Location']; ?>"><br>
    OOTD:
    <input required type = 'text' name = 'ootd' value = "<?php echo $selectedRow['Ootd']; ?>"><br>

    <!-- Add new fields for cancel, done, and remarks -->
    <label for = 'status'>Status:</label>
    <select name = 'status' id = 'status'>
    <option value = ''></option>
    <option value = 'Done' <?php if ( $selectedRow[ 'Status' ] == 'Done' ) echo 'selected';
    ?>>Done</option>
    <option value = 'Dancel' <?php if ( $selectedRow[ 'Status' ] == 'Cancel' ) echo 'selected';
    ?>>Cancel</option>
    </select><br>
    Remarks:
    <textarea name = 'remarks'><?php echo $selectedRow[ 'Remarks' ];
    ?></textarea><br>

    <!-- Create a button to trigger the 'update' action. -->
    <button type = 'submit' name = 'update'>Update</button>
    </form>
    <?php endif;
    ?>

    <h1>Add Activity</h1>
    <form action = 'activity.php' method = 'post'>
    <label for = 'name'>Name:</label>
    <input type = 'text' id = 'name' name = 'name' required><br><br>

    <label for = 'date'>Date:</label>
    <input type = 'date' id = 'date' name = 'date'required><br><br>

    <label for = 'time'>time:</label>
    <input type = 'time' id = 'time' name = 'time' required><br><br>

    <label for = 'location'>Location:</label>
    <input type = 'text' id = 'location' name = 'location' required><br><br>

    <label for = 'ootd'>Ootd:</label>
    <input id = 'ootd' name = 'ootd' required></input><br><br>

    <input type = 'submit' name = 'addActivity' value = 'Add Activity'>
    </form>

    <h1>Your Activities</h1>
    <?php
    if ( $show->num_rows > 0 ) {
        // If there are records in the database, display the table
        ?>
        <table>
        <thead>
        <tr>
        <th>Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>OOTD</th>
        <th>Status</th>
        <th>Remarks</th>
        <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $data as $row ): ?>
        <tr>
        <td><?php echo $row[ 'Name' ];
        ?></td>
        <td><?php echo $row[ 'Date' ];
        ?></td>
        <td><?php echo $row[ 'Time' ];
        ?></td>
        <td><?php echo $row[ 'Location' ];
        ?></td>
        <td><?php echo $row[ 'Ootd' ];
        ?></td>
        <td><?php echo $row[ 'Status' ];
        ?></td>
        <td><?php echo $row[ 'Remarks' ];
        ?></td>
        <td>
        <form action = '' method = 'POST'>
        <input type = 'text' name = 'remark'>
        <button type = 'submit' name = 'remark_id' value = "<?php echo $row['Id']; ?>">Add Remarks</button>
        <button type = 'submit' name = 'done_id' value = "<?php echo $row['Id']; ?>">Done</button>
        <button type = 'submit' name = 'cancel_id' value = "<?php echo $row['Id']; ?>">Cancel</button>
        <button type = 'submit' name = 'delete_id' value = "<?php echo $row['Id']; ?>">Delete</button>
        </form>
        </td>
        </tr>
        <?php endforeach;
        ?>
        </tbody>
        </table>
        <?php
    } else {
        // If no records are found, display a message
        echo '<p>No records found.</p>';
    }
    ?>

    </header>
    <!-- About-->

    <!-- Portfolio-->
    <div id = 'portfolio'>
    <div class = 'container-fluid p-0'>
    <div class = 'row g-0'>
    <div class = 'col-lg-4 col-sm-6'>
    <a class = 'portfolio-box' href = '../assets/img/portfolio/fullsize/1.jpg' title = 'Project Name'>
    <img class = 'img-fluid' src = '../assets/img/portfolio/thumbnails/1.jpg' alt = '...' />
    <div class = 'portfolio-box-caption'>
    <div class = 'project-category text-white-50'>Category</div>
    <div class = 'project-name'>Project Name</div>
    </div>
    </a>
    </div>
    <div class = 'col-lg-4 col-sm-6'>
    <a class = 'portfolio-box' href = '../assets/img/portfolio/fullsize/2.jpg' title = 'Project Name'>
    <img class = 'img-fluid' src = '../assets/img/portfolio/thumbnails/2.jpg' alt = '...' />
    <div class = 'portfolio-box-caption'>
    <div class = 'project-category text-white-50'>Category</div>
    <div class = 'project-name'>Project Name</div>
    </div>
    </a>
    </div>
    <div class = 'col-lg-4 col-sm-6'>
    <a class = 'portfolio-box' href = '../assets/img/portfolio/fullsize/3.jpg' title = 'Project Name'>
    <img class = 'img-fluid' src = '../assets/img/portfolio/thumbnails/3.jpg' alt = '...' />
    <div class = 'portfolio-box-caption'>
    <div class = 'project-category text-white-50'>Category</div>
    <div class = 'project-name'>Project Name</div>
    </div>
    </a>
    </div>
    <div class = 'col-lg-4 col-sm-6'>
    <a class = 'portfolio-box' href = '../assets/img/portfolio/fullsize/4.jpg' title = 'Project Name'>
    <img class = 'img-fluid' src = '../assets/img/portfolio/thumbnails/4.jpg' alt = '...' />
    <div class = 'portfolio-box-caption'>
    <div class = 'project-category text-white-50'>Category</div>
    <div class = 'project-name'>Project Name</div>
    </div>
    </a>
    </div>
    <div class = 'col-lg-4 col-sm-6'>
    <a class = 'portfolio-box' href = '../assets/img/portfolio/fullsize/5.jpg' title = 'Project Name'>
    <img class = 'img-fluid' src = '../assets/img/portfolio/thumbnails/5.jpg' alt = '...' />
    <div class = 'portfolio-box-caption'>
    <div class = 'project-category text-white-50'>Category</div>
    <div class = 'project-name'>Project Name</div>
    </div>
    </a>
    </div>
    <div class = 'col-lg-4 col-sm-6'>
    <a class = 'portfolio-box' href = '../assets/img/portfolio/fullsize/6.jpg' title = 'Project Name'>
    <img class = 'img-fluid' src = '../assets/img/portfolio/thumbnails/6.jpg' alt = '...' />
    <div class = 'portfolio-box-caption p-3'>
    <div class = 'project-category text-white-50'>Category</div>
    <div class = 'project-name'>Project Name</div>
    </div>
    </a>
    </div>
    </div>
    </div>
    </div>
    <!-- Call to action-->
    <section class = 'page-section bg-dark text-white'>
    <div class = 'container px-4 px-lg-5 text-center'>
    <h2 class = 'mb-4'>Free Download at Start Bootstrap!</h2>
    <a class = 'btn btn-light btn-xl' href = 'https://startbootstrap.com/theme/creative/'>Download Now!</a>
    </div>
    </section>
    <!-- Contact-->
    <section class = 'page-section' id ="contact">
    <h1>Announcement</h1>
    <?php
    $sql = "SELECT announcement.*, user.Firstname, user.Role
    FROM announcement 
    LEFT JOIN user ON announcement.UserId = user.UserID";
    $show = $con->query( $sql ) or die( $con->error );

    $data = array();

    while( $row = $show->fetch_assoc() ) {
        $data[] = $row;
    }

    if ( $show->num_rows > 0 ) {
        // If there are records in the database, display the table
        ?>
        <?php foreach ( $data as $row ): ?>
        <h4>Title: <?php echo $row[ 'title' ];
        ?></h4>
        <p>From: <?php echo $row[ 'Role' ];
        ?> Date: <?php echo $row[ 'createdAt' ];
        ?></p>
        <p><?php echo $row[ 'content' ];
        ?></p>
        <h4>Comments: </h4>

        <?php
        $sql = "SELECT comments.*, user.Firstname
        FROM comments 
        LEFT JOIN user ON comments.UserId = user.UserID";
        ;
        $result = $con->query( $sql );

        if ( $result->num_rows > 0 ) {
            // Output data of each row
            while( $row = $result->fetch_assoc() ) {
                echo '<p>From: ' . $row[ 'Firstname' ] . ' Date: ' . $row[ 'createdAt' ] . '</p>';
                echo '<p>' . $row[ 'content' ] . '</p>';
            }
        } else {
            echo 'No comments yet.';
        }
        ?>
        <form action = '' method = 'POST'>
        <p>Comments: <input type = 'text' name = 'comment'></p>
        <input type = 'submit' name = 'comment_submit'>
        </form>
        <?php endforeach;
        ?>
        <?php
    } else {
        // If no records are found, display a message
        echo '<p>No records found.</p>';
    }
    $con->close();
    ?>
    </section>

    <?php include_once( 'footer.php' ) ?>
