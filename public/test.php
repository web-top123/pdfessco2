<?php
$servername = "localhost";
$username = "pdf";
$password = "star1234STAR!@#$";
$database = "pdfglue_db"; // Replace 'your_database_name' with your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// SQL query to select data from the users table
$sql = "SELECT * FROM `users` WHERE `email` = 'johni@gmail.com' LIMIT 1";

// Execute query
$result = mysqli_query($conn, $sql);

// Check if query was successful
if ($result) {
  // Check if any rows were returned
  if (mysqli_num_rows($result) > 0) {
    // Fetch the first row from the result set
    $row = mysqli_fetch_assoc($result);
    // Output the data (you can modify this part to suit your needs)
    echo "User ID: " . $row['id'] . "<br>";
    echo "Username: " . $row['name'] . "<br>";
    echo "Email: " . $row['email'] . "<br>";
    // Output other columns as needed
  } else {
    // No rows matched the query criteria
    echo "No user found with email 'johni@gmail.com'";
  }
} else {
  // Query execution failed
  echo "Error: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>