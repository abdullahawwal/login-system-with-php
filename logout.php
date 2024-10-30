<?php
    session_start();
        $host_name = 'localhost';
        $user_name = 'root';
        $password = '';
        $database_name = 'asbhisab';
        $conn = mysqli_connect($host_name, $user_name, $password, $database_name);
        
    if(isset($_SESSION['user_id'])){
        $logout_id = mysqli_real_escape_string($conn, $_GET['id']);
        if(isset($logout_id)){
            $status = "0";
            $sql = mysqli_query($conn, "UPDATE tbl_user SET token = '{$status}' WHERE user_id={$_GET['id']}");
            if($sql){
                session_destroy();
                header("location: index.php");
            }
        }else{
            header("location: welcome.php");
        }
    }else{  
        header("location: index.php");
    }
?>