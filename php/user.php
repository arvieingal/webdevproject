<?php
include_once( '../connection/connection.php' );
$con = connection();

session_start();
if ( $_SESSION[ 'role' ] == null )
 {

    header( 'Location: ../index.html' );
} else {
    if ( $_SESSION[ 'role' ] == 'user' )
 {
    } else {
        header( 'Location: ../index.html' );
    }

}

$userId = $_SESSION[ 'userId' ];

$sql = "SELECT * FROM activity WHERE UserId = $userId ";
$show = $con->query( $sql ) or die( $con->error );

$data = [];
while( $row = $show->fetch_assoc() ) {
    $data[] = $row;
}

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' && isset( $_POST[ 'edit' ] ) ) {
    $selectedId = $_POST[ 'selected_id' ];

    $select_sql = "SELECT * FROM activity WHERE activityId = '$selectedId'";

    $result = $con->query( $select_sql );

    if ( $result->num_rows == 1 ) {
        $selectedRow = $result->fetch_assoc();
    } else {
        echo 'Record not found.';
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
    $update_sql = "UPDATE activity SET activityName = '$newName', date = '$newDate', time = '$newTime',location = '$newLocation', ootd = '$newOotd', status = '$newStatus', remarks = '$newRemarks' WHERE activityId = '$selectedId'";

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

        $done_id = "UPDATE activity SET Status = 'Done' WHERE activityId = '$done_id'";

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

        $sql = "UPDATE activity SET Remarks = '$remark' WHERE activityId = '$remark_id'";

        if($con->query($sql)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Marking done the Activity: ".$con->error;
        }
    }

    if(isset($_POST['cancel_id']) && !empty($_POST['cancel_id'])){
        $cancel_id = $_POST['cancel_id'];

        $cancel_id = "UPDATE activity SET Status = 'Cancelled' WHERE activityId = '$cancel_id'";

        if($con->query($cancel_id)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Cancelling the Activity: ".$con->error;
        }
    }



    if(isset($_POST['delete_id']) && !empty($_POST['delete_id'])){
        $delete_id = $_POST['delete_id'];

        $delete_sql = "DELETE FROM activity WHERE activityId = '$delete_id'";

        if($con->query($delete_sql)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Deleting Activity: ".$con->error;
        }
    }


?>
    <?php include_once("header.php") ?>
    <style>
        /* CSS for the modal */
        .modal {
        color: black;
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
        padding-top: 60px;
        }

        .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        }

        .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        }

        .close:hover,
        .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
        }
    </style>
    <!-- Call to action-->
    <section class = 'page-section bg-dark text-white'>
    <h1>Edit your Activity</h1>

<form action="" method="post">
    <label for="">Select Activity:</label>
    <select name="selected_id" id="selected_id">
        <?php foreach($data as $row): ?>
            <option value="<?php echo $row['activityId'];?>"><?php echo $row['activityName'];?></option>
        <?php endforeach;?>
    </select>
    <button type="submit" name="edit">Edit</button>
</form>

<?php if (isset($selectedRow)): ?>
<form method="post" action="">
    <!-- Hidden input to store the selected user's ID. -->
    <input required type = 'hidden' name = 'selected_id' value = "<?php echo $selectedRow['activityId']; ?>">
    Activity Name:
    <input required type = 'text' name = 'name' value = "<?php echo $selectedRow['activityName']; ?>"><br>
    Date:
    <input required type = 'text' name = 'date' value = "<?php echo $selectedRow['date']; ?>"><br>
    Time:
    <input required type = 'text' name = 'time' value = "<?php echo $selectedRow['time']; ?>"><br>
    Location:
    <input required type = 'text' name = 'location' value = "<?php echo $selectedRow['location']; ?>"><br>
    OOTD:
    <input required type = 'text' name = 'ootd' value = "<?php echo $selectedRow['ootd']; ?>"><br>

    <!-- Add new fields for cancel, done, and remarks -->
    <label for = 'status'>Status:</label>
    <select name = 'status' id = 'status'>
    <option value = ''></option>
    <option value = 'Done' <?php if ( $selectedRow[ 'status' ] == 'Done' ) echo 'selected';
    ?>>Done</option>
    <option value = 'Dancel' <?php if ( $selectedRow[ 'status' ] == 'Cancel' ) echo 'selected';
    ?>>Cancel</option>
    </select><br>
    Remarks:
    <textarea name = 'remarks'><?php echo $selectedRow[ 'remarks' ];
    ?></textarea><br>

    <!-- Create a button to trigger the 'update' action. -->
    <button type = 'submit' name = 'update'>Update</button>
    </form>
    <?php endif;
    ?>

    <!-- Button to trigger the modal -->
    <button id="addActivityBtn">Add Activity</button>

    <!-- The Modal -->
    <div id="addActivityModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <!-- Your form content goes here -->
        <form action = 'activity.php' method = 'post'>
        <label for = 'name'>Activity Name:</label>
        <input type = 'text' id = 'name' name = 'activityname' required><br><br>

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
    </div>
    </div>

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
        <td><?php echo $row[ 'activityName' ];
        ?></td>
        <td><?php echo $row[ 'date' ];
        ?></td>
        <td><?php echo $row[ 'time' ];
        ?></td>
        <td><?php echo $row[ 'location' ];
        ?></td>
        <td><?php echo $row[ 'ootd' ];
        ?></td>
        <td><?php echo $row[ 'status' ];
        ?></td>
        <td><?php echo $row[ 'remarks' ];
        ?></td>
        <td>
        <form action = '' method = 'POST'>
        <input type = 'text' name = 'remark'>
        <button type = 'submit' name = 'remark_id' value = "<?php echo $row['activityId']; ?>">Add Remarks</button>
        <button type = 'submit' name = 'done_id' value = "<?php echo $row['activityId']; ?>">Done</button>
        <button type = 'submit' name = 'cancel_id' value = "<?php echo $row['activityId']; ?>">Cancel</button>
        <button type = 'submit' name = 'delete_id' value = "<?php echo $row['activityId']; ?>">Delete</button>
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
    </section>




    <!-- Contact-->
    <section class = 'page-section' id ="contact">
    <h1>Announcement</h1>
    <?php
    $sql = "SELECT announcement.*, user.role
    FROM announcement 
    LEFT JOIN user ON announcement.userId = user.userId";
    $show = $con->query( $sql ) or die( $con->error );

    $data = array();

    while( $row = $show->fetch_assoc() ) {
        $data[] = $row;

        $_SESSION[ 'announcementId' ] = $row[ 'announcementId' ];
    }

    

    if ( $show->num_rows > 0 ) {
        // If there are records in the database, display the table
        ?>
        <?php foreach ( $data as $row ): ?>
            <h4>Title: <?php echo $row[ 'title' ];?></h4>
            <p>From: <?php echo $row['role'];?> Date: <?php echo $row[ 'createdAt' ];?></p>
            <p><?php echo $row[ 'content' ];?></p>
            <h4>Comments: </h4>

        <?php
        $sql = "SELECT comment.*, user.firstName
        FROM comment 
        LEFT JOIN user ON comment.userId = user.userId";
        $result = $con->query( $sql );

        if ( $result->num_rows > 0 ) {
            // Output data of each row
            while( $row = $result->fetch_assoc() ) {
                echo '<p>From: ' . $row[ 'firstName' ] . ' Date: ' . $row[ 'createdAt' ] . '</p>';
                echo '<p>' . $row[ 'content' ] . '</p>';
            }
        } else {
            echo 'No comments yet.';
        }
        ?>
        <form action = "comment.php" method = "POST">
            <p>Comments: <input type = 'text' name = 'comment'></p>
            <input type = 'submit' name = 'addComment' value = 'Enter'>
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

    <!-- Contact-->
  <section class="page-section" id="contact">
    <div class="container px-4 px-lg-5">
      <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-lg-8 col-xl-6 text-center">
          <h2 class="mt-0">Let's Get In Touch!</h2>
          <hr class="divider" />
          <p class="text-muted mb-5">Ready to start your next project with us? Send us a messages and we will get back
            to you as soon as possible!</p>
        </div>
      </div>
      <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
        <div class="col-lg-6">
          <!-- * * * * * * * * * * * * * * *-->
          <!-- * * SB Forms Contact Form * *-->
          <!-- * * * * * * * * * * * * * * *-->
          <!-- This form is pre-integrated with SB Forms.-->
          <!-- To make this form functional, sign up at-->
          <!-- https://startbootstrap.com/solution/contact-forms-->
          <!-- to get an API token!-->
          <form id="contactForm" data-sb-form-api-token="API_TOKEN">
            <!-- Name input-->
            <div class="form-floating mb-3">
              <input class="form-control" id="name" type="tel" placeholder="Enter your name..."
                data-sb-validations="required" />
              <label for="name">Full name</label>
              <div class="invalid-feedback" data-sb-feedback="name:required">A name is required.</div>
            </div>
            <!-- Email address input-->
            <div class="form-floating mb-3">
              <input class="form-control" id="email" type="tel" placeholder="name@example.com"
                data-sb-validations="required,email" />
              <label for="email">Email address</label>
              <div class="invalid-feedback" data-sb-feedback="email:required">An email is required.</div>
              <div class="invalid-feedback" data-sb-feedback="email:email">Email is not valid.</div>
            </div>
            <!-- Phone number input-->
            <div class="form-floating mb-3">
              <input class="form-control" id="phone" type="tel" placeholder="(123) 456-7890"
                data-sb-validations="required" />
              <label for="phone">Phone number</label>
              <div class="invalid-feedback" data-sb-feedback="phone:required">A phone number is required.</div>
            </div>
            <!-- Message input-->
            <div class="form-floating mb-3">
              <textarea class="form-control" id="message" type="text" placeholder="Enter your message here..."
                style="height: 10rem" data-sb-validations="required"></textarea>
              <label for="message">Message</label>
              <div class="invalid-feedback" data-sb-feedback="message:required">A message is required.</div>
            </div>
            <!-- Submit success message-->
            <!---->
            <!-- This is what your users will see when the form-->
            <!-- has successfully submitted-->
            <div class="d-none" id="submitSuccessMessage">
              <div class="text-center mb-3">
                <div class="fw-bolder">Form submission successful!</div>
                To activate this form, sign up at
                <br />
                <a
                  href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
              </div>
            </div>
            <!-- Submit error message-->
            <!---->
            <!-- This is what your users will see when there is-->
            <!-- an error submitting the form-->
            <div class="d-none" id="submitErrorMessage">
              <div class="text-center text-danger mb-3">Error sending message!</div>
            </div>
            <!-- Submit Button-->
            <div class="d-grid"><button class="btn btn-primary btn-xl disabled" id="submitButton"
                type="submit">Submit</button></div>
          </form>
        </div>
      </div>
      <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-lg-4 text-center mb-5 mb-lg-0">
          <i class="bi-phone fs-2 mb-3 text-muted"></i>
          <div>+1 (555) 123-4567</div>
        </div>
      </div>
    </div>
  </section>

    <?php include_once( 'footer.php' ) ?>

<script>
    // Get the modal
var modal = document.getElementById('addActivityModal');

// Get the button that opens the modal
var btn = document.getElementById('addActivityBtn');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName('close')[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
    modal.style.display = 'block';
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = 'none';
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

</script>