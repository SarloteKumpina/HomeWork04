<?php
// echo "Hello June 16th!";
session_start();
require_once "../config/config.php";
require_once "../src/templates/header.php";
include "../src/templates/jobFilterForm.html";
include "../src/templates/addJobForm.html";

$conn = new mysqli($servername, $username, $password, $dbname);

// if($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";

//NOT SAFE!!!
if(isset($_GET['jobName'])) {
  $jobName = "%" . $_GET['jobName'] . "%";
  $stmt = $conn->prepare("SELECT * FROM todo WHERE job LIKE (?)");
  $stmt->bind_param("s", $jobName); 
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $sql= "SELECT * FROM todo";
  $result= $conn->query($sql);
}

if ($result->num_rows > 0) {
    echo "Cool we got " . $result->num_rows . " rows of data!<hr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        // var_dump($row);
        $jobid = $row["id"];
        $html = "ID: " . $row["id"];
        $html .= " JOB: " . $row["job"];
        $html .= " ADDED: " . $row["added"];
        $html .= " UPDATED: " . $row["updated"];
        $html .= "<form action='deleteJob.php' method='post'>";
        $html .= "<button type='submit' name= 'deleteJob' value= '$jobid'>";
        $html .= "DELETE JOB</button>";
        $html .= "</form>";
        $html .= "<hr>";
        echo $html;
    }
  } else {
    echo "Zero results";
  }

// $sql= "SELECT * FROM todo";
// $result= $conn->query($sql);
// $allrows = $result->fetch_all(MYSQLI_ASSOC);
// // var_dump($allrows);

// foreach($allrows as $rowindex => $row) {
//   echo "<div class='row' id='row-$rowindex'>";
//   // var_dump($row);
//   $html = "Id: " . $row["id"];
//   $html .= " Job: " . $row["job"];
//   $html .= " Added: " . $row["added"];
//   $html .= " Updated: " . $row["updated"];
//   // $html .= "<hr>";
//   echo $html;
//   echo "</div>";
// }


require_once "../src/templates/footer.html";