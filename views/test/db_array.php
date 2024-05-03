<?php
// Assuming $mysqli is your database connection object

require_once 'model/database/database.php';
$mysqli = database();

$query = 'SELECT brgys FROM delivery_fee WHERE municipality = "Marilao"';
$stmt = $mysqli->prepare($query);

if ($stmt === false) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->execute();

// Bind the result to a variable
$stmt->bind_result($brgy);

// Fetch the result
$stmt->fetch();

// Close the statement
$stmt->close();

// Close the database connection
$mysqli->close();

// Now, decode the JSON string into an array
$array = explode(',', $brgy);

// Print the array for debugging
print_r($array);

// To echo the array as JSON
//echo json_encode($array);
echo '<hr>';
// To echo the original JSON string from the database
echo $brgy;
?>
