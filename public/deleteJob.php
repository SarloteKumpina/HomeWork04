<?php
require "../src/checkSession.php";

if (!isset($_POST['deleteJob'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$jobid = $_POST['deleteJob']; 
$stmt = $conn->prepare("DELETE FROM todo 
            WHERE todo.id = (?)
            AND todo.user_id = (?)");
$stmt->bind_param("dd", $jobid, $_SESSION['id']);
$stmt->execute();
header("Location: /");