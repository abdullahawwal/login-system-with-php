<?php
class Admin {

    public function __construct() {
        $host_name = 'localhost';
        $user_name = 'root';
        $password = '';
        $database_name = 'asbhisab';
        $db_connect = mysqli_connect($host_name, $user_name, $password);

        if ($db_connect) {
            $db_select = mysqli_select_db($db_connect, $database_name);
            if ($db_select) {
                //echo 'Database is Selected';
                return $db_connect;
            } else {
                die("Sorry Database is not selected." . mysqli_error($db_connect));
            }
        } else {
            die("Sorry Database is not connected." . mysqli_error($db_connect));
        }
    }

//****#####--Database Cunnection Function End--####*** 

//****#####--Admin Login Function Start--####***

    public function admin_login_check($data) {

        $db_connect = $this->__construct();
        date_default_timezone_set('Asia/Dhaka');
        $time=time()+10;

        $password = md5($data['password']);
        $sql = "SELECT * FROM tbl_user WHERE user_name ='$data[user_name]' AND password='$password' AND access_permission='0' ";
        $query_result = mysqli_query($db_connect, $sql);
        
        $sql1 = "UPDATE `tbl_user` SET last_login = $time WHERE  user_name ='$data[user_name]'";
        $query = mysqli_query($db_connect, $sql1);

        if ($query_result) {
            $row = mysqli_fetch_assoc($query_result);
            if ($row) {
                $_SESSION['first_name'] = $row['first_name'];
                $_SESSION['last_name'] = $row['last_name'];
                $_SESSION['user_id']   = $row['user_id'];
                $_SESSION['user_name']   = $row['user_name'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['user_type'] = $row['user_type'];
                $_SESSION['profile_pic'] = $row['profile_pic'];
                $_SESSION['branch_id'] = $row['branch_id'];
                $_SESSION['unique_id'] = $row['unique_id'];
                $_SESSION['access_permission'] = $row['access_permission'];
                header('Location: welcome.php');
            } if($row['access_permission'] = 1){
                $message= "Sorry You have no Access to this site";
                return $message;
            }
            else {
                $ex_message = "Please use valid User Name and password";
                return $ex_message;
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


//****#####--Admin Login Function End--####*** 

}
