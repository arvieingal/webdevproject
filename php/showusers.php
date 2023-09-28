<?php
// Include the PHP file that contains the database connection code.
include_once("../connection/connection.php");

// Establish a database connection using the connection() function.
$con = connection();

// Define an SQL query to select all records from the "users" table.
$sql = "SELECT * FROM user";

// Execute the SQL query and store the result in the $show variable.
$show = $con->query($sql) or die ($con->error);

// Create an empty array to store the data retrieved from the database.
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
    $newFirstname = $_POST['firstname'];
    $newLastname = $_POST['lastname'];
    $newAge = $_POST['age'];
    $newGender = $_POST['gender'];
    $newAddress = $_POST['address'];
    $newEmail = $_POST['email'];

    // Define an SQL query to update the user's information in the database.
    $update_sql = "UPDATE user SET Firstname = '$newFirstname', Lastname= '$newLastname',Age='$newAge',Gender='$newGender',Address='$newAddress',Email='$newEmail' WHERE UserID = '$selectedId'";

    // Execute the SQL query to update the user's information.
    if ($con->query($update_sql)) {
        // Redirect to the 'update.php' page upon successful update.
        header("Location: showusers.php");
        exit();
    } else {
        // Display an error message if the update fails.
        echo "Error updating record: " . $con->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Set the document's character encoding and other metadata. -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Set the page title. -->
    <title>Edit Records</title>
</head>
<body>
    <!-- Create a heading for the page. -->
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
            Firstname: 
            <input required type="text" name="firstname" value="<?php echo $selectedRow['Firstname']; ?>"><br>
            Lastname:
            <input required type="text" name="lastname" value="<?php echo $selectedRow['Lastname']; ?>"><br>
            Age:
            <input required type="text" name="age" value="<?php echo $selectedRow['Age']; ?>"><br>
            Gender:
            <input required type="text" name="gender" value="<?php echo $selectedRow['Gender']; ?>"><br>
            Address:
            <input required type="text" name="address" value="<?php echo $selectedRow['Address']; ?>"><br>
            Email:
            <input required type="text" name="email" value="<?php echo $selectedRow['Email']; ?>"><br>
            <!-- Create a button to trigger the 'update' action. -->
            <button type="submit" name="update">Update</button>
        </form>
    <?php endif; ?>

    <!-- Create an HTML table to display user data. -->
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through the $data array and display user information in table rows. -->
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?php echo $row['Firstname']; ?></td>
                    <td><?php echo $row['Lastname']; ?></td>
                    <td><?php echo $row['Age']; ?></td>
                    <td><?php echo $row['Gender']; ?></td>
                    <td><?php echo $row['Address']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="admin.php">Go back to menu</a>
</body>
</html>
