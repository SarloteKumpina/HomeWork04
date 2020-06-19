<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if(isset($_POST['login'])) {
        require_once "../config/config.php";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = (?)");
        $stmt->bind_param("s", $_POST['userName']);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // var_dump($row);
            // die("for now");
            $_SESSION['user'] = $row['user_name'];
            $_SESSION['id'] = $row['user_id'];
        }
    }
}


header("Location: /");