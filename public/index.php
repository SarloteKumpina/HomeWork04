<?php
// echo "Hello June 16th!";
session_start();
require_once "../config/config.php";

if (!isset($_SESSION['user']) ) {
  include "../src/templates/loginForm.html";
  include "../src/templates/signUpForm.html";
  exit();
}

if (!isset($_SESSION['id'])) {
  include "../src/templates/loginForm.html";
  include "../src/templates/signUpForm.html";
  exit();
}

echo "Hello " . $_SESSION['user'] . " your id is " . $_SESSION['id'] . "<hr>";

require_once "../src/templates/header.php";
include "../src/templates/logoutForm.html";
include "../src/templates/jobSearchForm.html";
include "../src/templates/addJobForm.html";

$conn = new mysqli($servername, $username, $password, $dbname);

//CHECK FOR CONNECTION LATER DELETE
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

//NOT SAFE!!!
if(isset($_GET['jobName'])) {
  $jobName = "%" . $_GET['jobName'] . "%";
  $stmt = $conn->prepare("SELECT * 
          FROM todo 
          WHERE job 
          LIKE (?)
          AND user_id = (?)");
  $stmt->bind_param("sd", $jobName, $_SESSION['id']); 
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $stmt = $conn->prepare("SELECT * 
          FROM todo 
          WHERE user_id = (?)");
  $stmt->bind_param("d", $_SESSION['id']); 
  $stmt->execute();
  $result = $stmt->get_result();
}

if ($result->num_rows > 0) {
    // echo "Cool we got " . $result->num_rows . " rows of data!<hr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        // var_dump($row);
        $jobid = $row["id"];
        $job = $row["job"];
        // $job = $row["job"];
        $html = "<form action='updateJob.php' method='post'>";
        $html .= "ID: " . $row["id"];
        $html .= " <input name='jobName' value='$job'>";
        $html .= " ADDED: " . $row["added"];
        $html .= " UPDATED: " . $row["updated"];
        $html .= " <button type='submit' name='updateJob' value='$jobid'>";
        $html .= "UPDATE JOB</button>";
        $html .= "</form>";
        $html .= "<form action='deleteJob.php' method='post'>";
        $html .= "<button type='submit' name='deleteJob' value= '$jobid'>";
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