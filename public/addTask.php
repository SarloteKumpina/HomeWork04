<?php
require "../src/checkSession.php";

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