<?php
require "../src/checkSession.php";

if (!isset($_POST['deletetask'])){
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$taskid = $_POST['deletetask']; 
$stmt = $conn->prepare("DELETE FROM todo 
            WHERE id = (?)
            AND user_id = (?)");
$stmt->bind_param("dd", $taskid, $_SESSION['id']);
$stmt->execute();
header("Location: /");