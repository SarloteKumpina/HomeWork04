<?php
require "../src/checkSession.php";

if (!isset($_POST['deleteTask'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$taskid = $_POST['deleteTask']; 
$stmt = $conn->prepare("DELETE FROM `todo` WHERE `todo`.`id` = (?)");
$stmt->bind_param("d", $taskid);
$stmt->execute();
header("Location: /");