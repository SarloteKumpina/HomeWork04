<?php
session_start();

//TODO add more checks for REQUEST type and songName and artistName validity
if (!isset($_POST['addJob'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$jobName = $_POST['jobName']; 
// $dueDate = $_POST['dueDate'];
$stmt = $conn->prepare("INSERT INTO todo (job) VALUE (?)");
$stmt->bind_param("s", $jobName);
$stmt->execute();
header("Location: /");


die("for now");



if (!isset($_SESSION['id'])) {
    //we do nothing without user id
    header("Location: /");
}


//might want to check if user has filled this form

// $userid = $_SESSION['id'];
//INSERT INTO `tracks` (`id`, `name`, `artist`, `created`) VALUES (NULL, 'Pa vējam', 'Jumprava', current_timestamp())


// echo "Ok should have added song now";
header("Location: /");