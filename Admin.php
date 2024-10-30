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
//****#####--Common Method Function start--####***
    public function getOneCol($col, $tbl, $comCol, $comVal) {
        $db_connect = $this->__construct();

        $sql = "SELECT $col FROM $tbl WHERE $comCol='$comVal' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            $row = mysqli_fetch_array($result);
            return $row[$col];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function getOneColBranch($col, $tbl, $comCol, $comVal,$branch) {
        $db_connect = $this->__construct();

        $sql = "SELECT $col FROM $tbl WHERE $comCol='$comVal' AND branch_id='$branch'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            $row = mysqli_fetch_array($result);
            return $row[$col];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function getOneColDist($col, $tbl, $comCol, $comVal,$dist) {
        $db_connect = $this->__construct();

        $sql = "SELECT $col FROM $tbl WHERE $comCol='$comVal' AND distributor_id='$dist'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            $row = mysqli_fetch_array($result);
            return $row[$col];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function getOneColRand($col, $tbl) {
        $db_connect = $this->__construct();

        $sql = "SELECT $col FROM $tbl WHERE user_id = '$_SESSION[user_id]' order by id desc limit 1";
        if (mysqli_query($db_connect, $sql)) {
               $query_result = mysqli_query($db_connect, $sql);
                if ($query_result) {

                    foreach($query_result as $rand){            
                        if(empty($rand))
                            $rand_invs = 0;
                        else
                            $rand_invs = $rand['rand_inv'];

                    }
                    return $rand_invs + 1;
                }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Admin Function Start--####***  
    public function fetchRows($sql) {
        $db_connect = $this->__construct();
        $arr = array();
        if (mysqli_query($db_connect, $sql)) {
            $res = mysqli_query($db_connect, $sql);
            while ($row = mysqli_fetch_array($res)) {
                $arr[] = $row;
            }
            return $arr;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Common Method Function End--####***
    public function execute_query($sql) {
        $db_connect = $this->__construct();

        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function getTotalRows($sql) {
        $db_connect = $this->__construct();

        if (mysqli_query($db_connect, $sql)) {
            $res = mysqli_query($db_connect, $sql);
            $NUM = mysqli_num_rows($res);
            return $NUM;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

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
                header('Location: inventory.php');
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
