<?php
echo "Hello June 16th!";
session_start();
require_once "../config/config.php";
require_once "../src/templates/header.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$sql= "SELECT * FROM todo";
$result= $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Cool we got " . $result->num_rows . " rows of data!<hr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        var_dump($row);

    }
  } else {
    echo "0 results";
  }


require_once "../src/templates/footer.html";