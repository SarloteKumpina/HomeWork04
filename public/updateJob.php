<?php
session_start();

if (!isset($_POST['updateJob'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$jobid = $_POST['updateJob'];
$jobName = $_POST['jobName'];
$stmt = $conn->prepare("UPDATE todo SET job = (?) WHERE id = (?)");
$stmt->bind_param("sd", $jobName, $jobid);
$stmt->execute();
header("Location: /");