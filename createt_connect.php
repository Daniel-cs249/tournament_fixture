<?php
include 'createt.html';

if (isset($_POST['submit'])) {
    // Collect form data
    $courtname = $_POST['courtname'];
    $totalteams = $_POST['totalteams'];
    $category = $_POST['category'];
    $shuttletype = $_POST['shuttle'];
    $entrance = $_POST['entrance'];
    $venue = $_POST['venue'];
    $date = $_POST['date'];
    $district = $_POST['district'];
    $upiid = $_POST['upi-id'];
    $password=$_POST['password'];
    // Database connection
    $host = "localhost";
    $user = "root";
    $pass = "abc123";
    $db = "tournament";
    $conn = new mysqli($host, $user, $pass, $db, 3307);

    if ($conn->connect_error) {
        die("Failed to connect to database: " . $conn->connect_error);
    } else {
        // Correct SQL query
        $insert = "INSERT INTO create_tournament (court_name, total_teams, category, shuttle_type, entrance_fee, venue, district, upi_id, date, password) 
                   VALUES ('$courtname', '$totalteams', '$category', '$shuttletype', '$entrance', '$venue', '$district', '$upiid', '$date', '$password')";

        // Execute the query
        if (mysqli_query($conn, $insert)) {
            echo "<script>alert('Data successfully submitted!');</script>";
        } else {
            echo "Error: " . mysqli_error($conn); // Show detailed error message
        }
    }

    $conn->close();
}
?>
