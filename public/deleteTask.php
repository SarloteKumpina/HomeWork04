<?php
require "../src/checkSession.php";

if (!isset($_POST['deleteTask'])) {
    header("Location: /");
}

require_once "../config/config.php";
$conn = new mysqli($servername, $username, $password, $dbname);
$id = $_POST['deleteTask']; 
$stmt = $conn->prepare("DELETE FROM todo 
        WHERE id = (?)
        AND user_id = (?)");
$stmt->bind_param("dd", $id, $_SESSION['id']);
$stmt->execute();
header("Location: /");