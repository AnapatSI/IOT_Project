<?php
$servername = "localhost";
// REPLACE with your Database name
$dbname = "s66160402";
// REPLACE with Database user
$username = "s66160402";
// REPLACE with Database user password
$password = "DFRWarAM";

// Establish connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection established successfully
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "<div id='connectionMessage'>Connected to mysql database. <br></div>";
}
?>

<script>
// Set a timeout to hide the connection message after 3 seconds
setTimeout(function() {
    document.getElementById('connectionMessage').style.display = 'none';
}, 3000);
</script>