<?php
require "../src/checkSession.php";


if (!isset($_POST['updateJob'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$jobid = $_POST['updateJob'];
$jobName = $_POST['jobName'];
$duedate = $_POST['dueDate'];

if(isset($_POST['isDone'])) {
    $isDone = 1;
} else {
    $isDone = 0;
}
// $checked = $_POST['isDone'];
$stmt = $conn->prepare("UPDATE todo 
            SET done = (?) ,job = (?), 
            updated = CURRENT_TIMESTAMP(), 
            due_date = STR_TO_DATE((?), '%Y-%m-%d')
            WHERE id = (?)
            AND user_id = (?)");
$stmt->bind_param("dssdd", $isDone, $jobName, $duedate, $jobid, $_SESSION['id']);
$stmt->execute();
header("Location: /");