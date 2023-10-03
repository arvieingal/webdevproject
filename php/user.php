
<?php
    include_once("../connection/connection.php");
    $con = connection();
    
    $sql = "SELECT * FROM activity ORDER BY Date ASC";
    $show = $con->query($sql) or die($con->error);

    session_start();
    if($_SESSION["Role"] == null)
    {        
        header("Location: ../index.html");
    }
    else
    {
        if($_SESSION["Role"] == "user")
        {}
        else
        {
            header("Location: ../index.html");
        }

    }

    $data = array();

    while($row = $show->fetch_assoc()){
        $data[] = $row;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])){
        $selectedId = $_POST['selected_id'];

        $select_sql = "SELECT * FROM activity WHERE Id = '$selectedId'";

        $result = $con->query($select_sql);

        if($result->num_rows==1){
            $selectedRow = $result->fetch_assoc();
        }else{
            echo "Record not fouond.";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        // Get the selected user's ID and updated information from the POST data.
        $selectedId = $_POST['selected_id'];
        $newName = $_POST['name'];
        $newDate = $_POST['date'];
        $newTime = $_POST['time'];
        $newAmpm = $_POST['ampm'];
        $newLocation = $_POST['location'];
        $newOotd = $_POST['ootd'];
        $newStatus = $_POST['status'];
        $newRemarks = $_POST['remarks'];
    
        // Define an SQL query to update the user's information in the database.
        $update_sql = "UPDATE activity SET Name = '$newName', Date = '$newDate', Time = '$newTime', Ampm = '$newAmpm',Location = '$newLocation', Ootd = '$newOotd', Status = '$newStatus', Remarks = '$newRemarks' WHERE Id = '$selectedId'";
    
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
    <input required type="hidden" name="selected_id" value="<?php echo $selectedRow['Id']; ?>">
    Activity Name: 
    <input required type="text" name="name" value="<?php echo $selectedRow['Name']; ?>"><br>
    Date: 
    <input required type="text" name="date" value="<?php echo $selectedRow['Date']; ?>"><br>
    Time: 
    <input required type="text" name="time" value="<?php echo $selectedRow['Time']; ?>"><br>
    am/pm: 
    <select name="ampm" id="">
        <option value="am" <?php if ($selectedRow['Ampm'] == 'am') echo 'selected'; ?>>am</option>
        <option value="pm" <?php if ($selectedRow['Ampm'] == 'pm') echo 'selected'; ?>>pm</option>
    </select><br>
    Location:
    <input required type="text" name="location" value="<?php echo $selectedRow['Location']; ?>"><br>
    OOTD:
    <input required type="text" name="ootd" value="<?php echo $selectedRow['Ootd']; ?>"><br>

    <!-- Add new fields for cancel, done, and remarks -->
    <label for="status">Status:</label>
    <select name="status" id="status">
        <option value=""></option>
        <option value="Done" <?php if ($selectedRow['Status'] == 'Done') echo 'selected'; ?>>Done</option>
        <option value="Dancel" <?php if ($selectedRow['Status'] == 'Cancel') echo 'selected'; ?>>Cancel</option>
    </select><br>
    Remarks:
    <textarea name="remarks"><?php echo $selectedRow['Remarks']; ?></textarea><br>

    <!-- Create a button to trigger the 'update' action. -->
    <button type="submit" name="update">Update</button>
</form>
<?php endif; ?>

<h1>Add Activity</h1>
<form action="activity.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>

    <label for="date">Date:</label>
    <input type="text" id="date" name="date" placeholder="yyyy/mm/dd" required><br><br>

    <label for="time">time:</label>
    <input type="text" id="time" name="time" required><br><br>

    <select name="ampm" id="">
        <option value="am">am</option>
        <option value="pm">pm</option>
    </select><br><br>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" required><br><br>

    <label for="ootd">Ootd:</label>
    <input id="ootd" name="ootd" required></input><br><br>

    <input type="submit" value="Add Activity">
</form>

<h1>Your Activities</h1>
<?php


if ($show->num_rows > 0) {
    // If there are records in the database, display the table
?>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Am/Pm</th>
            <th>Location</th>
            <th>OOTD</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?php echo $row['Name'];?></td>
            <td><?php echo $row['Date'];?></td>
            <td><?php echo $row['Time'];?></td>
            <td><?php echo $row['Ampm'];?></td>
            <td><?php echo $row['Location'];?></td>
            <td><?php echo $row['Ootd'];?></td>
            <td><?php echo $row['Status'];?></td>
            <td><?php echo $row['Remarks'];?></td>
            <td>
                <form action="" method="POST">
                    <button type="submit" name="delete_id" value="<?php echo $row['Id']; ?>">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
} else {
    // If no records are found, display a message
    echo "<p>No records found.</p>";
}
?>
        </header>
        <!-- About-->
        
        <!-- Portfolio-->
        <div id="portfolio">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="../assets/img/portfolio/fullsize/1.jpg" title="Project Name">
                            <img class="img-fluid" src="../assets/img/portfolio/thumbnails/1.jpg" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="../assets/img/portfolio/fullsize/2.jpg" title="Project Name">
                            <img class="img-fluid" src="../assets/img/portfolio/thumbnails/2.jpg" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="../assets/img/portfolio/fullsize/3.jpg" title="Project Name">
                            <img class="img-fluid" src="../assets/img/portfolio/thumbnails/3.jpg" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="../assets/img/portfolio/fullsize/4.jpg" title="Project Name">
                            <img class="img-fluid" src="../assets/img/portfolio/thumbnails/4.jpg" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="../assets/img/portfolio/fullsize/5.jpg" title="Project Name">
                            <img class="img-fluid" src="../assets/img/portfolio/thumbnails/5.jpg" alt="..." />
                            <div class="portfolio-box-caption">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <a class="portfolio-box" href="../assets/img/portfolio/fullsize/6.jpg" title="Project Name">
                            <img class="img-fluid" src="../assets/img/portfolio/thumbnails/6.jpg" alt="..." />
                            <div class="portfolio-box-caption p-3">
                                <div class="project-category text-white-50">Category</div>
                                <div class="project-name">Project Name</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Call to action-->
        <section class="page-section bg-dark text-white">
            <div class="container px-4 px-lg-5 text-center">
                <h2 class="mb-4">Free Download at Start Bootstrap!</h2>
                <a class="btn btn-light btn-xl" href="https://startbootstrap.com/theme/creative/">Download Now!</a>
            </div>
        </section>
        <!-- Contact-->
        <section class="page-section" id="contact">
            <div class="container px-4 px-lg-5">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-8 col-xl-6 text-center">
                        <h2 class="mt-0">Let's Get In Touch!</h2>
                        <hr class="divider" />
                        <p class="text-muted mb-5">Ready to start your next project with us? Send us a messages and we will get back to you as soon as possible!</p>
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
                                <input class="form-control" id="name" type="text" placeholder="Enter your name..." data-sb-validations="required" />
                                <label for="name">Full name</label>
                                <div class="invalid-feedback" data-sb-feedback="name:required">A name is required.</div>
                            </div>
                            <!-- Email address input-->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="email" type="email" placeholder="name@example.com" data-sb-validations="required,email" />
                                <label for="email">Email address</label>
                                <div class="invalid-feedback" data-sb-feedback="email:required">An email is required.</div>
                                <div class="invalid-feedback" data-sb-feedback="email:email">Email is not valid.</div>
                            </div>
                            <!-- Phone number input-->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="phone" type="tel" placeholder="(123) 456-7890" data-sb-validations="required" />
                                <label for="phone">Phone number</label>
                                <div class="invalid-feedback" data-sb-feedback="phone:required">A phone number is required.</div>
                            </div>
                            <!-- Message input-->
                            <div class="form-floating mb-3">
                                <textarea class="form-control" id="message" type="text" placeholder="Enter your message here..." style="height: 10rem" data-sb-validations="required"></textarea>
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
                                    <a href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                                </div>
                            </div>
                            <!-- Submit error message-->
                            <!---->
                            <!-- This is what your users will see when there is-->
                            <!-- an error submitting the form-->
                            <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Error sending message!</div></div>
                            <!-- Submit Button-->
                            <div class="d-grid"><button class="btn btn-primary btn-xl disabled" id="submitButton" type="submit">Submit</button></div>
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

    <a href="logout.php">Logout</a>
    <?php include_once("footer.php") ?>
