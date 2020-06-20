<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if(isset($_POST['login'])) {
        require_once "../config/config.php";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = (?)");
        $stmt->bind_param("s", $_POST['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows != 1) {
            header("Location: /?unsucesfull_login=true");
            exit();
        }

        $row = $result->fetch_assoc();
        if (password_verify($_POST['password'], $row['hash'])) {
                $_SESSION['user'] = $row['user_name'];
                $_SESSION['id'] = $row['user_id'];
                header("Location: /?sucesfull_login=true");
        } else {
            header("Location: /?unsucesfull_login=true");
            exit();
        }  
    }
}
