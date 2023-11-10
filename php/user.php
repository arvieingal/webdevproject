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

        $done_id = "UPDATE activity SET status = 'Done' WHERE activityId = '$done_id'";

        if($con->query($done_id)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Marking done the Activity: ".$con->error;
        }
    }

    if(isset($_POST['remark_id'])){
        $remark_id = $_POST['remark_id'];
        $remark = $_POST['remark'];

        $remark_id = "UPDATE activity SET remarks = '$remark' WHERE activityId = '$remark_id'";

        if($con->query($remark_id)){
            header ("Location: user.php");
            exit();
        }else{
            echo "Error Marking done the Activity: ".$con->error;
        }
    }

    if(isset($_POST['cancel_id']) && !empty($_POST['cancel_id'])){
        $cancel_id = $_POST['cancel_id'];

        $cancel_id = "UPDATE activity SET status = 'Cancelled' WHERE activityId = '$cancel_id'";

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


    $sql = "SELECT * FROM user Where userId = $userId";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();



?>
    <!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../admin/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <style>
        /* CSS for the modal */
        .modal {
        color: black;
        display: none;
        position: fixed;
        z-index: 9999;
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

        button{
            background-color: #f4623a;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 6px 14px;
            margin: 1px;
        }
        input[type=submit]{
            background-color: #f4623a;
            color: white;
            border: none;
            border-radius: 10px;
            padding:6px 14px;
            margin: 1px;
        }
        input{
            border: 1px solid black;
            border-radius: 10px;
            padding: 4px 12px;
            margin: 1px;
        }
        select{
            border: 1px solid black;
            border-radius: 10px;
            padding: 6px 12px;
            margin: 1px;
        }
    </style>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <h5 style="color:white; padding-top: 6px;"><?php echo $row['firstName']; ?></h5>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="#">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Activities
                            </a>
                            <a class="nav-link" href="announcement_user.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Announcements
                            </a>
                            <div class="sb-sidenav-menu-heading">Interface</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Layouts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="login.html">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div>
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Addons</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <h5><?php echo $row['role']; ?></h5>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Activities</h1>
                        <div class="row">
                            <h3>Edit your Activity</h3>

                            <form action="" method="post">
                                <label for="">Select Activity:</label>
                                <select name="selected_id" id="selected_id">
                                    <?php foreach($data as $row): ?>
                                        <option value="<?php echo $row['activityId'];?>"><?php echo $row['activityName'];?></option>
                                    <?php endforeach;?>
                                </select>
                                <button type="submit" name="edit" id="editActivity">Edit</button>
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
                            </div>
                            <!-- Modal for Edit Records -->
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
                                <div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    DataTable Example
                                </div>
                                <div class="card-body">
                                <?php
                                if ( $show->num_rows > 0 ) {
                                    // If there are records in the database, display the table
                                    ?>
                                    <table id="datatablesSimple">
                                        <thead>
                                            <tr>
                                                <th>Activity Name</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Location</th>
                                                <th>OOTD</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Activity Name</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Location</th>
                                                <th>OOTD</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        <?php foreach ($data as $row): ?>
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
                                            <input type = 'text' name = 'remark' required>
                                            <button type = 'submit' name = 'remark_id' value = "<?php echo $row['activityId']; ?>">Add Remarks</button>
                                            </form>
                                            <form action="" method="POST">
                                            <button type = 'submit' name = 'done_id' value = "<?php echo $row['activityId']; ?>">Done</button>
                                            <button type = 'submit' name = 'cancel_id' value = "<?php echo $row['activityId']; ?>">Cancel</button>
                                            <button type = 'submit' name = 'delete_id' value = "<?php echo $row['activityId']; ?>">Delete</button>
                                            </form>
                                            </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    } else {
                                        // If no records are found, display a message
                                        echo '<p>No records found.</p>';
                                    }
                                    ?>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../admin/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../admin/js/datatables-simple-demo.js"></script>
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
    </body>
</html>