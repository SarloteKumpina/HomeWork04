<?php
require "../src/checkSession.php";

if (!isset($_POST['deleteTask'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$taskid = $_POST['deleteTask']; 
$stmt = $conn->prepare("DELETE FROM todo 
            WHERE todo.id = (?)
            AND todo.user_id = (?)");
$stmt->bind_param("dd", $taskid, $_SESSION['id']);
$stmt->execute();
header("Location: /");