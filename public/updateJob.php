<?php
session_start();

if (!isset($_POST['updateJob'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$jobid = $_POST['updateJob'];
$jobName = $_POST['jobName'];
if(isset($_POST['isDone'])) {
    $isDone = 1;
} else {
    $isDone = 0;
}
// $checked = $_POST['isDone'];
$stmt = $conn->prepare("UPDATE todo 
            SET done = (?) ,job = (?), updated = CURRENT_TIMESTAMP()
            WHERE id = (?)");
$stmt->bind_param("dsd", $isDone, $jobName, $jobid);
$stmt->execute();
header("Location: /");