<?php
require "../src/checkSession.php";

//TODO add more checks for REQUEST type and songName and artistName validity
if (!isset($_POST['addTask'])){
    header("Location: /");
}



require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$taskName = $_POST['taskName'];
$duedate = $_POST['taskDueDate'];
$userid = $_SESSION['id'];

$stmt = $conn->prepare("INSERT INTO 
        todo (task, due_date, user_id) 
        VALUE ((?), (?), (?))");
$stmt->bind_param("ssd", $taskName, $duedate, $userid);
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