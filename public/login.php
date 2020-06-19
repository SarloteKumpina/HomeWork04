<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    require_once "../config/config.php";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = (?)");
    $stmt->bind_param("s", $_POST['userName']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        var_dump($row);
        die("for now");
    }

    // echo "Cool got POST method that will save my login!!!";
    if (isset($_POST['userName'])) {
       $_SESSION['userName'] = $_POST['userName'];
       echo "Session saved!!!";
    }
}
header("Location: /");