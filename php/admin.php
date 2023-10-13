<?php
session_start();
if($_SESSION["Role"]==null){
    header("Location: ../index.html");
}
else{
    if($_SESSION["Role"] == "admin"){

    }
    else{
        header("Location: ../index.html");
    }
}

include_once("../connection/connection.php");
$con = connection();

$sql = "SELECT * FROM user";
$show = $con->query($sql) or die($con->error);

$genderData = [
    'Male' => 0,
    'Female' => 0,
    'Others' => 0
];

while ($row = $show->fetch_assoc()) {
    $gender = $row['Gender'];
    if (array_key_exists($gender, $genderData)) {
        $genderData[$gender]++;
    }
}

$monthLabels = [
    'January', 'February', 'March', 'April',
    'May', 'June', 'July', 'August',
    'September', 'October', 'November', 'December'
];
$activityCounts = array_fill(0, 12, 0);

$sqll = "SELECT DATE_FORMAT(Date, '%m') AS MonthNumber, COUNT(*) AS ActivityCount FROM activity GROUP BY MonthNumber";
$show = $con->query($sqll) or die($con->error);

while ($row = $show->fetch_assoc()) {
    $monthNumber = $row['MonthNumber'];
    $activityCounts[$monthNumber - 1] = $row['ActivityCount'];
}

$sql = "SELECT * FROM user ORDER BY Lastname ASC";
$show = $con->query($sql) or die($con->error);

$userId = $_SESSION[ 'UserID' ];


$data = array();

while ($row = $show->fetch_assoc()) {
    $data[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $selectedId = $_POST['selected_id'];

    $select_sql = "SELECT * FROM user WHERE UserID = '$selectedId'";

    $result = $con->query($select_sql);

    if ($result->num_rows == 1) {
        $selectedRow = $result->fetch_assoc();
    } else {
        echo "Record not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $selectedId = $_POST['selected_id'];
    $newStatus = $_POST['status'];
    $newLastname = $_POST['lastname'];
    $newAge = $_POST['age'];
    $newGender = $_POST['gender'];
    $newAddress = $_POST['address'];
    $newEmail = $_POST['email'];
    $newRole = $_POST['role'];

    $update_sql = "UPDATE user SET Status = '$newStatus',Role = '$newRole' WHERE UserID = '$selectedId'";

    if ($con->query($update_sql)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating record: " . $con->error;
    }
}

// Fetch Own account
$sql = "SELECT * FROM user Where UserID = $userId";
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
        .modal {
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
            <p style="color:white; padding-top: 14px;"><?php echo $row['Firstname']; ?></p>
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
                            <a class="nav-link" href="index.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
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
                        Start Bootstrap
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Primary Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Warning Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Success Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Danger Card</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Area Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="genderChart"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="activityBarChart" width="400" height="200"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal for Edit Records -->
                        <div id="editModal" class="modal">
                                    <div class="modal-content">
                                        <h2>Edit User</h2>
                                        <form method="post" action="">
                                        <input type="hidden" name="selected_id" value="">
                                        Status: <input required type="text" name="status" value=""><br>
                                        Role: <input required type="text" name="role" value=""><br>
                                        <button type="submit" name="update">Update</button>
                                        </form>
                                        <button type="button" onclick="closeModal()">Close</button>
                                    </div>
                                    </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                DataTable Example
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Address</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Last Name</th>
                                            <th>First Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Address</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php foreach ($data as $row): ?>
                                        <tr>
                                            <td><?php echo $row['Lastname']; ?></td>    
                                            <td><?php echo $row['Firstname']; ?></td>
                                            <td><?php echo $row['Age']; ?></td>
                                            <td><?php echo $row['Gender']; ?></td>
                                            <td><?php echo $row['Address']; ?></td>
                                            <td><?php echo $row['Email']; ?></td>
                                            <td><?php echo $row['Status']; ?></td>
                                            <td><?php echo $row['Role']; ?></td>
                                            <td><form action="" method="post">
                                            <button type="button" name="edit" data-user-id="<?php echo $row['UserID']; ?>" data-status="<?php echo $row['Status']; ?>" data-role="<?php echo $row['Role']; ?>">Edit</button>
                                            </form></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <form action="announcement.php" method="post">
                        <label for="">Announcement:</label><br>
                        <label for="">Title:</label><br>
                        <input type="text" name="title"><br>
                        <label for="">Content:</label><br>
                        <textarea name="content" id="" cols="30" rows="3"></textarea>
                        <br>
                        <input type="submit" value="Submit Announcement">
                    </form>
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
            // For Modal
            // JavaScript for opening the modal and loading content
        const editModal = document.getElementById('editModal');

        // Function to open the modal and load content
        function openEditModal(userId, status, role) {
        // Create the edit form HTML content
        const editForm = `
            <h2>Edit User</h2>
            <form method="post" action="">
            <input type="hidden" name="selected_id" value="${userId}">
            Status: <input required type="text" name="status" value="${status}"><br>
            Role: <input required type="text" name="role" value="${role}"><br>
            <button type="submit" name="update">Update</button>
            </form>
        `;
        
        // Set the modal content to the edit form
        editModal.querySelector('.modal-content').innerHTML = editForm;
        
        // Display the modal
        editModal.style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
        editModal.style.display = 'none';
        }

        // Event listener for the "Edit" button
        document.addEventListener('click', function(event) {
        if (event.target.matches('button[name="edit"]')) {
            const userId = event.target.dataset.userId;
            const status = event.target.dataset.status;
            const role = event.target.dataset.role;
            openEditModal(userId, status, role);
        }
        });

        // Event listener for the modal close button
        document.querySelector('.modal-content').addEventListener('click', function(event) {
        if (event.target.matches('button[name="update"]')) {
            closeModal();
        }
        });

        // Close the modal when the user clicks outside of it
        window.addEventListener('click', function(event) {
        if (event.target === editModal) {
            closeModal();
        }
        });


            // Data for the pie chart
            const data = {
                labels: ['Male', 'Female', 'Others'],
                datasets: [{
                    data: <?php echo json_encode(array_values($genderData)); ?>,
                    backgroundColor: ['blue', 'pink', 'gray'], // Colors for each section of the pie chart
                }]
            };

            // Get the canvas element
            const ctx = document.getElementById('genderChart').getContext('2d');

            // Create the pie chart
            const genderPieChart = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    // Set the size of the chart
                    responsive: false, // Disable responsiveness
                    maintainAspectRatio: false, // Disable aspect ratio
                    width: 400, // Set your desired width
                    height: 400, // Set your desired height
                }
            });

            // Generate a legend based on the data


        // Data for the bar chart
        const activitiesData = {
            labels: <?php echo json_encode($monthLabels); ?>,
            datasets: [{
                label: 'Activity Count',
                data: <?php echo json_encode($activityCounts); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        // Get the canvas element for the bar chart
        const activityCtx = document.getElementById('activityBarChart').getContext('2d');

        // Create the bar chart
        const activityBarChart = new Chart(activityCtx, {
            type: 'bar',
            data: activitiesData,
            options: {
                responsive: false,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });


        </script>
    </body>
</html>