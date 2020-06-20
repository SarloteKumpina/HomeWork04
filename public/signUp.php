<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if(isset($_POST['signup'])) {
        require_once "../config/config.php";
        $conn = new mysqli($servername, $username, $password, $dbname);
        $username = $_POST['username'];
        $email = $_POST['email'];
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users
        (user_name, email, hash)
        VALUES( (?), (?), (?) ) ");
        $stmt->bind_param("sss", $username, $email, $hash);
        $stmt->execute();
        $result = $stmt->get_result();
        var_dump($result);
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // var_dump($row);
            // die("for now");
            
            $_SESSION['user'] = $row['user_name'];
            $_SESSION['id'] = $row['user_id'];
        }
        
    header("Location: /");    
    }
}
// header("Location: /");

