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

// Fetch and format data for the pie chart
$genderData = [
    'Male' => 0,
    'Female' => 0,
    'Others' => 0
];

while ($row = $show->fetch_assoc()) {
    // Assuming you have a 'gender' column in your database
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
    $activityCounts[$monthNumber - 1] = $row['ActivityCount']; // Subtract 1 to convert to array index
}

$sql = "SELECT * FROM user ORDER BY Lastname ASC";
$show = $con->query($sql) or die($con->error);

$data = array();

// Loop through the query result and fetch each row as an associative array, then add it to the $data array.
while ($row = $show->fetch_assoc()) {
    $data[] = $row;
}

// Check if the HTTP request method is POST and the 'edit' button is clicked.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    // Get the selected user's ID from the POST data.
    $selectedId = $_POST['selected_id'];

    // Define an SQL query to select a user by their ID.
    $select_sql = "SELECT * FROM user WHERE UserID = '$selectedId'";

    // Execute the SQL query to retrieve the selected user's data.
    $result = $con->query($select_sql);

    // Check if a user with the specified ID exists.
    if ($result->num_rows == 1) {
        // Fetch the selected user's data as an associative array.
        $selectedRow = $result->fetch_assoc();
    } else {
        // Display an error message if the user is not found.
        echo "Record not found.";
    }
}

// Check if the HTTP request method is POST and the 'update' button is clicked.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Get the selected user's ID and updated information from the POST data.
    $selectedId = $_POST['selected_id'];
    $newStatus = $_POST['status'];
    $newLastname = $_POST['lastname'];
    $newAge = $_POST['age'];
    $newGender = $_POST['gender'];
    $newAddress = $_POST['address'];
    $newEmail = $_POST['email'];
    $newRole = $_POST['role'];

    // Define an SQL query to update the user's information in the database.
    $update_sql = "UPDATE user SET Status = '$newStatus',Role = '$newRole' WHERE UserID = '$selectedId'";

    // Execute the SQL query to update the user's information.
    if ($con->query($update_sql)) {
        // Redirect to the 'update.php' page upon successful update.
        header("Location: admin.php");
        exit();
    } else {
        // Display an error message if the update fails.
        echo "Error updating record: " . $con->error;
    }
}

?>


admin<br>
<a href="logout.php">Logout</a><br>
<a href="showusers.php">Show User</a>

<!DOCTYPE html>
<html>
<head>
    <title>Gender Pie Chart</title>
    <!-- Include Chart.js library from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.min.js"></script>
    
</head>
<body>
<h2>Edit Records</h2>

<!-- Create a form for selecting a user to edit. -->
<form method="post" action="">
    <label for="selected_id">Select User:</label>
    <select name="selected_id" id="selected_id">
        <!-- Populate the dropdown with user IDs and first names from the $data array. -->
        <?php foreach ($data as $row): ?>
            <option value="<?php echo $row['UserID']; ?>"><?php echo $row['Firstname']; ?></option>
        <?php endforeach; ?>
    </select>
    <!-- Create a button to trigger the 'edit' action. -->
    <button type="submit" name="edit">Edit</button>
</form>

<!-- Display an edit form if a user is selected. -->
<?php if (isset($selectedRow)): ?>
    <form method="post" action="">
        <!-- Hidden input to store the selected user's ID. -->
        <input required type="hidden" name="selected_id" value="<?php echo $selectedRow['UserID']; ?>">
        Status:
        <input required type="text" name="status" value="<?php echo $selectedRow['Status']; ?>"><br>
        Role:
        <input required type="text" name="role" value="<?php echo $selectedRow['Role']; ?>"><br>
        <!-- Create a button to trigger the 'update' action. -->
        <button type="submit" name="update">Update</button>
    </form>
<?php endif; ?>

<!-- Create an HTML table to display user data. -->
<table>
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
        </tr>
    </thead>
    <tbody>
        <!-- Loop through the $data array and display user information in table rows. -->
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
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    
    <!-- Your chart will be rendered here -->
    <canvas id="genderChart"></canvas>
    <canvas id="activityBarChart" width="400" height="200"></canvas>

    <form action="announcement.php" method="post">
        <label for="">Announcement:</label><br>
        <label for="">Title:</label><br>
        <input type="text" name="title"><br>
        <label for="">Content:</label><br>
        <textarea name="content" id="" cols="30" rows="3"></textarea>
        <br>
        <input type="submit" value="Submit Announcement">
    </form>
    

    <!-- Remove width and height attributes -->

    <!-- Your JavaScript code for creating the pie chart goes here -->
    <!-- Your JavaScript code for creating the pie chart goes here -->
<script>
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
</script>
<!-- JavaScript code for the bar chart -->
<script>
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
