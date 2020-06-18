<?php
session_start();

//TODO add more checks for REQUEST type and songName and artistName validity
if (!isset($_POST['deleteJob'])){
    header("Location: /index.php");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$jobid = $_POST['deleteJob']; 
$stmt = $conn->prepare("DELETE FROM todo WHERE id = (?)");
$stmt->bind_param("d", $jobid);
$stmt->execute();
header("Location: /index.php");