<?php
require "../src/checkSession.php";


if (!isset($_POST['updateTask'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$taskid = $_POST['updateTask'];
$taskName = $_POST['taskName'];
$duedate = $_POST['dueDate'];

if(isset($_POST['isDone'])) {
    $isDone = 1;
} else {
    $isDone = 0;
}
// $checked = $_POST['isDone'];
$stmt = $conn->prepare("UPDATE todo 
            SET done = (?) ,task = (?), 
            updated = CURRENT_TIMESTAMP(), 
            due_date = STR_TO_DATE((?), '%Y-%m-%d')
            WHERE id = (?)
            AND user_id = (?)");
$stmt->bind_param("dssdd", $isDone, $taskName, $duedate, $taskid, $_SESSION['id']);
$stmt->execute();
header("Location: /");