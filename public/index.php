<?php
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

echo "<div class='welcome-user'>Welcome " . $_SESSION['user'] . "! Check out your \"To Do List\". </div><hr>";
// echo "Welcome " . $_SESSION['user'] . "! Check out your 'To Do List'. "id is " . $_SESSION['id'] . "<hr>";

include "../src/templates/logoutForm.html";
include "../src/templates/addTaskForm.html";
include "../src/templates/searchForm.html";


$conn = new mysqli($servername, $username, $password, $dbname);

//CHECK FOR CONNECTION LATER DELETE
// if($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";


if(isset($_GET['dueDate']) || isset($_GET['taskName'])) {
    $dueDate = "%" . $_GET['dueDate'] . "%";
    $taskName = "%" . $_GET['taskName'] . "%";
    $stmt = $conn->prepare("SELECT * 
            FROM todo 
            WHERE due_date LIKE (?)
            AND task LIKE (?)
            AND user_id = (?)
            ORDER BY due_date ASC");
    $stmt->bind_param("ssd", $dueDate, $taskName, $_SESSION['id']); 
    $stmt->execute();
    $result = $stmt->get_result();
  } else {
  $stmt = $conn->prepare("SELECT * 
          FROM todo 
          WHERE user_id = (?)
          ORDER BY due_date ASC");
  $stmt->bind_param("d", $_SESSION['id']); 
  $stmt->execute();
  $result = $stmt->get_result();
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $classes = "task-to-do";
        $checked = "";
        if ($row["done"]) {
          $classes .= " task-done";
          $checked = "checked";
        }

        $taskid = $row["id"];
        $task = $row["task"];

        if(isset($row['due_date'])) {
          $duedate = $row['due_date'];
        } else {
          $duedate = "2020-06-22";
        }
        $setduedate = date_create($duedate);
        $today = date_create(date("Y-m-d"));
        $days = date_diff($today, $setduedate)->format('%r%a');
        
        $html = "<hr>";
        $html .= "<div class='$classes'>";
        $html .= "<form action='updateTask.php' method='post'>";
        // $html .= "ID: " . $row["id"];
        $html .= " TASK: ";
        $html .= "<input type='checkbox' name='isDone' $checked>";
        $html .= " <input name='taskName' value='$task'>";
        $html .= " DUE DATE: ";
        $html .= "<input type='date' name='dueDate' value='$duedate'>";
        // $html .= " ADDED: " . $row["added"];
        // $html .= " UPDATED: " . $row["updated"];
        $html .= "<button type='submit' class='btn btn-secondary mb-2' name='updateTask' value='$taskid'>";
        $html .= "UPDATE TASK</button>";
        $html .= "<form action='deleteTask.php' method='post'>";
        $html .= "<button type='submit' class='btn btn-secondary mb-2' name='deletetask' value='$taskid'>";
        $html .= "DELETE TASK</button>";
        $html .= "</form>";
        $html .= "<span class='days-left'> $days days left untill due date</span>";
        $html .= "</form>";

        $html .= "</div>";
        echo $html;
    }
  } else {
    echo "<div class='zero-results'>Zero results</div>";
  }

require_once "../src/templates/footer.html";