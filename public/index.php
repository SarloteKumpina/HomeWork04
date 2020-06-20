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
include "../src/templates/searchForm.html";
include "../src/templates/addJobForm.html";

$conn = new mysqli($servername, $username, $password, $dbname);

//CHECK FOR CONNECTION LATER DELETE
// if($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";


if(isset($_GET['dueDate']) || isset($_GET['jobName'])) {
    $dueDate = "%" . $_GET['dueDate'] . "%";
    $jobName = "%" . $_GET['jobName'] . "%";
    $stmt = $conn->prepare("SELECT * 
            FROM todo 
            WHERE due_date LIKE (?)
            AND job LIKE (?)
            AND user_id = (?)");
    $stmt->bind_param("ssd", $dueDate, $jobName, $_SESSION['id']); 
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
          $duedate = "2020-06-22";
        }
        $setduedate = date_create($duedate);
        $today = date_create(date("Y-m-d"));
        $days = date_diff($today, $setduedate)->format('%r%a');

        $html = "<div class='$classes'>";
        $html .= "<form action='updateJob.php' method='post'>";
        // $html .= "ID: " . $row["id"];
        $html .= " JOB: ";
        $html .= "<input type='checkbox' name='isDone' $checked>";
        $html .= " <input name='jobName' value='$job'>";
        $html .= "DUE DATE: ";
        $html .= " <input type='date' name='dueDate' value='$duedate'>";
        // $html .= " ADDED: " . $row["added"];
        // $html .= " UPDATED: " . $row["updated"];
        $html .= " <button type='submit' name='updateJob' value='$jobid'>";
        $html .= "UPDATE JOB</button>";
        $html .= "<form action='deleteJob.php' method='post'>";
        $html .= "<button type='submit' name='deleteJob' value= '$jobid'>";
        $html .= "DELETE JOB</button>";
        $html .= "</form>";
        $html .= "<span class='days-left'>$days days left untill due date</span>";
        $html .= "</form>";
        $html .= "</div>";
        echo $html;
    }
  } else {
    echo "Zero results";
  }

require_once "../src/templates/footer.html";