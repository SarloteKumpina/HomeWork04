<?php
// echo "Hello June 16th!";
session_start();
require_once "../config/config.php";
require_once "../src/templates/header.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['id'])) {
  if (isset($_GET['unsucessfull_login'])){
    include "../src/templates/unsucessfullLogin.html";
  }
  include "../src/templates/loginForm.html";
  include "../src/templates/signUpForm.html";
  include "../src/templates/footer.html";
  exit();
}

if(isset($_GET['sucessfull_login'])) {
  include "../src/templates/sucessfullLogin.html";
}

echo "Hello " . $_SESSION['user'] . " your id is " . $_SESSION['id'] . "<hr>";

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
        
        $classes = "job-to-do";
        $checked = "";
        if ($row["done"]) {
          $classes .= " job-done";
          $checked = "checked";
        }

        $jobid = $row["id"];
        $job = $row["job"];

        if(isset($row['due_date'])) {
          $duedate = $row['due_date'];
        } else {
          $duedate = "2020-06-20";
        }


        // $isDone = $row["done"];
        // $job = $row["job"];
        $html = "<div class='$classes'>";
        $html .= "<form action='updateJob.php' method='post'>";
        $html .= "ID: " . $row["id"];
        $html .= "<input type='checkbox' name='isDone' $checked>";
        $html .= " <input name='jobName' value='$job'>";
        $html .= " <input type='date' name='dueDate' value='$duedate'>";
        $html .= " ADDED: " . $row["added"];
        $html .= " UPDATED: " . $row["updated"];
        $html .= " <button type='submit' name='updateJob' value='$jobid'>";
        $html .= "UPDATE JOB</button>";
        $html .= "</form>";
        $html .= "<form action='deleteJob.php' method='post'>";
        $html .= "<button type='submit' name='deleteJob' value= '$jobid'>";
        $html .= "DELETE JOB</button>";
        $html .= "</form>";
        $html .= "</div>";
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