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

    //****#####--Show All Company Branch Function start--####***
    public function show_purchase_cheque_payment_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####*** 
//****#####--Update All Location info Function Start--####***
    public function update_approvial_status_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_sale_invoice` SET approved_status = '1' WHERE  id = '$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Cheque Approved successfully";
            header('Location: inventory.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update All Location info Function End--####***
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
//****#####--Show All Company Branch Function start--####***
    public function show_sales_cheque_payment_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####*** 
//****#####--Update All Location info Function Start--####***
    public function update_purchase_approvial_status_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_productpurcheseinvoice_info` SET approved_status = '1' WHERE  id = '$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Cheque Approved successfully";
            header('Location: inventory.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update All Location info Function End--####***
//****#####--Admin Logout Function Start--####***

    
    public function active_user() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_user` ORDER BY last_login DESC";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function active_user_number() {
        $db_connect = $this->__construct();
        $time=time();

        $sql = "SELECT * FROM `tbl_user` WHERE last_login >= $time ORDER BY last_login DESC";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_last_login($user_id) {
        $db_connect = $this->__construct();
        date_default_timezone_set('Asia/Dhaka');
        $time=time()+10;

        $sql = "UPDATE `tbl_user` SET last_login = $time WHERE user_id =$user_id";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Admin Logout Function End--####*** 
//****#####--Save User Type Function Start--####***
    public function save_user_type_info($data) {
        $db_connect = $this->__construct();

        $user_type = $data[user_type];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_user_type WHERE user_type ='" . $user_type . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry User Type is unique . This User Type is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_user_type` (user_type,status) VALUES ('$data[user_type]','$data[status]')";

            if (mysqli_query($db_connect, $sql)) {
                $message = "User Type Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }
     public function save_distributor_info($data) {
        $db_connect = $this->__construct();
      $distributor_code = $data[distributor_code];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_distributor WHERE distributor_code ='" . $distributor_code . "'");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Dealer Code is unique . This Distributor Code is alrady exits !!</span>";
            return $message;
        }else{
            $sql = "INSERT INTO `tbl_distributor` (branch_id,distributor_name,contact_num,address,distributor_code,status,openig_balance,dues,date) VALUES ('$data[branch_id]','$data[distributor_name]','$data[contact_number]','$data[address]','$data[distributor_code]','$data[status]','$data[openig_balance]','$data[openig_balance]','$data[date]')";

            if (mysqli_query($db_connect, $sql)) {
                $message = "Distributor Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }            
        }

    }   

//****#####--Save User Type Function End--####*** 
//****#####--Manage User Type Function Start--####***
    public function select_all_user_type_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_user_type` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_all_distributor_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_distributor` WHERE branch_id = '$_SESSION[branch_id]' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_serial_number_info_info($branchID) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_serial_number` WHERE serial_number != pro_model AND inStock != 0 AND deletion_status= 0 AND branch_id = '$branchID'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
//****#####--Manage User Type Function End--####*** 
//****#####--Show User Type Function Start--####***
    public function show_user_type_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_user_type` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show User Type Function End--####*** 
//****#####--Delete User Type Function Start--####*** 
    public function delete_user_type($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_user_type` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_distributor($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_distributor` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

    public function show_distributor_info_by_id($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_distributor` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function update_distributor_info_by_id($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_distributor` 
              SET   " . "distributor_name = '$data[distributor_name]',
              " . "branch_id = '$data[branch_id]',
              " . "contact_num = '$data[contact_number]',
              " . "address = '$data[address]',
              " . "opening_balance = '$data[opening_balance]',
              " . "dues = '$data[opening_balance]',
              " . "distributor_code = '$data[distributor_code]',
                    " . "status = '$data[status]'" . " 
              WHERE  id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Distributor Updated successfully";
            header('Location: manage_distributor.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
//****#####--Delete User Type Function End--####*** 
//****#####--Update User Type Function Start--####*** 
    public function update_user_type_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_user_type` 
              SET   " . "user_type = '$data[user_type]', 
                    " . "status = '$data[status]'" . " 
              WHERE  id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " User Type Update successfully";
            header('Location: manage_user_type.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update User Type Function End--####*** 
//****#####--Select Active User Type Function start--####*** 
    public function select_all_active_user_type() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_user_type` WHERE deletion_status= 0 AND status = 1 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active User Type Function End--####***      
//****#####--Save User Function Start--####***
    public function save_user_info($data) {
        $db_connect = $this->__construct();

        $directory = 'profile_pic/';
        $ran_id = rand(time(), 100000000);
        $target_file = $directory . $_FILES['profile_pic']['name'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_size = $_FILES['profile_pic']['size'];
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        if ($check) {
            if (file_exists($target_file)) {
                $massage2 = "This file is already exists. please try new one";
                return $massage2;
            } else {
                if ($file_size > 10000000) {
                    $massage2 = "File is too large. please try new one";
                    return $massage2;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png') {
                        $massage2 = "File type is not valid. please try new one";
                        return $massage2;
                    } else {
                        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);

                        $access_permission = $_POST['access_permission'];
                        $chk1 = "";
                        foreach ($access_permission as $chk2) {
                            $chk1 .= $chk2 . ",";
                        }
                        $rest1 = substr($chk1, 0, strlen($chk1) - 1);

                        $password = md5($data['password']);
                        $confirm_password = md5($data['confirm_password']);

                        $password = md5($data['password']);
                        $confirm_password = md5($data['confirm_password']);
                        if ($password == $confirm_password) {
                            $user_name = $data[user_name];
                            $query = mysqli_query($db_connect, "SELECT * FROM tbl_user WHERE user_name ='" . $user_name . "' AND deletion_status = 0 ");
                            if (mysqli_num_rows($query) > 0) {
                                $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry User Name is unique . This User Name is alrady exits !!</span>";
                                return $message;
                            } else {
                                $sql = "INSERT INTO `tbl_user` (first_name,last_name,user_name,full_name,profile_pic,branch_id,password,confirm_password,user_type,status,unique_id,access_permission) VALUES ('$data[firstname]','$data[lastname]','$data[user_name]', '$data[full_name]', '$target_file','$data[branch_id]','$password','$confirm_password','$data[user_type]','$data[status]','$ran_id','0')";
                                if (mysqli_query($db_connect, $sql)) {
                                    $message = "User  info save successfully";
                                    return $message;
                                }
                            }
                        } else {
                            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Password is Not Matching !!</span>";
                            return $message;
                        }
                    }
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    //****#####--Save User Function Start--####***
    public function update_profile_pic_info($data) {
        $db_connect = $this->__construct();

        $directory = 'profile_pic/';
        $target_file = $directory . $_FILES['profile_pic']['name'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_size = $_FILES['profile_pic']['size'];
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        if ($check) {
            if (file_exists($target_file)) {
                $massage2 = "This file is already exists. please try new one";
                return $massage2;
            } else {
                if ($file_size > 10000000) {
                    $massage2 = "File is too large. please try new one";
                    return $massage2;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png') {
                        $massage2 = "File type is not valid. please try new one";
                        return $massage2;
                    } else {
                        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);
                        $user_id = $data['user_id'];
                        $full_name = $data['full_name'];
                        $sql = "UPDATE `tbl_user` 
                                    SET   " . "full_name = '$full_name', 
                                     " . "profile_pic = '$target_file'" . " 
                                     WHERE  user_id = '$user_id'";

                        if (mysqli_query($db_connect, $sql)) {
                            $message = "User  info save successfully";
                            return $message;
                        }
                    }
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save User Function End--####***     
//****#####--Save User Function End--####***  
    public function update_password_info($data) {
        $db_connect = $this->__construct();
        $user_id = $data[user_id];
        $old = md5($data['oldPass']);
        $password = md5($data['newPassword']);
        $newpassword = md5($data['confirmnewPassword']);
        $query = mysqli_query($db_connect, "SELECT password FROM tbl_user WHERE user_id ='" . $user_id . "' AND deletion_status = 0 ");
        if ($query) {
            $row = mysqli_fetch_assoc($query);
            if ($row) {
                $oldpass2 = $row['password'];
                if ($oldpass2 == $old) {
                    if ($password == $newpassword) {
                        $sql = "UPDATE `tbl_user` 
                        SET   " . "password = '$password', 
                        " . "confirm_password = '$newpassword'" . " 
                            WHERE  user_id = '$user_id'";

                        if (mysqli_query($db_connect, $sql)) {
                            $message = "<span style='color: white;background:#009933;padding:8px;'>Password Change Successfully  !!</span>";
                            return $message;
                        } else {
                            die('Query Problem' . mysqli_error($db_connect));
                        }
                    } else {
                        $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry confirm password is not matching !!</span>";
                        return $message;
                    }
                } else {
                    $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry your current password is not matching !!</span>";
                    return $message;
                }
            }
        } else {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry This password is not this user !!</span>";
            return $message;
        }
    }

//****#####--Select User Function Start--####***    
    public function select_all_user_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_user` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select User Function End--####*** 
//****#####--Delete User Function Start--####*** 
    public function delete_user_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_user` WHERE user_id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id = '$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete User Function End--####***  
//****#####--Show User  Function Start--####***
    public function show_user_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_user` WHERE user_id = '$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_model_number_info_info($branchID) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_serial_number` WHERE deletion_status= 0 AND branch_id='$branchID' AND serial_number = pro_model ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
//****#####--Show User  Function End--####*** 
////****#####--Update user  Function Start--####***
    public function update_user_info($data) {
        $db_connect = $this->__construct();
        $access_permission = $_POST['access_permission'];
        $chk1 = "";
        foreach ($access_permission as $chk2) {
            $chk1 .= $chk2 . ",";
        }
        $rest1 = substr($chk1, 0, strlen($chk1) - 1);

        $sql = "UPDATE `tbl_user` SET "
                . "user_name = '$data[user_name]',"
                . "full_name = '$data[full_name]',"
                . "branch_id = '$data[branch_id]',"
                . "user_type = '$data[user_type]',"
                . "status = '$data[status]',"
                . "access_permission = '$rest1'"
                . " WHERE user_id='$data[user_id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " User Info successfully";
            header('Location: manage_user.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

////****#####--Update user Function End--####***  
//****#####--Save Company Function Start--####***
    public function save_company_info($data) {
        $db_connect = $this->__construct();

        $directory = 'company_logo/';
        $target_file = $directory . $_FILES['company_logo']['name'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_size = $_FILES['company_logo']['size'];
        $check = getimagesize($_FILES['company_logo']['tmp_name']);
        if ($check) {
            if (file_exists($target_file)) {
                $message = "<span style='color: white;background:#f44336;padding:8px;'>This file is already exists. please try new one !!</span>";
                return $message;
            } else {
                if ($file_size > 10000000) {
                    $message = "<span style='color: white;background:#f44336;padding:8px;'>File is too large. please try new one !!</span>";
                    return $message;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png') {
                        $message = "<span style='color: white;background:#f44336;padding:8px;'>File type is not valid. please try new one !!</span>";
                        return $message;
                    } else {
                        move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_file);

                        $company_name = $data['company_name'];
                        $query = mysqli_query($db_connect, "SELECT * FROM tbl_company WHERE company_name ='" . $company_name . "' AND deletion_status = 0 ");
                        if (mysqli_num_rows($query) > 0) {
                            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Company Name is unique . This Company is alrady exits !!</span>";
                            return $message;
                        } else {
                            $sql = "INSERT INTO `tbl_company` (company_name,email_address,company_logo,address,mob_number,status) VALUES ('$data[company_name]', '$data[email_address]', '$target_file','$data[address]','$data[mob_number]','$data[status]')";
                            if (mysqli_query($db_connect, $sql)) {
                                $message = "Company info save successfully";
                                return $message;
                            }
                        }
                    }
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Company Function END--####***
//****#####--Save Company Function Start--####***
    public function update_company_logo_info($data) {
        $db_connect = $this->__construct();

        $directory = 'company_logo/';
        $target_file = $directory . $_FILES['company_logo']['name'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_size = $_FILES['company_logo']['size'];
        $check = getimagesize($_FILES['company_logo']['tmp_name']);
        if ($check) {
            if (file_exists($target_file)) {
                $message = "<span style='color: white;background:#f44336;padding:8px;'>This file is already exists. please try new one !!</span>";
                return $message;
            } else {
                if ($file_size > 10000000) {
                    $message = "<span style='color: white;background:#f44336;padding:8px;'>File is too large. please try new one !!</span>";
                    return $message;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png') {
                        $message = "<span style='color: white;background:#f44336;padding:8px;'>File type is not valid. please try new one !!</span>";
                        return $message;
                    } else {
                        move_uploaded_file($_FILES['company_logo']['tmp_name'], $target_file);

                        $company_id = $data[company_id];

                        $sql = "UPDATE `tbl_company` 
                                    SET   " . "company_logo = '$target_file'" . " 
                                     WHERE  id = '$company_id'";

                        if (mysqli_query($db_connect, $sql)) {
                            $message = "Company Logo Update successfully";
                            return $message;
                        }
                    }
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Company Function END--####***
//****#####--Select Company Function Start--####***
    public function select_all_company_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_company` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Company Function END--####***
//****#####--Delete Company Function Start--####***
    public function delete_company_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_company` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id = '$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Company Function END--####***
//****#####--Select Active Company Function Start--####***
    public function select_all_active_company() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_company` WHERE deletion_status= 0 AND status = 1 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Company Function End--####***
//****#####--Show Active Company Function Start--####***
    public function show_company_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_company` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Active Company Function End--####***
////****#####--Update Company Function Start--####***
    public function update_company_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_company` SET "
                . "company_name = '$data[company_name]',"
                . "email_address = '$data[email_address]',"
                . "address = '$data[address]',"
                . "mob_number = '$data[mob_number]',"
                . "status = '$data[status]'"
                . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Company Info successfully";
            header('Location: manage_campany.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

////****#####--Update company Function End--####***  
//****#####--Save Company Branch info Function Start--####***
    public function save_branch_info($data) {

        $db_connect = $this->__construct();

        $directory = 'branch_logo/';
        $target_file = $directory . $_FILES['logo']['name'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_size = $_FILES['logo']['size'];
        $check = getimagesize($_FILES['logo']['tmp_name']);
        if ($check) {
            if (file_exists($target_file)) {
                $message = "<span style='color: white;background:#f44336;padding:8px;'>This file is already exists. please try new one !!</span>";
                return $message;
            } else {
                if ($file_size > 10000000) {
                    $message = "<span style='color: white;background:#f44336;padding:8px;'>File is too large. please try new one !!</span>";
                    return $message;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png') {
                        $message = "<span style='color: white;background:#f44336;padding:8px;'>File type is not valid. please try new one !!</span>";
                        return $message;
                    } else {
                        move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);

                        $company_name = $data['company_name'];
                        $query = mysqli_query($db_connect, "SELECT * FROM tbl_company WHERE company_name ='" . $company_name . "' AND deletion_status = 0 ");
                        if (mysqli_num_rows($query) > 0) {
                            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Company Name is unique . This Company is alrady exits !!</span>";
                            return $message;
                        } else {
                           $sql = "INSERT INTO `tbl_branch` (company_id,email_address,branch_name,postal_code,location,address,mob_number,status,logo) VALUES ('$data[company_id]','$data[email_address]','$data[branch_name]','$data[postal_code]','$data[location]','$data[address]','$data[mob_number]','$data[status]','$target_file')";
                            if (mysqli_query($db_connect, $sql)) {
                                $message = "Branch Info Save Successfully";
                                return $message;
                            } else {
                                die('Query problem' . mysqli_error($db_connect));
                            }
                        }
                    }
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }


        // $sql = "INSERT INTO `tbl_branch` (company_id,email_address,branch_name,postal_code,location,address,mob_number,status) VALUES ('$data[company_id]','$data[email_address]','$data[branch_name]','$data[postal_code]','$data[location]','$data[address]','$data[mob_number]','$data[status]')";
        // if (mysqli_query($db_connect, $sql)) {
        //     $message = "Branch Info Save Successfully";
        //     return $message;
        // } else {
        //     die('Query problem' . mysqli_error($db_connect));
        // }
    }

//****#####--Save Company Branch info Function End--####***
//****#####--Select Active Company Branch Function Start--####***
    public function select_all_active_branch() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_branch` WHERE deletion_status= 0 AND status = 1 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_all_active_distributor($branchID) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_distributor` WHERE branch_id= '$branchID'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_purchase_return_info($branch_id){
         $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_return_invoice` WHERE deletion_status= 0 AND branch_id = '$branch_id' AND date = DATE(NOW()) ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
}
public function show_pur_ret_info($invID){
          $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_purchase_return_info` WHERE deletion_status= 0 AND common_id = '$invID' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
}
//****#####--Select Active Company Branch Function End--####***
//****#####--Select All Company Branch Function Start--####***
    public function select_all_branch_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_branch` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select All Company Branch Function End--####***
//****#####--Show All Company Branch Function start--####***
    public function show_branch_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_branch` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####***
//****#####--Update Company Branch Function Start--####***
    public function update_branch_info($data) {
        $db_connect = $this->__construct();
        if(!empty($_FILES['logo']))
        {
            $directory = 'branch_logo/';
            $target_file = $directory . $_FILES['logo']['name'];
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
            $file_size = $_FILES['logo']['size'];
            $check = getimagesize($_FILES['logo']['tmp_name']);
            if ($check) {
            if (file_exists($target_file)) {
                $message = "<span style='color: white;background:#f44336;padding:8px;'>This file is already exists. please try new one !!</span>";
                return $message;
            } else {
                if ($file_size > 10000000) {
                    $message = "<span style='color: white;background:#f44336;padding:8px;'>File is too large. please try new one !!</span>";
                    return $message;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png') {
                        $message = "<span style='color: white;background:#f44336;padding:8px;'>File type is not valid. please try new one !!</span>";
                        return $message;
                    } else {
                       
             $sql = "UPDATE `tbl_branch` 
              SET   " . "company_id = '$data[company_id]', 
                    " . "email_address = '$data[email_address]', 
                    " . "branch_name = '$data[branch_name]', 
                    " . "postal_code = '$data[postal_code]', 
                    " . "location = '$data[location]', 
                    " . "address = '$data[address]',
                    " . "logo = '".addslashes($target_file)."', 
                    " . "mob_number = '$data[mob_number]', 
                    " . "status = '$data[status]'" . " 
                    WHERE  id='$data[id]'";
                            if (mysqli_query($db_connect, $sql)) {
                                 move_uploaded_file($_FILES['logo']['tmp_name'], $target_file);
                                $_SESSION['message'] = " Branch Info update successfully";
                            header('Location: manage_branch.php');
                            } else {
                                die('Query problem' . mysqli_error($db_connect));
                            }
                
                    }
                }
            }
        }
        }
        else{
            $sql = "UPDATE `tbl_branch` 
              SET   " . "company_id = '$data[company_id]', 
                    " . "email_address = '$data[email_address]', 
                    " . "branch_name = '$data[branch_name]', 
                    " . "postal_code = '$data[postal_code]', 
                    " . "location = '$data[location]', 
                    " . "address = '$data[address]', 
                    " . "mob_number = '$data[mob_number]', 
                    " . "status = '$data[status]'" . " 
                    WHERE  id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Branch Info update successfully";
            header('Location: manage_branch.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
        }
        
    }

    public function select_all_active_product_code() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product` WHERE deletion_status= 0 AND branch_id = '$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_active_product_code_wo_br() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product` WHERE deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
//****#####--Update Company Branch Function End--####***
//****#####--Delete All Company Branch Function Start--####***
    public function delete_branch_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_branch` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id = '$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete All Company Branch Function End--####***    
//****#####--Show All Company Branch Function start--####***
    public function show_purches_prodyct_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_invoice_info` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


//****#####--Show All Company Branch Function End--####***   
//****#####--Save Group info Function Start--####***
    public function save_product_group_info($data) {

        $db_connect = $this->__construct();
        $group = $data[pro_group_name];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_group WHERE pro_group_name ='" . $group . "' AND branch_id = '$_SESSION[branch_id]' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this Group is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_group` (pro_group_name,status,branch_id) VALUES ('$data[pro_group_name]','$data[status]','$_SESSION[branch_id]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Product Group Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Group info Function End--####*** 
//****#####--Save Group info Function Start--####***
    public function save_product_supplier_group_info($data) {

        $db_connect = $this->__construct();
        $sup_group_name = $data[sup_group_name];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_supplier_group WHERE sup_group_name ='" . $sup_group_name . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry This Supplier Group is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_supplier_group` (sup_group_name,status) VALUES ('$data[sup_group_name]','$data[status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Supplier Group Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Group info Function End--####*** 
//****#####--Save Group info Function Start--####***
    public function save_customer_group_info($data) {

        $db_connect = $this->__construct();
        $cus_group_name = $data[cus_group_name];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_customer_group WHERE cus_group_name ='" . $cus_group_name . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry This Customer Group is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_customer_group` (cus_group_name,branch_id,status) VALUES ('$data[cus_group_name]','$data[branch_id]','$data[status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Customer Group Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }
     public function select_customer_group_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_customer_group` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_customer_group_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_customer_group` WHERE deletion_status= 0 AND id='$id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
    }

//****#####--Save Group info Function End--####*** 
//****#####--Manage Group Function Start--####***
    public function select_product_group_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_group` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Group Function End--####*** 
//****#####--Show All Company Branch Function start--####***
    public function show_pro_group_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_group` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####***  
//****#####--Update All Location info Function Start--####***
    public function update_pro_group_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_group` 
              SET   " . "pro_group_name = '$data[pro_group_name]', 
                    " . "status = '$data[status]'" . " 
              WHERE  id = '$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Product Group info successfully";
            header('Location: manage_product_group.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_customer_group_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_customer_group` 
              SET   " . "cus_group_name = '$data[cus_group_name]', 
                    " . "status = '$data[status]'" . " 
              WHERE  id = '$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Customer Group info Update successfully";
            header('Location: manage_customer_group.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update All Location info Function End--####***
//****#####--Delete Group Function Start--####*** 
    public function delete_product_group($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_product_group` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Group Function End--####***    
//****#####--Delete Group Function Start--####*** 
    public function delete_serial_number($id) {
        $db_connect = $this->__construct();
         $pm = $this->getOneCol('pro_model','tbl_destribution_serial_number','id',$id);
         $sl = $this->getOneCol('serial_number','tbl_destribution_serial_number','id',$id);
         $invoice_number = $this->getOneCol('invoice_number','tbl_destribution_serial_number','id',$id);
        $qt = $this->getOneCol('quantity','tbl_purchase_invoice_info','invoice_number',$invoice_number);
         $stc = $this->getOneColBranch('inStock','tbl_destribution_product','pro_model',$pm,$_SESSION['branch_id']);
         $qty = $qt - 1;
         $stock = $stc - 1;
        $sql = "DELETE FROM `tbl_destribution_serial_number` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $sql2 = "UPDATE `tbl_purchase_invoice_info` SET quantity = '$qty' WHERE invoice_number = '$invoice_number' AND branch_id= '$_SESSION[branch_id]' ";
             $sql3 = "UPDATE `tbl_destribution_product` SET inStock = '$stock' WHERE pro_model = '$pm' AND branch_id= '$_SESSION[branch_id]' ";
             $rs = mysqli_query($db_connect, $sql2);
             if($rs){
                mysqli_query($db_connect, $sql3);
               $_SESSION["message"] = 'Delete Successfully';
               
             }
            
             else{
               die('Query Problem' . mysqli_error($db_connect));
                return false;  
             }
               
            header("Location: {$_SERVER['HTTP_REFERER']}");
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Group Function End--####***    
//****#####--Save Brand info Function Start--####***
    public function save_product_brand_info($data) {

        $db_connect = $this->__construct();
        $brand = $data[pro_brand_name];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_brand WHERE pro_brand_name ='" . $brand . "' AND branch_id = '$_SESSION[branch_id]' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this Brand is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_brand` (pro_brand_name,status,branch_id) VALUES ('$data[pro_brand_name]','$data[status]','$_SESSION[branch_id]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Product Brand Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Brand info Function End--####***  
//****#####--Manage Group Function Start--####***
    public function select_product_brand_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_brand` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Group Function End--####*** 
//****#####--Delete Group Function Start--####*** 
    public function delete_product_brand($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_product_brand` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
     public function delete_customer_group($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_customer_group` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Group Function End--####***   
//****#####--Show All Company Branch Function start--####***
    public function show_pro_brand_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_brand` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####*** 
//****#####--Update All Location info Function Start--####***
    public function update_pro_brand_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_brand` 
              SET   " . "pro_brand_name = '$data[pro_brand_name]', 
                    " . "status = '$data[status]'" . " 
              WHERE  id = '$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Update Product Brand info successfully";
            header('Location: manage_product_brand.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update All Location info Function End--####*** 
//****#####--Save Category info Function Start--####***
    public function save_product_category_info($data) {

        $db_connect = $this->__construct();
        $proCat = $data[product_category];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_category WHERE product_category ='" . $proCat . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this Category is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_category`(product_category,status) VALUES ('$data[product_category]','$data[status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Product Category  Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Category info Function End--####***  
//****#####--Manage Group Function Start--####***
    public function select_product_catrgory_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_category` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Group Function End--####***  
//****#####--Delete Group Function Start--####*** 
    public function delete_product_category($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_product_category` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Group Function End--####***   
//****#####--Show All Company Branch Function start--####***
    public function show_pro_category_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_category` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####*** 
//****#####--Update All Location info Function Start--####***
    public function update_pro_category_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_category` 
              SET   " . "product_category = '$data[product_category]', 
                    " . "status = '$data[status]'" . " 
              WHERE  id = '$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Update Product Category info successfully";
            header('Location: manage_product_category.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update All Location info Function End--####*** 
//****#####--Save Model info Function Start--####***
    public function save_product_model_info($data) {

        $db_connect = $this->__construct();
        $model = $data[product_model];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_model WHERE product_model ='" . $model . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this Model is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_model`(product_model,status) VALUES ('$data[product_model]','$data[status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Product Model Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Model info Function End--####***
//****#####--Manage Group Function Start--####***
    public function select_product_model_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_model` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Group Function End--####*** 
//****#####--Show All Company Branch Function start--####***
    public function show_pro_model_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_model` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####***  
//****#####--Update All Location info Function Start--####***
    public function update_pro_model_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_model` 
              SET   " . "product_model = '$data[product_model]', 
                    " . "status = '$data[status]'" . " 
              WHERE  id = '$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Product Model info successfully";
            header('Location: manage_pro_model.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update All Location info Function End--####***
//****#####--Delete Group Function Start--####*** 
    public function delete_product_model($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_product_model` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Group Function End--####***       
//****#####--Save Supplier info Function Start--####***
  public function sendSMS($from, $sms, $to) {
       $user = urlencode('hairlife285');
       $password = urlencode('hlair@19209');
       $sender = urlencode($from);
       $sms = urlencode($sms);
       $to = urlencode($to);
       $api_params = '?user=' . $user . '&password=' . $password . '&sender=' . $sender . '&SMSText=' . $sms . '&GSM=' . $to;
       $smsGatewayUrl = "http://app.planetgroupbd.com/api/sendsms/plain";
       $smsgatewaydata = $smsGatewayUrl . $api_params;
       $url = $smsgatewaydata;
       
       $ch = curl_init();                       
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_POST, 1);

       $output = curl_exec($ch);
       curl_close($ch);
       if (!$output) {
           $output = file_get_contents($smsgatewaydata);
       }
       return $output;
   }
    public function save_product_supplier_info($data) {

        $db_connect = $this->__construct();

        $supplier_code = $data[supplier_code];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_supplier WHERE supplier_code ='" . $supplier_code . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this supplier is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_supplier` (supplier_group,supplier_name,contact_num,supplier_code,address,opening_balance,paymentCount,status,branch_id) VALUES ('$data[supplier_group]','$data[supplier_name]','$data[contact_num]','$data[supplier_code]','$data[address]','$data[opening_balance]','$data[opening_balance]','$data[status]','$_SESSION[branch_id]')";
            if (mysqli_query($db_connect, $sql)) {
                 $this->sendSMS('Hairlife',$data['msg'], '88'.$data['contact_num']);
                $message = "Product Supplier Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Supplier info Function End--####*** 
//****#####--Manage Group Function Start--####***
    public function select_product_supplier_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_supplier` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Group Function End--####*** 
//****#####--Delete Group Function Start--####*** 
    public function delete_product_supplier($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_product_supplier` WHERE id = '$id'";

        //$sql = "UPDATE `tbl_user` SET deletion_status=0 WHERE user_id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Group Function End--####***       
//****#####--Show All Company Branch Function start--####***
    public function show_pro_supplier_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_supplier` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####*** 
//****#####--Save Purches Product Function Start--####***   
    public function save_product_info($data) {
        $db_connect = $this->__construct();

        $product_code = $data[product_code];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product WHERE product_code ='" . $product_code . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Product Code is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product`
                       (pro_group_id,
                       pro_cat,
                       pro_desc,
                       rp,
                       mrp,
                       product_brand_id,
                       pro_model_id,
                       product_unit_type,
                       product_code,
                       red_level,
                       ref_product,
                       pro_status)
                        VALUES 
                        ('$data[pro_group_id]',
                        '$data[pro_cat]',
                        '$data[pro_desc]',
                        '$data[rp]',
                        '$data[mrp]',
                        '$data[product_brand_id]',
                        '$data[pro_model_id]',
                        '$data[product_unit_type]',
                        '$data[product_code]',
                        '$data[red_level]',
                        '$data[ref_product]',
                        '$data[pro_status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Product Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Product Function End--####***  
//****#####--Update user  Function Start--####***
    public function update_supplier_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_supplier` SET "
               . "supplier_name = '$data[supplier_name]',"
               . "contact_num = '$data[contact_num]',"
               . "address = '$data[address]',"
               . "opening_balance = '$data[opening_balance]',"
               . "paymentCount = '$data[opening_balance]',"
               . "status = '$data[status]',"
               . "branch_id = '$_SESSION[branch_id]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Update Supplier Info successfully";
            header('Location: manage_product_supplier.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update user Function End--####***  
//****#####--Save Supplier info Function Start--####***
    public function save_product_seller_info($data) {

        $db_connect = $this->__construct();
        $seller_name = $data[seller_name];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_add_seller WHERE seller_name ='" . $seller_name . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this buyer is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_add_seller` (seller_name,contact_num,address,previousDues,status) VALUES ('$data[seller_name]','$data[contact_num]','$data[address]','$data[previousDues]','$data[status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Product Seller Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Supplier info Function End--####*** 
//****#####--Manage User Type Function Start--####***
    public function select_product_details_info($product_code) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE pCode LIKE '%$product_code%' AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage User Type Function End--####*** 
//****#####--Manage User Type Function Start--####***
    public function select_product_Purdetails_info($product_code) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE product_code LIKE '%$product_code%' AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage User Type Function End--####***   
//****#####--Select Active Group Type Function start--####*** 
    public function select_all_active_product_group() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_group` WHERE deletion_status= 0 AND branch_id = '$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_active_product_group_wo_br() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_group` WHERE deletion_status= 0 AND branch_id != 2 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    

//****#####--Select Active Group Type Function End--####***
//****#####--Select Active Group Type Function start--####*** 
    public function select_all_active_supplier_group() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_supplier_group` WHERE deletion_status= 0 AND status = 1 order by id desc ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Group Type Function End--####***  
//****#####--Select Active Group Type Function start--####*** 
    public function select_all_purches_product_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_invoice_info` WHERE deletion_status = 0 AND status_flag = 0 and user_id ='$_SESSION[user_id]' AND branch_id = '$_SESSION[branch_id]' ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Group Type Function End--####***  
//****#####--Select Active Group Type Function start--####*** 
    public function show_all_purches_product_info($rand) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_invoice_info` WHERE deletion_status= 0 AND rand_inv = '$rand' ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Group Type Function End--####***  
//****#####--Select Active Brand Type Function start--####*** 
    public function select_all_active_product_brand() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_brand` WHERE deletion_status= 0 AND branch_id='$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_active_product_brand_wo_br() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_brand` WHERE deletion_status= 0 AND branch_id != 2 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
//****#####--Select Active Brand Type Function End--####***   
//****#####--Select Active Brand Type Function start--####*** 
    public function select_all_active_product() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE deletion_status= 0 order by id desc";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Brand Type Function End--####*** 
//****#####--Select Active Brand Type Function start--####*** 
    public function select_all_active_product_code_serial_no() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE serial_no = 1 order by id desc";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Brand Type Function End--####***    
//****#####--Select Active Brand Type Function start--####*** 
    

//****#####--Select Active Brand Type Function End--####***       
//****#####--Select Active Supplier Type Function start--####*** 
    public function select_all_active_product_seller($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_seller`  ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Supplier Type Function End--####***  
//****#####--Select Active Supplier Type Function start--####*** 
    public function select_all_active_product_supplier() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_supplier` WHERE deletion_status= 0 AND status = 1 ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Supplier Type Function End--####***  
//****#####--Select Active Supplier Type Function start--####*** 
    public function select_all_active_product_buyer() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_seller` WHERE deletion_status= 0 AND status = 1 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Supplier Type Function End--####***   
//****#####--Select Active Material Function start--####*** 
    public function select_all_active_product_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Material Function End--####***  
//****#####--Select Active Invoice Number Function start--####*** 
    public function select_all_active_purchase_invoice_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Invoice Number Function End--####***      
//****#####--Select Active Invoice Number Function start--####*** 
    public function select_all_active_invoice_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Invoice Number Function End--####***    
//****#####--Select Active Invoice Number Function start--####*** 


//****#####--Select Invoice Number Function End--####***       
//****#####--Save Purches Product Function Start--####***   
    public function insertPurchesproduct($product_code, $pro_sup_id, $pro_cat_id, $pro_quanity, $unit_price, $salePrice, $sup_inv_num, $product_brand_id, $pro_model_id, $date, $serial) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_product_puches_info`
                       (product_code,
                       pro_sup_id,
                       pro_cat_id,
                       unit_quantity,
                       unit_price,
                       salePrice,
                       sup_inv_num,
                       product_brand_id,
                       pro_model_id,
                       date,
                       serial_number)
                        VALUES 
                        ('$product_code',
                        '$pro_sup_id',
                        '$pro_cat_id',
                        '$pro_quanity',
                        '$unit_price',
                        '$salePrice',
                        '$sup_inv_num',
                        '$product_brand_id',
                        '$pro_model_id',
                        '$date',
                        '$serial')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Purches Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Product Function End--####***  
//****#####--Save purches Product Common info  Function Start--####***   
    public function insertInvoiceproduct($pro_sup_id, $product_code, $pro_cat_id, $pro_group_id, $remarks, $quantity, $unit_price, $salePrice, $sup_inv_num, $product_brand_id, $pro_model_id, $description, $date, $quantity2, $total_prices) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_purches_pro_comn_info`
                       (pro_sup_id,
                       product_code,
                       pro_cat_id,
                       pro_group_id,
                       remarks,
                       quantity,
                       unit_price,
                       salePrice,
                       sup_inv_num,
                       product_brand_id,
                       pro_model_id,
                       description,
                       date,
                       inStock,
                       total_prices)
                        VALUES 
                        ('$pro_sup_id',
                        '$product_code',
                        '$pro_cat_id',
                        '$pro_group_id',
                        '$remarks',
                        '$quantity',
                        '$unit_price',
                        '$salePrice',
                        '$sup_inv_num',
                        '$product_brand_id',
                        '$pro_model_id',
                        '$description',
                        '$date',
                        '$quantity2',
                        '$total_prices')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Purches Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save purches Product Common info End--####***  
//****#####--Save purches Product Common info  Function Start--####***   
    public function insertProductInfo($product_code, $pro_cat_id, $product_brand_id, $pro_model_id, $pro_group_id, $description, $remarks, $salePrice, $quantity, $unit_price, $inStock, $total_prices, $common_id) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_product_purchase_info`
                       (product_code,
                       pro_cat_id,
                       product_brand_id,
                       pro_model_id,
                       pro_group_id,
                       description,
                       remarks,
                       salePrice,
                       quantity,
                       unit_price,
                       inStock,
                       total_prices,
                       common_id)
                        VALUES 
                        ('$product_code',
                        '$pro_cat_id',
                        '$product_brand_id',
                        '$pro_model_id',
                        '$pro_group_id',
                        '$description',
                        '$remarks',
                        '$salePrice',
                        '$quantity',
                        '$unit_price',
                        '$inStock',
                        '$total_prices',
                        '$common_id')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save purches Product Common info End--####*** 
//****#####--Save purches Product Common info  Function Start--####***   
    public function save_purchase_main_invoice_info($rand_inv, $date, $date_month, $reference_invoice_number, $invoice_number, $product_sup_group, $supplier_id, $totalAmount, $other_discount, $billTotal, $payment_method, $cashPaid, $dues,$branch_id, $chequeAmount, $bankName, $cheque_num, $cheque_app_date) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_productpurcheseinvoice_info`
                       (rand_inv,
                       date,
                       date_month,
                       reference_invoice_number,
                       invoice_number,
                       supplier_group,
                       supplier_id,
                       totalAmount,
                       other_discount,
                       billTotal,
                       payment_method,
                       cashPaid,
                       dues,
                       branch_id,
                       chequeAmount,
                       bankName,
                       cheque_num,
                       cheque_app_date,
                       user_id)
                        VALUES 
                        ('$rand_inv',
                        '$date',
                        '$date_month',
                        '$reference_invoice_number',
                        '$invoice_number',
                        '$product_sup_group',
                        '$supplier_id',
                        '$totalAmount',
                        '$other_discount',
                        '$billTotal',
                        '$payment_method',
                        '$cashPaid',
                        '$dues',
                        '$branch_id',
                        '$chequeAmount',
                        '$bankName',
                        '$cheque_num',
                        '$cheque_app_date'
                        ,'$_SESSION[user_id]')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Product Invoice Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save purches Product Common info End--####***
//****#####--Update purches Product invoice info  Function Start--####***   
    public function updateProductPurchesinvoiceInfo($product_brand, $pro_model, $pro_group, $discount, $description, $salesPrice, $quantity, $unit_price, $inStock, $total_prices, $id) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_purchase_invoice_info` SET product_brand ='$product_brand',pro_model='$pro_model',pro_group ='$pro_group',discount ='$discount',description ='$description',salePrice ='$salesPrice',quantity ='$quantity',unit_price ='$unit_price',inStock ='$inStock',total_prices ='$total_prices' WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Update Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update purches Product invoice info End--####***  
//****#####--Update purches Product Common info  Function Start--####***   
    public function updateProductPurchesInfo($product_brand, $pro_model, $pro_group, $discount, $description,  $salesPrice, $quantity, $unit_price, $inStock, $total_prices, $proID) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destribution_product` SET "
                . "product_brand='$product_brand',"
                . "pro_model='$pro_model',"
                . "pro_group ='$pro_group',"
                . "discount ='$discount',"
                . "description ='$description',"
                . "salePrice ='$salesPrice',"
                . "quantity ='$quantity',"
                . "unit_price ='$unit_price',"
                . "inStock ='$inStock',"
                . "total_prices ='$total_prices'"
                . " WHERE id = '$proID'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Update Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function updateProductPurchesInfo_serial($pro_model,$back_pro_model) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destribution_serial_number` SET pro_model='$pro_model',serial_number = '$pro_model' WHERE pro_model = '$back_pro_model' AND branch_id = '$_SESSION[branch_id]' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Update Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function updateProductPurchesInfo_serial_mod_only($pro_model,$back_pro_model){
              $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destribution_serial_number` SET pro_model='$pro_model' WHERE pro_model = '$back_pro_model' AND branch_id = '$_SESSION[branch_id]' ";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Update Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }  
    }
            public function show_serial_number_info_by_id($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_serial_number` WHERE id ='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_serial_number($data,$id){
          $db_connect = $this->__construct();
         $ser = $this->getOneCol('serial_number','tbl_destribution_serial_number','serial_number',$data['serial_number']);
         if(empty($ser)){
            $sql = "UPDATE `tbl_destribution_serial_number` SET serial_number = '$data[serial_number]' WHERE id ='$id' ";
            if (mysqli_query($db_connect, $sql)) {
                
                return "Updated Successfully";
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }             
         }else{
            return "Serial Already Exists"; 
         }
  
}

//****#####--Update purches Product Common info End--####***  
//****#####--Select Active Company Function Start--####***
    public function select_all_active_customer($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_seller` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_due_customer($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_due_customer` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_due($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM tbl_customer_due INNER JOIN tbl_due_customer ON tbl_customer_due.customer_id=tbl_due_customer.id WHERE tbl_customer_due.customer_id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function due_customer($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM tbl_due_customer WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_cus($data) {
        $db_connect = $this->__construct();
        $balance = 0 - $data['advance_payment'];
        $sql = "INSERT INTO `tbl_add_seller`(seller_name,nick_name,deli_address,contact_num,address,previousDues,advance_payment,type,status)VALUES('$data[seller_name]','$data[nick_name]','$data[deli_address]','$data[contact_num]','$data[address]','$balance','$data[advance_payment]','$data[type]','1')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Customer added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_due_customer($data) {
        $db_connect = $this->__construct();
        $sql = "INSERT INTO `tbl_due_customer`(customer,mobile,address,balance,active)VALUES('$data[seller_name]','$data[contact_num]','$data[address]','$data[balance]','1')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Customer added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_new_due($data,$id) {
        $db_connect = $this->__construct();
        $sql = "INSERT INTO `tbl_customer_due`(customer_id,particular,due,joma,date)VALUES('$id','$data[particular]','$data[due]','$data[joma]','$data[date]')";
        $update = "UPDATE tbl_due_customer SET balance='$data[new]' WHERE id='$id'";
        $query = mysqli_query($db_connect, $update);
        if (mysqli_query($db_connect, $sql)) {
           
            return "Customer added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_due_cus_id($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM tbl_due_customer WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Company Function End--####*** 
//****#####--Select Active Company Function Start--####***
    public function select_all_active_reference($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_reference` WHERE branch_id = '$branch_id' AND deletion_status= 0 AND status = 1 ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Company Function End--####*** 
//****#####--Select Active Company Function Start--####***
    public function select_all_active_customer_group($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_customer_group` WHERE branch_id = '$branch_id' AND deletion_status= 0 AND status = 1 ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    

//****#####--Select Active Company Function End--####***   
//****#####--Delete Country Function Start--####*** 
    public function delete_saller_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_add_seller` WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION["message"] = 'Delete Successfully';
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_due_customer_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_due_customer` WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            return 'Delete Successfully';
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Country Function End--####***   
//****#####--Show All Company Branch Function start--####***
    public function show_saller_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_seller` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####***
//****#####--Update All Location info Function Start--####***
    public function update_saller_info($data) {
        $db_connect = $this->__construct();
        $balance = 0 - $data['advance_payment'];
        $sql = "UPDATE `tbl_add_seller` SET seller_name = '$data[seller_name]',nick_name = '$data[nick_name]',deli_address = '$data[deli_address]',contact_num = '$data[contact_num]',address = '$data[address]',advance_payment = '$data[advance_payment]',previousDues = '$balance' WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Customer info Updated successfully";
            header('Location: add_saller.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update All Location info Function End--####***
//****#####--Manage Purches Function Start--####***
    public function select_purches_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Purches Function End--####*** 
//****#####--Dashboard Content Function Start--####***
    public function select_all_main_branch_purchase_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE  deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function END--####***
//****#####--Manage Purches Function Start--####***
    public function select_purches_due_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE dues !='' AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Purches Function End--####*** 
//****#####--Manage Purches Function Start--####***
    public function select_daily_purches_info($branch_id,$today_date) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE date = '$today_date' AND branch_id = '$branch_id' AND deletion_status= 0 order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Purches Function End--####***   
//****#####--Show Sales Product Dails Function Start--####***  
    public function show_purchase_invoc_info($invoiceCommonId) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE common_id = '$invoiceCommonId' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Sales Product Dails Function End--####***    
//****#####--Show Sales Product Dails Function Start--####***  
    public function show_purchase_basic_invoc_info($user_id,$rand_inv) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_purchase_invoice_info` WHERE user_id = '$user_id' AND rand_inv = '$rand_inv'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Sales Product Dails Function End--####***       
//****#####--Delete Country Function Start--####*** 
    public function delete_purches($id) {
        $db_connect = $this->__construct();

        $sql = "DELETE a.*, 
                    b.*  
                FROM       tbl_productpurcheseinvoice_info AS a 
                INNER JOIN tbl_purchase_invoice_info       AS b 
                ON         b.common_id = a.common_id 
                WHERE      a.id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Country Function End--####*** 
//****#####--Delete Country Function Start--####*** 
    public function delete_new_purches_product($id) {
        $db_connect = $this->__construct();

        $sql = "DELETE FROM tbl_purchase_invoice_info WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION["message"] = 'Delete Successfully';
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Country Function End--####*** 
//****#####--Show Product Function Start--####***  
    public function show_product_purches_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Product Function End--####*** 
//****#####--Select Active Invoice Number Function start--####*** 
    public function select_all_active_saller() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_seller` WHERE status = '1' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Purches Product Function End--####***    
//****#####--Show Product Function Start--####***  
    public function show_product_purches_genr_info($invNum) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_product_puches_info` WHERE sup_inv_num = '$invNum' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Product Function End--####*** 
////****#####--Update Product Puches General info Function Start--####***
    public function update_product_purches_genrl_info($common_id, $date, $reference_invoice_number, $invoice_number, $product_sup_group, $supplier_id, $totalAmount, $other_discount, $billTotal, $payment_method, $cashPaid, $dues, $predues, $currentDue, $chequeAmount, $bankName, $cheque_num, $cheque_app_date, $id) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_productpurcheseinvoice_info` SET "
                . "common_id ='$common_id',"
                . "date = '$date',"
                . "reference_invoice_number = '$reference_invoice_number',"
                . "invoice_number ='$invoice_number',"
                . "supplier_group ='$product_sup_group',"
                . "supplier_id ='$supplier_id',"
                . "totalAmount = '$totalAmount',"
                . "other_discount ='$other_discount',"
                . "billTotal ='$billTotal',"
                . "payment_method ='$payment_method',"
                . "cashPaid ='$cashPaid',"
                . "dues ='$dues',"
                . "chequeAmount ='$chequeAmount',"
                . "bankName ='$bankName',"
                . "cheque_num ='$cheque_num',"
                . "cheque_app_date ='$cheque_app_date'"
                . " WHERE id='$id'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Product Invoice Info Save Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

////****#####--Update Product Puches General info Function End--####***   
////****#####--Ajax Product Details Info Function Start--####***  
    function geProductInfo($pCode, $branch_id) {
        $db_connect = $this->__construct();

        $sql = "select p.* from tbl_destribution_product as p,tbl_destribution_serial_number as s where (p.pro_model ='$pCode' OR s.serial_number = '$pCode') AND s.branch_id = '$branch_id' AND p.pro_model = s.pro_model  group by p.pro_model";

        $query = mysqli_query($db_connect, $sql);
        if ($query) {
            $arr = mysqli_fetch_assoc($query);

            $salePrice = $arr['salePrice'];
            $description = $arr['description'];
            $inStock = $arr['inStock'];
            $costPrice = $arr['unit_price'];
            $str = $arr['pro_model'];
            $product_model = trim($str);

            if ($salePrice != '' && $inStock != '') {
                return $description . "##" . $salePrice . "##" . $costPrice . "##" . $inStock . "##" . $product_model;
            } else {
                return '';
            }
        }
    }

////****#####--Ajax Product Details Info Function End--####***
////****#####--Ajax Product Details Info Function Start--####***  
    function geProductInfo_for_rp($pCode) {
        $db_connect = $this->__construct();

        $sql = "select p.* from tbl_destribution_product as p,tbl_destribution_serial_number as s where (p.pro_model ='$pCode' OR s.serial_number = '$pCode') AND s.branch_id = '$branch_id' AND p.pro_model = s.pro_model  group by p.pro_model";

        $query = mysqli_query($db_connect, $sql);
        if ($query) {
            $arr = mysqli_fetch_assoc($query);

            $salePrice = $arr['rp'];
            $description = $arr['description'];
            $inStock = $arr['inStock'];
            $costPrice = $arr['unit_price'];
            $str = $arr['pro_model'];
            $product_model = trim($str);

            if ($salePrice != '' && $inStock != '') {
                return $description . "##" . $salePrice . "##" . $costPrice . "##" . $inStock . "##" . $product_model;
            } else {
                return '';
            }
        }
    }

////****#####--Ajax Product Details Info Function End--####***
////****#####--Select Unit Price Function--####***
    function unitPrice() {
        $db_connect = $this->__construct();

        $sql = "SELECT
                  id,unit_price
                FROM `tbl_product_purchase_info`
                ORDER BY unit_price";

        $query = mysqli_query($db_connect, $sql);
        if ($query) {
            return $query;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

////****#####--Ajax Product Details Info Function End--####***     
//****#####--Manage Purches Function Start--####***
    public function select_invoiceID_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Purches Function End--####***    
//****#####--Manage Purches Function Start--####***
    public function select_purInvoiceID_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE deletion_status= 0 || deletion_status = 1 ORDER BY id DESC LIMIT 1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_rand_inv_info() {

    $db_connect = $this->__construct();

    $sql = "SELECT rand_inv FROM `tbl_productpurcheseinvoice_info` WHERE deletion_status= 0 || deletion_status = 1 ORDER BY id DESC LIMIT 1";
    $query_result = mysqli_query($db_connect, $sql);
    if ($query_result) {

        foreach($query_result as $rand){            
            if(empty($rand))
                $rand_invs = 0;
            else
                $rand_invs = $rand['rand_inv'];

        }
        return $rand_invs + 1;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

//****#####--Manage Purches Function End--####***  
//****#####--Manage Purches Function Start--####***
    public function select_purComnID_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT common_id FROM `tbl_product_purchase_info` WHERE deletion_status= 0 ORDER BY id DESC LIMIT 1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Manage Purches Function End--####***  
//****#####--Save Supplier info Function Start--####***
    public function save_product_distribution_info($data) {
        $db_connect = $this->__construct();

        $transfer_date = date('Y-m-d', strtotime($data['transfer_date']));
        $pro_model = $data[pro_model];
        $remainingStock = $data[remaining_quantity];
        $quantity = $data[quantity];
        $branch_id = $data[branch_id];

        $query2 = mysqli_query($db_connect, "SELECT * FROM tbl_destribution_product WHERE pro_model ='" . $pro_model . "' AND branch_id = '" . $branch_id . "' AND deletion_status = 0 ");
        $prePro = mysqli_num_rows($query2);
        if ($prePro > 0) {
            $pDetails = mysqli_fetch_assoc($query2);
            $qty = $pDetails['inStock'];
            $upStock = $qty + $quantity;
            $queryUp = mysqli_query($db_connect, "UPDATE `tbl_destribution_product` SET inStock = '$upStock' WHERE pro_model = '$pro_model'");
            if ($queryUp) {
                $update_query = "UPDATE `tbl_product_purchase_info` SET inStock = '$remainingStock' WHERE pro_model = '$pro_model'";

                if (mysqli_query($db_connect, $update_query)) {
                    $message = "save";
                } else {
                    die('Query problem' . mysqli_error($db_connect));
                }
            }
        } else {
            $query = "SELECT * FROM `tbl_product_purchase_info` WHERE pro_model ='$pro_model'";
            $query_result = mysqli_query($db_connect, $query);
            foreach ($query_result as $product_details) {

                $sql = "INSERT INTO `tbl_destribution_product` "
                        . "(product_brand,"
                        . "pro_model,"
                        . "pro_group,"
                        . "unit_type,"
                        . "discount,"
                        . "description,"
                        . "rp,"
                        . "mrp,"
                        . "color_name,"
                        . "ref_product,"
                        . "salePrice,"
                        . "quantity,"
                        . "unit_price,"
                        . "inStock,"
                        . "total_prices,"
                        . "serial_no,"
                        . "branch_id,"
                        . "common_id,"
                        . "transfer_person,"
                        . "transfer_date) "
                        . "VALUES "
                        . "('$product_details[product_brand]',"
                        . "'$pro_model',"
                        . "'$product_details[pro_group]',"
                        . "'$product_details[unit_type]',"
                        . "'$product_details[discount]',"
                        . "'$product_details[description]',"
                        . "'$product_details[rp]',"
                        . "'$product_details[mrp]',"
                        . "'$product_details[color_name]',"
                        . "'$product_details[ref_product]',"
                        . "'$product_details[salePrice]',"
                        . "'$data[quantity]',"
                        . "'$data[unit_price]',"
                        . "'$data[quantity]',"
                        . "'$data[total_price]',"
                        . "'$product_details[serial_no]',"
                        . "'$data[branch_id]',"
                        . "'$product_details[common_id]',"
                        . "'$data[transfer_person]',"
                        . "'$transfer_date')";
                $result = mysqli_query($db_connect, $sql);
                if ($result) {
                    $price = $product_details['total_prices'];
                    $nTprice = $data[total_price];
                    $upPrc = $price - $nTprice;
                    $update_query = "UPDATE `tbl_product_purchase_info` SET inStock = '$remainingStock', total_prices = '$upPrc' WHERE pro_model = '$pro_model'";
                    if (mysqli_query($db_connect, $update_query)) {
                        $message = "save Successfully";
                    } else {
                        die('Query problem' . mysqli_error($db_connect));
                    }
                } else {
                    die('Query problem' . mysqli_error($db_connect));
                }
            }
        }
    }

//****#####--Save Supplier info Function End--####*** 
//****#####--Manage Salse Product Function Start--####***        
    
 public function insertselsreturnproduct($pro_model,  $quantity, $cl_desc, $unit_price, $net_amount, $netCost_price, $inStock, $totalAmount, $TotalCostAmount, $cash_paid, $due_amount,  $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date, $warranty_date, $paid_pre_dues, $common_id,$branch_id, $date, $shipping_charge,$rand_inv,$user_id,$inst_discount,$invoice_number) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_sale_return`
                       (pro_model,
                       quantity,
                       cl_desc,
                       unit_price,
                       net_amount,
                       netCost_price,
                       inStock,
                       totalAmount,
                       TotalCostAmount,
                       cash_paid,
                       due_amount,
                       advance,
                       payment_method,
                       chequeAmount,
                       bank_name,
                       chequeNum,
                       chuque_app_date,
                       warranty_date,
                       paid_pre_dues,
                       common_id,
                       branch_id,
                       date,
                       rand_inv,user_id,discount,invoice_number)
                        VALUES 
                        ('$pro_model',
                        '$quantity',
                        '$cl_desc',
                        '$unit_price',
                        '$net_amount',
                        '$netCost_price',
                        '$inStock',
                        '$totalAmount',
                        '$TotalCostAmount',
                        '$cash_paid',
                        '$due_amount',
                        '$advance',
                        '$payment_method',
                        '$chequeAmount',
                        '$bank_name',
                        '$chequeNum',
                        '$chuque_app_date',
                        '$warranty_date',
                        '$paid_pre_dues',
                        '$common_id',
                        '$branch_id',
                        '$date',
                        '$rand_inv','$user_id','$inst_discount','$invoice_number')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Salse Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    

//****#####--Manage Salse Product Function End--####*** 
//****#####--Update Stock Function Start--####***  
    public function updateProductStatusFlag() {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_purchase_invoice_info` SET status_flag = 1 where user_id = '$_SESSION[user_id]' and branch_id = '$_SESSION[branch_id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update Stock Function End--####***  
//****#####--Update Stock Function Start--####***  
    public function updateSupplierDues($currentDue, $supplier_id) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_supplier` SET paymentCount = '$currentDue' WHERE id = '$supplier_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = " product Purchase info save Successfully";  
            $_SESSION['ret_message'] = " product Purchase info save Successfully"; 

        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update Stock Function End--####***  
//****#####--Update Stock Function Start--####***  
    public function updatebuyerDues($customer_id, $totalPreDues, $common_id) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_add_seller` SET previousDues = '$totalPreDues' WHERE id = '$customer_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['inv'] = "$common_id";
            $message = "Sales Product Info Save Successfully";
            $_SESSION['ret_msg'] = "Return Product Info Save Successfully";
            return $message;
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
   public function updatedistributorDues($distributor_id, $fdues){
            $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_distributor` SET Dues = '$fdues' WHERE id = '$distributor_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Distribute Info Save Successfully";
            return $message;
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
   }
 // public function updatesupplierDues($supplier, $totalPreDues) {
 //        $db_connect = $this->__construct();

 //        $sql = "UPDATE `tbl_product_supplier` SET paymentCount = '$totalPreDues' WHERE id = '$supplier'";
 //        $result = mysqli_query($db_connect, $sql);
 //        if ($result) {
 //            $message = "Sales Product Info Save Successfully";
 //            $_SESSION['ret_msg'] = "Return Product Info Save Successfully";
 //            return $message;
            
 //        } else {
 //            die('Query Problem' . mysqli_error($db_connect));
 //        }
 //    }

//****#####--Update Stock Function End--####***  
//****#####--Save purches Product Common info  Function Start--####***   
public function insertSaleInvoiceproduct($customer_id, $invoice_number, $date, $date_month, $totalAmount, $cash_paid, $due_amount, $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date,$user_id,$vat,$labour,$transport,$season,$invoice_by,$discount,$comment,$asb_trans,$appr,$delivery,$sabek_due) {
        $db_connect = $this->__construct();
        
        $to = "salambricks@gmail.com";
        $subject = "ASB Bricks New Invoice";
        $message = "<html>
                    <head>
                    <title>ASB New Invoice Created</title>
                    </head>
                    <body>
                    <h1>ASB Auto Bricks New Invoice Created</h1>
                    <p>Invoice No: '$invoice_number'</p>
                    <table>
                    <tr>
                    <th>Total Amount</th>
                    <th>'$totalAmount'</th>
                    </tr>
                    <tr>
                    <td>Cash Paid</td>
                    <td>'$cash_paid'</td>
                    </tr>
                    <tr>
                    <td>Due Amount</td>
                    <td>'$due_amount'</td>
                    </tr>
                    <tr>
                    <td>Discount</td>
                    <td>'$discount'</td>
                    </tr>
                    </table>
                    </body>
                    </html>";
        $headers = "From: mail@asbricks.com" . "\r\n" ;
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        mail($to,$subject,$message,$headers);

        $sql = "INSERT INTO `tbl_sale_invoice`
                       (
                       customer_id,
                       season,
                       invoice_by,
                       discount,
                       invoice_number,
                       date,
                       date_month,
                       totalAmount,
                       cash_paid,
                       due_amount,
                       payment_method,
                       chequeNum,
                       chequeAmount,
                       bank_name,
                       cheque_app_date,
                       vat,
                       labour,
                       transport,
                       user_id,
                       comment,
                       asb_trans,
                       approved_status,
                       cus_delivery,
                       sabek_due)
                        VALUES 
                        ('$customer_id',
                        '$season',
                        '$invoice_by',
                        '$discount',
                        '$invoice_number',
                        '$date',
                        '$date_month',
                        '$totalAmount',
                        '$cash_paid',
                        '$due_amount',
                        '$payment_method',
                        '$chequeNum',
                        '$chequeAmount',
                        '$bank_name',
                        '$cheque_app_date',
                        '$vat',
                        '$labour',
                        '$transport',
                        '$user_id',
                        '$comment',
                        '$asb_trans',
                        '$appr',
                        '$delivery',
                        '$sabek_due')";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $id = mysqli_insert_id($db_connect);
            return $id;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function insertselsproduct($pro_model,  $quantity, $cl_desc, $unit_price, $net_amount, $netCost_price, $inStock, $totalAmount, $TotalCostAmount, $cash_paid, $due_amount,  $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date, $warranty_date, $paid_pre_dues, $common_id,$branch_id, $date, $shipping_charge,$rand_inv,$user_id,$inst_discount,$invoice_number) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_product_salse`
                       (pro_model,
                       quantity,
                       cl_desc,
                       unit_price,
                       net_amount,
                       netCost_price,
                       inStock,
                       totalAmount,
                       TotalCostAmount,
                       cash_paid,
                       due_amount,
                       advance,
                       payment_method,
                       chequeAmount,
                       bank_name,
                       chequeNum,
                       chuque_app_date,
                       warranty_date,
                       paid_pre_dues,
                       common_id,
                       branch_id,
                       date,
                       rand_inv,user_id,discount,invoice_number)
                        VALUES 
                        ('$pro_model',
                        '$quantity',
                        '$cl_desc',
                        '$unit_price',
                        '$net_amount',
                        '$netCost_price',
                        '$inStock',
                        '$totalAmount',
                        '$TotalCostAmount',
                        '$cash_paid',
                        '$due_amount',
                        '$advance',
                        '$payment_method',
                        '$chequeAmount',
                        '$bank_name',
                        '$chequeNum',
                        '$chuque_app_date',
                        '$warranty_date',
                        '$paid_pre_dues',
                        '$common_id',
                        '$branch_id',
                        '$date',
                        '$rand_inv','$user_id','$inst_discount','$invoice_number')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Salse Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
   public function insertsaleproduct($quantity,  $category, $pitch_code, $type, $unit_price, $net_amount,$user_id,$invoice_number,$date,$date_month,$rand_inv,$stock) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_sale`
                       (category,
                       pitch_code,
                       type,
                       quantity,
                       unit_price,
                       net_amount,
                       rand_inv,
                       user_id,
                       invoice_number,
                       date,
                       date_month,
                       stock)
                        VALUES 
                        ('$category',
                        '$pitch_code',
                        '$type',
                        '$quantity',
                        '$unit_price',
                        '$net_amount',
                        '$rand_inv',
                        '$user_id',
                        '$invoice_number',
                        '$date',
                        '$date_month',
                        '$stock')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Order Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
    public function saveincome($invoice_number,$quantity,$category,$pitch_code,$type,$cash_paid,$user_id,$branch_id,$date,$date_month,$rand) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_account` (account_head,amount,branch_id,account_type,date,purpose,status,date_month,user_id,rand_inv,category) VALUES ('দৈনিক আয়','$cash_paid','$branch_id','2','$date','$invoice_number $category $type $quantity $pitch_code','1','$date_month','$user_id','$rand','ইট')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Income Added Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
    public function saveDeliverydetails($invID, $bricks,  $type, $pc, $delivery, $delivery_left, $invoice, $driver) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_sales_delivery`
                       (inv_id, bricks, type, pc, delivery, invoice_number, date, driver)
                        VALUES 
                        ('$invID',
                        '$bricks',
                        '$type',
                        '$pc',
                        '$delivery',
                        '$invoice',
                        NOW(),
                        '$driver')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Order Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
      public function insertOrderInvoiceproduct($customer_id, $invoice_number, $date, $date_month, $totalAmount, $cash_paid, $due_amount, $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date,$user_id) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_order_invoice`
                       (
                       customer_id,
                       invoice_number,
                       date,
                       date_month,
                       totalAmount,
                       cash_paid,
                       due_amount,
                       payment_method,
                       chequeNum,
                       chequeAmount,
                       bank_name,
                       cheque_app_date,
                       user_id)
                        VALUES 
                        ('$customer_id',
                        '$invoice_number',
                        '$date',
                        '$date_month',
                        '$totalAmount',
                        '$cash_paid',
                        '$due_amount',
                        '$payment_method',
                        '$chequeNum',
                        '$chequeAmount',
                        '$bank_name',
                        '$cheque_app_date',
                        '$user_id')";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $id = mysqli_insert_id($db_connect);
            return $id;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function insertorderproduct($quantity,  $category, $unit_price, $net_amount,$user_id,$invoice_number,$date,$date_month,$rand_inv) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_order`
                       (category,
                       quantity,
                       unit_price,
                       net_amount,
                       rand_inv,
                       user_id,
                       invoice_number,
                       date,
                       date_month)
                        VALUES 
                        ('$category',
                        '$quantity',
                        '$unit_price',
                        '$net_amount',
                        '$rand_inv',
                        '$user_id',
                        '$invoice_number',
                        '$date',
                        '$date_month')";

        if (mysqli_query($db_connect, $sql)) {
            $sql2 = "UPDATE tbl_paka_it_stock SET stock = '$stock' WHERE category = '$category'";
            if(mysqli_query($db_connect, $sql2))
            {
              return $message = "Order Info Save Successfully";

            } else {
            die('Query problem' . mysqli_error($db_connect));
        }
            
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_customer_due($customer_id,$balance){
         $db_connect = $this->__construct();
        $sql2 = "UPDATE tbl_add_seller
         SET previousDues = '$balance' WHERE id = '$customer_id'";
            if(mysqli_query($db_connect, $sql2))
            {
              return $message = "Sale Successfully";

            } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
 public function insertSalesReturnInvoiceproduct($reference_invoice, $customer_id, $branch_id, $customer_address, $totalPreDues, $ref_code, $remarks, $invoice_number, $date, $totalAmount, $TotalCostAmount, $cash_paid, $due_amount, $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date, $warranty_date, $paid_pre_dues, $shipping_charge,$rand_inv,$user_id,$date_month,$total_Dis) {
        $db_connect = $this->__construct();
        $sql = "INSERT INTO `tbl_sale_return_invoice`
                       (reference_invoice,
                       customer_id,
                       branch_id,
                       customer_address,
                       privious_dues,
                       ref_code,
                       remarks,
                       invoice_number,
                       date,
                       totalAmount,
                       TotalCostAmount,
                       cash_paid,
                       due_amount,
                       advance,
                       payment_method,
                       chequeAmount,
                       bank_name,
                       chequeNum,
                       chuque_app_date,
                       warranty_date,
                       paid_pre_dues,
                       shipping_charge,
                       rand_inv,user_id,date_month,total_discount_back)
                        VALUES 
                        ('$reference_invoice',
                        '$customer_id',
                        '$branch_id',
                        '$customer_address',
                        '$totalPreDues',
                        '$ref_code',
                        '$remarks',
                        '$invoice_number',
                        '$date',
                        '$totalAmount',
                        '$TotalCostAmount',
                        '$cash_paid',
                        '$due_amount',
                        '$advance',
                        '$payment_method',
                        '$chequeAmount',
                        '$bank_name',
                        '$chequeNum',
                        '$chuque_app_date',
                        '$warranty_date',
                        '$paid_pre_dues',
                        '$shipping_charge',
                        '$rand_inv','$user_id','$date_month','$total_Dis')";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $id = mysqli_insert_id($db_connect);
            return $id;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function insertPurRetInvoiceproduct($supplier, $branch_id, $totalPreDues, $remarks, $invoice_number, $date, $totalAmount, $cash_paid, $due_amount, $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date, $warranty_date, $ajaira, $shipping_charge,$user_id,$date_month,$total_Dis,$privious_dues){
    $db_connect = $this->__construct();
    $sql = "INSERT INTO `tbl_purchase_return_invoice`
                        (supplier_id,
                       branch_id,
                       privious_dues,
                       remarks,
                       invoice_number,
                       date,
                       totalAmount,
                       cash_paid,
                       due_amount,
                       payment_method,
                       chequeAmount,
                       bank_name,
                       chequeNum,
                       chuque_app_date,
                       shipping_charge,
                       user_id,
                       date_month,
                       total_discount_back)
                        VALUES 
                        ('$supplier',
                        '$branch_id',
                        '$privious_dues',
                        '$remarks',
                        '$invoice_number',
                        '$date',
                        '$totalAmount',
                        '$cash_paid',
                        '$due_amount',
                        '$payment_method',
                        '$chequeAmount',
                        '$bank_name',
                        '$chequeNum',
                        '$chuque_app_date',
                        '$shipping_charge',
                        '$user_id',
                        '$date_month',
                        '$total_Dis')";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $id = mysqli_insert_id($db_connect);
            return $id;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
}

public function insertpurchaseretproduct($pro_model,  $quantity, $cl_desc, $unit_price, $net_amount, $inStock, $totalAmount, $cash_paid, $due_amount,  $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date, $warranty_date, $ajaira, $common_id,$branch_id, $date, $shipping_charge,$user_id,$inst_discount,$invoice_number,$supplier){
 $db_connect = $this->__construct();
    $sql = "INSERT INTO `tbl_purchase_return_info` 
                       (supplier_name,
                       branch_id,
                       quantity,
                       invoice_number,
                       date,
                       unit_price,
                       total_prices,
                       common_id,
                       pro_model_id,
                       description,
                       user_id,
                       discount)
                        VALUES 
                        ('$supplier',
                        '$branch_id',
                        '$quantity',
                        '$invoice_number',
                        '$date',
                        '$unit_price',
                        '$net_amount',
                        '$common_id',
                        '$pro_model',
                        '$cl_desc',
                        '$user_id',
                        '$inst_discount')";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return "Done";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
}
public function select_all_monthly_Purchase_return_info_by_date($branch_id){
    $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_return_invoice` WHERE date BETWEEN '$from' AND '$to' AND branch_id = '$branch_id' AND deletion_status = 0 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
}
public function select_all_Purchase_return_info_monthly($branch_id){
        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
       date_default_timezone_set($timezone);
       $timestamp = time();
       $db_date = date("Y-m", $timestamp);

        $sql = "SELECT * FROM `tbl_purchase_return_invoice` WHERE date_month = '$db_date' AND branch_id = '$branch_id' AND deletion_status = 0 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
}
//****#####--Save purches Product Common info End--####***  
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_daily_sales_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE date = DATE(NOW()) AND branch_id = '$branch_id' AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####*** 
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_daily_sales_product_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE date = DATE(NOW()) AND deletion_status= 0 order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####*** 
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_sales_product_info_by_group($from, $to, $pro_model) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE (date BETWEEN '$from' AND '$to' AND trim(pro_model) = '$pro_model') OR (trim(pro_model) = '$pro_model') ORDER BY id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####*** 
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_sales_product_info_by_brand($from, $to, $pro_model) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE (date BETWEEN '$from' AND '$to' AND pro_model = '$pro_model') OR (pro_model = '$pro_model') ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_sales_product_info_by_model($from, $to, $model_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE (date BETWEEN '$from' AND '$to' AND trim(pro_model) = '$model_id') OR (trim(pro_model) = '$model_id') ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***  
//****#####--Select monthly Sales info By Date Function Start--####***    
    public function day_wise_sales_report($from, $to, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE date BETWEEN '$from' AND '$to' AND branch_id = '$branch_id' AND deletion_status = 0 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####***
//****#####--Select Daly Sales info Function Start--####***    
    

//****#####--Select Daly Sales info Function End--####*** 
//****#####--Select Stock List info Function Start--####***    
    public function select_all_daily_sales_report_invoice($search, $branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE branch_id = '$branch_id' AND invoice_number LIKE '%$search%' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####*** 
//****#####--Select Stock List info Function Start--####***    
    public function select_all_daily_sales__invoice_wise_sales($search) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE invoice_number LIKE '%$search%' order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####***  
//****#####--Select Stock List info Function Start--####***    
    public function select_all_daily_sales_report_by_model($model_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE pro_model_id = '$model_id' ";
        $query = mysqli_query($db_connect, $sql);
        if ($query) {
            return $query;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####***
//****#####--Select Stock List info Function Start--####***    
    public function select_all_sales_report_by_group($group_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE pro_group = '$group_id' ";
        $query = mysqli_query($db_connect, $sql);
        if ($query) {
            return $query;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####***
//****#####--Select Stock List info Function Start--####***    
    public function select_all_sales_report_by_brand($brand_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE product_brand = '$brand_id' ";
        $query = mysqli_query($db_connect, $sql);
        if ($query) {
            return $query;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####*** 
//****#####--Select Stock List info Function Start--####***    
    public function select_all_sales_report_by_model($model_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE pro_model_id = '$model_id' ";
        $query = mysqli_query($db_connect, $sql);
        if ($query) {
            return $query;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####***   
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_daily_account_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE date = DATE(NOW()) AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_monthly_account_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***
//****#####--Select monthly Sales info By Date Function Start--####***    
    public function select_all_monthly_account_info_by_date($from, $to) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE date BETWEEN '$from' AND '$to' AND deletion_status = 0 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####*** 
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_daily_income_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(amount) as income FROM `tbl_account` WHERE date = DATE(NOW()) AND account_type = 2 AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***  
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_monthly_income_info($from, $to) {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(amount) as income FROM `tbl_account` WHERE date BETWEEN '$from' AND '$to' AND account_type = 2 AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***    
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_daily_expense_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(amount) as expense FROM `tbl_account` WHERE date = DATE(NOW()) AND account_type = 1 AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***  
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_monthly_expense_info($from, $to) {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(amount) as expense FROM `tbl_account` WHERE date BETWEEN '$from' AND '$to' AND account_type = 1 AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***        
//****#####--Select monthly Sales info Function Start--####***    
    public function select_all_sales_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE branch_id = '$branch_id' AND date = DATE(NOW()) AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_monthly_sales_info($branch_id) {

        $db_connect = $this->__construct();
     $timezone = 'Asia/Dhaka';
   date_default_timezone_set($timezone);
   $timestamp = time();
   $db_date = date("Y-m", $timestamp);
        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE date_month= '$db_date' AND branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####*** 
//****#####--Select monthly Sales info Function Start--####***    
     public function select_all_item_sales_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT cl_desc,pro_model,date,unit_price,SUM(quantity) as quantity FROM `tbl_product_salse` WHERE branch_id = '$branch_id' AND deletion_status= 0  group by cl_desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####*** 
//****#####--Select monthly Sales info Function Start--####***    
    public function select_all_monthly_payment_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE due_amount != '' OR advance != '' AND  deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####***  
//****#####--Select monthly Sales info Function Start--####***    
    public function select_all_monthly_purchase_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####*** 
//****#####--Select monthly Sales info Function Start--####***    
    public function select_all_monthly_cancel_purchase_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE  deletion_status= 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####*** 
//****#####--Select monthly Sales info By Date Function Start--####***    
    public function select_all_monthly_purchase_info_by_date($from, $to, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE (transfer_date BETWEEN '$from' AND '$to' AND branch_id = '$branch_id') AND deletion_status = 0 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####***  
//****#####--Select monthly Sales info By Date Function Start--####***    
    public function select_all_cancel_monthly_purchase_info_by_date($from, $to) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE date BETWEEN '$from' AND '$to' AND deletion_status = 1 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####***  
//****#####--Select monthly Sales info Function Start--####***    
    public function select_all_cancel_sales_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE  deletion_status= 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####***  
////****#####--Select monthly Sales info Function Start--####***    
    public function select_all_monthly_profit_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####***  
//****#####--Show Sales Product Dails Function Start--####***  
    public function show_distribution_serial_number_info($pro_model, $branch_id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_destribution_serial_number` WHERE pro_model = '$pro_model' AND branch_id = '$branch_id' AND deletion_status = '0' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Sales Product Dails Function End--####*** 
//****#####--Select Stock List info Function Start--####***    
    public function select_all_common_stock_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE  deletion_status = 0 AND branch_id = '$_SESSION[branch_id]' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####***  
//****#####--Select Stock List info Function Start--####***    
   public function select_all_current_stock($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE branch_id = '$branch_id' AND deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
       public function select_all_current_stock_info($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE branch_id = '$branch_id' AND deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_emergency_stock_info($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE branch_id = '$branch_id' AND inStock <3 AND deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####***  
//****#####--Select Stock List info Function Start--####***    
    public function select_all_common_stock_info_by_code($search) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM tbl_destribution_product WHERE (product_brand LIKE '%$search%' OR pro_model LIKE '%$search%') ORDER BY id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Stock List info Function End--####*** 
//****#####--Select Stock List info Function Start--####***    
    public function select_all_current_stock_info_by_code($search, $branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM tbl_destribution_product WHERE  pro_model LIKE '%$search%' AND inStock > 10 AND branch_id = '$branch_id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_current_stock_info_by_grp($search, $branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM tbl_destribution_product WHERE pro_group = '$search' AND branch_id = '$branch_id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_current_stock_info_by_brnd($pro_brand,$branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM tbl_destribution_product WHERE product_brand = '$pro_brand' AND branch_id = '$branch_id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 

//****#####--Select Stock List info Function End--####*** 
//****#####--Select monthly Sales info By Date Function Start--####***    
    public function select_all_monthly_sales_info_by_date($from, $to, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE date BETWEEN '$from' AND '$to' AND branch_id = '$branch_id' AND deletion_status = 0 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####*** 
//****#####--Select monthly Sales info By Date Function Start--####***    
   public function select_all_monthly_product_sales_info_by_date($from, $to, $item,$branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT cl_desc,pro_model,date,unit_price,SUM(quantity) as quantity FROM `tbl_product_salse` WHERE date BETWEEN '$from' AND '$to'AND branch_id = '$branch_id' AND cl_desc LIKE '%$item%' group by cl_desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####*** 
//****#####--Select monthly Sales info By Date Function Start--####***    
    public function select_all_cancel_sales_info_by_date($from, $to) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE date BETWEEN '$from' AND '$to' AND deletion_status = 1 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####*** 
//****#####--Select monthly Sales info By Date Function Start--####***    
    public function select_all_monthly_profit_info_by_date($from, $to, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT date,SUM(totalAmount) as TotalSales,SUM(TotalCostAmount) as TotalCost FROM `tbl_sale_invoice` WHERE date BETWEEN '$from' AND '$to' AND branch_id = '$branch_id' AND deletion_status = 0 GROUP BY date DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Bu Date Function End--####***   
//****#####--Delete Delete Salses Product Function Start--####*** 
    public function delete_sales_product($id) {
        $db_connect = $this->__construct();
        $result = $this->show_invoice($id);
        $ta = $this->getOneCol('totalAmount','tbl_sale_invoice','id',$id);
        $cus_id = $this->getOneCol('customer_id','tbl_sale_invoice','id',$id);
        $pd = $this->getOneCol('previousDues','tbl_add_seller','id',$cus_id);
        $totalPreDues = $pd - $ta;
        foreach($result as $data){
            $ser = $data['pro_model'];
             $qty = $data['quantity'];
            $stck = $this->getOneColBranch('inStock', 'tbl_destribution_product', 'pro_model', $mod,$_SESSION['branch_id']);
            $stckup = $stck + $qty;   
            $stock = $this->update_stock($stckup, $ser);
        }
        if($stock){
          $up = $this->updatebuyerDues($cus_id, $totalPreDues,0);
           if($up){
           $sql = "DELETE FROM  `tbl_sale_invoice` WHERE id='$id'";
            if (mysqli_query($db_connect, $sql)) {
                $sql1 = "DELETE FROM  `tbl_product_salse` WHERE rand_inv='$id'";
                 if (mysqli_query($db_connect, $sql1)) {
                $_SESSION['message'] = "Delete Successfully";
                header("Location: {$_SERVER['HTTP_REFERER']}");
                } else {
                    die('Query Problem' . mysqli_error($db_connect));
                } 
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }  
            
        }else{
            return false;
        }
    }
       
    }
    public function show_invoice($id){
      $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_product_salse` WHERE rand_inv='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }  
    }

    public function show_sales_invoc_info($rand,$user) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_product_salse` WHERE rand_inv='$rand' AND user_id='$user' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function show_order_invoice_info($rand) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_order` WHERE rand_inv = '$rand' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
     public function show_sale_invoice_info($rand) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_sale` WHERE rand_inv = '$rand' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function show_sales_basic_invoc_info($rand,$user) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_product_salse` WHERE rand_inv='$rand' AND user_id='$user' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Sales Product Dails Function End--####***
    public function show_sales_basic_invoc_info_by_id($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_product_salse` WHERE id = '$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Sales Product Dails Function End--####***
//****#####--Save Address Book Function Start--####***
    public function save_address_book_info($data) {

        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_address_book` (name,location,email_address,company,contact_person,web_address,details,gender,mobile1,mobile2,note,date,status) VALUES ('$data[name]','$data[location]','$data[email_address]','$data[company]','$data[contact_person]','$data[web_address]','$data[details]','$data[gender]','$data[mobile1]','$data[mobile2]','$data[note]','$data[date]','$data[status]')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Address Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Address Book Function END--####***
//****#####--Show Address Book Function Start--####***  
    public function show_address_book_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_address_book` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Address Book Function End--####*** 
////****#####--Select Address Book Function Start--####***
    public function select_all_address_book_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_address_book` WHERE deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Address Book Function End--####***
//****#####--Delete Address Book Branch Function Start--####***
    public function delete_address_book_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_address_book` WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Address Book Function End--####***
////****#####--Update Address Book Function Start--####***
    public function update_addres_book_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_address_book` SET "
                . "name	= '$data[name]',"
                . "location = '$data[location]',"
                . "email_address = '$data[email_address]',"
                . "company = '$data[company]',"
                . "contact_person = '$data[contact_person]',"
                . "web_address = '$data[web_address]',"
                . "details = '$data[details]',"
                . "gender = '$data[gender]',"
                . "mobile1 = '$data[mobile1]',"
                . "mobile2 = '$data[mobile2]',"
                . "note = '$data[note]',"
                . "date = '$data[date]',"
                . "status='$data[status]'"
                . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = " Address Book Info successfully";
            header('Location: manage_note_book.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

////****#####--Update Address Book Function End--####***
//****#####--Save payment info Function Start--####***

//****#####--Save payment info Function End--####*** 
//****#####--Show payment info Function Start--####***  
    public function show_payment_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show payment info Function End--####*** 
//****#####--Show payment info Function Start--####***  
    public function show_purchase_payment_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show payment info Function End--####***  
//****#####--Update payment Function Start--####*** 
    public function update_sales_payment_info($cuesId, $preDues, $preCash, $invoice_number) {
        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "UPDATE `tbl_sale_invoice` SET due_amount ='$preDues',cash_paid = '$preCash',date  = '$date' WHERE customer_id = '$cuesId' AND invoice_number = '$invoice_number'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Payment Info Update Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update payment Function End--####*** 
//****#####--Select monthly Sales info Function Start--####***    
    public function select_all_update_payment_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE deletion_status= 0 AND branch_id = '$_SESSION[branch_id]' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select monthly Sales info Function End--####***   
//****#####--Delete Delete Salses Product Function Start--####*** 
    public function delete_sales_payment_info($id) {
        $db_connect = $this->__construct();  
        $cus = $this->getOneCol('customer_name','tbl_sales_payment_info','id',$id);
        $paid = $this->getOneCol('paid','tbl_sales_payment_info','id',$id);
        $pd = $this->getOneCol('previousDues','tbl_add_seller','id',$cus);
        $fd = $pd + $paid;        
        $sql = "UPDATE `tbl_add_seller` SET previousDues = '$fd' WHERE id = '$cus'";
            if (mysqli_query($db_connect, $sql)) {
                $sql1 = "DELETE FROM  `tbl_sales_payment_info` WHERE id='$id'";
                 if (mysqli_query($db_connect, $sql1)) {
                    $_SESSION['message'] = "Delete Successfully";
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                } else {
                    die('Query Problem' . mysqli_error($db_connect));
                }
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }      
    }
     public function delete_pur_payment_info($id) {
        $db_connect = $this->__construct();  
        $sup = $this->getOneCol('product_sup_name','tbl_purchase_paymet','id',$id);
        $paid = $this->getOneCol('paid','tbl_purchase_paymet','id',$id);
        $pd = $this->getOneCol('paymentCount','tbl_product_supplier','id',$sup);
        $fd = $pd + $paid;        
        $sql = "UPDATE `tbl_product_supplier` SET paymentCount = '$fd' WHERE id = '$sup'";
            if (mysqli_query($db_connect, $sql)) {
                $sql1 = "DELETE FROM  `tbl_purchase_paymet` WHERE id='$id'";
                 if (mysqli_query($db_connect, $sql1)) {
                    $_SESSION['message'] = "Delete Successfully";
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                } else {
                    die('Query Problem' . mysqli_error($db_connect));
                }
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }      
    }

//****#####--Delete Delete Salses Product Function End--####***
//****#####--Update Stock Function Start--####***  


//****#####--Update Stock Function End--####***  
//****#####--Update payment Function Start--####*** 
    public function update_payment_info($data) {
        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "UPDATE `tbl_sale_invoice` SET "
                . "cash_paid = '$data[currCash]',"
                . "due_amount = '$data[currDues]',"
                . "advance = '$data[currAdvance]',"
                . "date = '$date'"
                . " WHERE id= '$data[id]'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Collection Info Update successfully";
            header('Location: payment.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update payment Function End--####***  
//****#####--Update payment Function Start--####*** 
    public function update_purchase_payment_info($supplierId, $preDues, $preCash, $invoice_number) {
        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "UPDATE `tbl_productpurcheseinvoice_info` SET dues ='$preDues',cashPaid = '$preCash',date  = '$date' WHERE supplier_id = '$supplierId' AND invoice_number = '$invoice_number'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Payment Info Update Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update payment Function End--####***  
//****#####--Update Stock Function Start--####***  
    public function update_final_dues_info($supplierId, $finalDues) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_supplier` SET paymentCount = '$finalDues' WHERE id = '$supplierId'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Payment Info Update Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update Stock Function End--####***  
//****#####--Dashboard Content Function Start--####***
    public function select_all_todays_sales_payment($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE branch_id = '$branch_id' AND date = DATE(NOW()) AND deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function select_all_todays_cullection_payment($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE branch_id = '$branch_id' AND date = DATE(NOW()) AND deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Product Return info Function End--####*** 
    public function select_all_todays_purchase_payment($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE branch_id = '$branch_id' AND (transfer_date = DATE(NOW()) AND deletion_status = 0)";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_all_todays_purchase_payment_dash($branch_id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE branch_id = '$branch_id' AND (date = DATE(NOW()) AND deletion_status = 0)";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
   public function select_all_todays_purchase_payment_dash_cash_das($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_paymet` WHERE branch_id = '$branch_id' AND (date = DATE(NOW()) AND deletion_status = 0)";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
       public function select_all_todays_sales_return_invoice_dash($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE branch_id = '$branch_id' AND (date = DATE(NOW()) AND deletion_status = 0)";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Dashboard Content Function End--####*** 
//****#####--Select Active Company Function Start--####***
    public function select_all_active_banck_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_bank_setup` WHERE deletion_status= 0 AND status = 1 ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Company Function End--####***  
//****#####--Delete Country Function Start--####*** 
    public function delete_bank_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_bank_setup` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Delete Country Function End--####***    
//****#####--product code from product invoice table Function start--####***
    public function select_product_purchase_invoice_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_invoice_info` WHERE id='$id' AND branch_id='$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--product code from product invoice table Function start--####*** 
//****#####--product code from purchases product table Function start--####***
    public function get_product_purchase_info($pro_model) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE pro_model ='$pro_model' AND branch_id='$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--product code from purchases product table Function start--####*** 
//****#####--Delete Country Function End--####*** 
    public function update_purchase_stock($newStock, $proModel) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destribution_product` SET inStock ='$newStock' where pro_model = '$proModel' AND branch_id='$_SESSION[branch_id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show Product Function Start--####***  
//****#####--product code from product invoice table Function start--####***
    public function select_commonId_from_mainInvoice_table($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--product code from product invoice table Function start--####*** 
//****#####--invoice common id from product invoice table Function start--####***
    public function get_invoice_all_info_by_common_id($cmmnID) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_invoice_info` WHERE common_id ='$cmmnID' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--invoice common id from product invoice table Function start--####*** 
//****#####--purches Stock info--####***
    public function show_Stock_info($pro_model) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE pro_model ='$pro_model' AND branch_id='$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--purches Stock info--####*** 
//****#####--purches Stock info--####***
    public function show_serial_number_info($pro_model) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_serial_number` WHERE pro_model ='$pro_model' AND pro_model != serial_number  AND branch_id = '$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--purches Stock info--####*** 
//****#####--Update Stock Function Start--####***  
    public function update_stock($inStock, $cat) {
        
        $db_connect = $this->__construct();
        $sql = "UPDATE `tbl_paka_it_stock` SET stock ='$inStock' WHERE category = '$cat'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return "Done";
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
        public function update_stock_serial($inStock, $pModel) {
        $db_connect = $this->__construct();
         if($inStock == 0){
        $sql = "UPDATE `tbl_destribution_serial_number` SET inStock ='$inStock',deletion_status='1' where serial_number = '$pModel' AND branch_id = '$_SESSION[branch_id]'";             
         }
         else{
        $sql = "UPDATE `tbl_destribution_serial_number` SET inStock ='$inStock',deletion_status='0' where serial_number = '$pModel' AND branch_id = '$_SESSION[branch_id]'";                 
         }

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['return'] = "Purchase Return Successfull";
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_stock_serial_ret($inStock, $pModel) {
        $db_connect = $this->__construct();
         if($inStock == 0){
        $sql = "DELETE FROM `tbl_destribution_serial_number`  where serial_number = '$pModel' AND branch_id = '$_SESSION[branch_id]'";             
         }
         else{
        $sql = "UPDATE `tbl_destribution_serial_number` SET inStock ='$inStock',deletion_status='0' where serial_number = '$pModel' AND branch_id = '$_SESSION[branch_id]'";                 
         }

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['return'] = "Purchase Return Successfull";
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update Stock Function End--####***
//****#####--Select Active Supplier Type Function start--####*** 
    public function select_all_active_supplier_invoie_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE deletion_status= 0 AND deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Supplier Type Function End--####***  
//****#####--Update Stock Function Start--####***  
    public function update_sales_stock($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_purchase_info` SET inStock = '$data[inStock]' where product_code = '$data[product_code]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update Stock Function End--####***  
//****#####--Save Group info Function Start--####***
    public function save_product_serial_number_info($product_model, $serial_number, $pro_quantity, $inStock,$pro_color, $branch_id) {

        $db_connect = $this->__construct();

        $query = mysqli_query($db_connect, "SELECT * FROM tbl_destribution_serial_number WHERE serial_number ='" . $serial_number . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Serial Number is Uniq . This Serial Number is Already Exit!!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_destribution_serial_number` (pro_model,serial_number,quantity,instock,product_color,branch_id) VALUES ('$product_model','$serial_number','$pro_quantity','$pro_quantity','$pro_color','$branch_id')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Serial Number Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Group info Function End--####***
//****#####--Save Group info Function Start--####***
    public function save_old_product_serial_number_info($pro_model_id, $serial_number, $pro_quantity, $quantity, $inStock, $pro_color, $branch_id) {

        $db_connect = $this->__construct();

        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_serial_number WHERE serial_number ='" . $serial_number . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Serial Number is Uniq . This Serial Number is Already Exit!!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_serial_number` (product_model,serial_number,pro_quantity,total_quantity,instock,color_name,branch_id) VALUES ('$pro_model_id','$serial_number','$pro_quantity','$quantity','$inStock','$pro_color','$branch_id')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Serial Number Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Group info Function End--####***
//****#####--Update purches Product invoice info  Function Start--####***   
    public function add_old_purchases_product($product_brand, $product_model, $pro_group,$discount, $description, $rp, $mrp, $color_name,$salesPrice, $quantity, $unit_price, $inStock, $total_prices, $serial_no, $branch_id) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destribution_product` SET product_brand ='$product_brand',pro_model ='$product_model',pro_group ='$pro_group',discount ='$discount',description ='$description',rp ='$rp',mrp ='$mrp',color_name ='$color_name',salePrice ='$salesPrice',quantity ='$quantity',unit_price ='$unit_price',inStock ='$inStock',total_prices ='$total_prices',serial_no ='$serial_no',branch_id ='$branch_id' WHERE pro_model ='$product_model' ";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Update Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_distribution_stock_serial($pm,$inStock,$branch_id)
    {
      $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destribution_product` SET inStock ='$inStock' WHERE pro_model ='$pm' AND branch_id='$branch_id'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Update Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
   public function update_distribution_stock_serial_dist($pm,$stck,$distributor_id)
    {
      $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destributor_product_stock` SET inStock ='$stck' WHERE pro_model ='$pm' AND distributor_id='$distributor_id'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Update Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
//****#####--Update purches Product invoice info End--####*** 
//****#####--Update purches Product invoice info  Function Start--####***   
    public function add_purchases_product($product_brand, $product_model, $pro_group,$discount, $description, $rp, $mrp, $color_name,$salesPrice, $quantity, $unit_price, $inStock, $total_prices, $serial_no, $branch_id,$rand_inv) {
        $db_connect = $this->__construct();

         $sql = "INSERT INTO `tbl_purchase_invoice_info`("
                    . "product_brand,"
                    . "pro_model,"
                    . "pro_group,"
                    . "discount,"
                    . "description,"
                    . "rp,"
                    . "mrp,"
                    . "color_name,"
                    . "salePrice,"
                    . "quantity,"
                    . "unit_price,"
                    . "inStock,"
                    . "total_prices,"
                    . "serial_no,"
                    . "branch_id,"
                    . "user_id,"
                    . "rand_inv) "
                    . "VALUES "
                    . "('$product_brand',"
                    . "'$product_model',"
                    . "'$pro_group',"
                    . "'$discount',"
                    . "'$description',"
                    . "'$rp',"
                    . "'$mrp',"
                    . "'$color_name',"
                    . "'$salesPrice',"
                    . "'$quantity',"
                    . "'$unit_price',"
                    . "'$inStock',"
                    . "'$total_prices',"
                    . "'$serial_no',"
                    . "'$branch_id',"
                    . "'$_SESSION[user_id]',"
                    . "'$rand_inv')";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Product Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update purches Product invoice info End--####*** 
//****#####--Update purches Product invoice info  Function Start--####***   
    public function add_purchases_product_info($product_brand, $product_model, $pro_group,$discount, $description, $rp, $mrp, $color_name,$salesPrice, $quantity, $unit_price, $inStock, $total_prices, $serial_no, $branch_id) {
        $db_connect = $this->__construct();

        $query = mysqli_query($db_connect, "SELECT * FROM `tbl_destribution_product` WHERE pro_model = '" . $product_model . "' AND deletion_status = 0 AND branch_id = '".$branch_id."' ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Product Model is Unique . This Serial Number is Already Exit!!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_destribution_product`("
                    . "product_brand,"
                    . "pro_model,"
                    . "pro_group,"
                    . "discount,"
                    . "description,"
                    . "rp,"
                    . "mrp,"
                    . "color_name,"
                    . "salePrice,"
                    . "quantity,"
                    . "unit_price,"
                    . "inStock,"
                    . "total_prices,"
                    . "serial_no,"
                    . "branch_id)"
                    . "VALUES "
                    . "('$product_brand',"
                    . "'$product_model',"
                    . "'$pro_group',"
                    . "'$discount',"
                    . "'$description',"
                    . "'$rp',"
                    . "'$mrp',"
                    . "'$color_name',"
                    . "'$salesPrice',"
                    . "'$quantity',"
                    . "'$unit_price',"
                    . "'$inStock',"
                    . "'$total_prices',"
                    . "'$serial_no',"
                    . "'$branch_id')";
            $result = mysqli_query($db_connect, $sql);
            if ($result) {
                $message = "Product Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }
        public function add_purchases_product_info_distributor($product_brand, $product_model, $pro_group,$discount, $description, $rp, $mrp, $color_name,$salesPrice, $quantity, $unit_price, $inStock, $total_prices, $serial_no, $distributor_id) {
        $db_connect = $this->__construct();

        $query = mysqli_query($db_connect, "SELECT * FROM ` tbl_destributor_product_stock` WHERE pro_model = '" . $product_model . "' AND deletion_status = 0 AND distributor_id = '".$distributor_id."' ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Product Model is Unique . This Serial Number is Already Exit!!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_destributor_product_stock`("
                    . "product_brand,"
                    . "pro_model,"
                    . "pro_group,"
                    . "discount,"
                    . "description,"
                    . "rp,"
                    . "mrp,"
                    . "color_name,"
                    . "salePrice,"
                    . "quantity,"
                    . "unit_price,"
                    . "inStock,"
                    . "total_prices,"
                    . "serial_no,"
                    . "distributor_id)"
                    . "VALUES "
                    . "('$product_brand',"
                    . "'$product_model',"
                    . "'$pro_group',"
                    . "'$discount',"
                    . "'$description',"
                    . "'$rp',"
                    . "'$mrp',"
                    . "'$color_name',"
                    . "'$salesPrice',"
                    . "'$quantity',"
                    . "'$unit_price',"
                    . "'$inStock',"
                    . "'$total_prices',"
                    . "'$serial_no',"
                    . "'$distributor_id')";
            $result = mysqli_query($db_connect, $sql);
            if ($result) {
                $message = "Product Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Update purches Product invoice info End--####***
//****#####--Update purches Product invoice info  Function Start--####***   
    public function add_new_purchases_product($product_brand, $product_model, $pro_group,$discount, $description, $rp, $mrp, $color_name,$salesPrice, $quantity, $unit_price, $inStock, $total_prices, $serial_no, $branch_id,$rand_inv) {
        $db_connect = $this->__construct();

        // $query = mysqli_query($db_connect, "SELECT * FROM `tbl_purchase_invoice_info` WHERE pro_model ='" . $product_model . "' AND deletion_status = 0 ");
        // if (mysqli_num_rows($query) > 0) {
        //     $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry Product Model is Unique . This Serial Number is Already Exit!!</span>";
        //     return $message;
        // } else {
            $sql = "INSERT INTO `tbl_purchase_invoice_info`("
                    . "product_brand,"
                    . "pro_model,"
                    . "pro_group,"
                    . "discount,"
                    . "description,"
                    . "rp,"
                    . "mrp,"
                    . "color_name,"
                    . "salePrice,"
                    . "quantity,"
                    . "unit_price,"
                    . "inStock,"
                    . "total_prices,"
                    . "serial_no,"
                    . "branch_id,"
                    . "user_id,"
                    . "rand_inv) "
                    . "VALUES "
                    . "('$product_brand',"
                    . "'$product_model',"
                    . "'$pro_group',"
                    . "'$discount',"
                    . "'$description',"
                    . "'$rp',"
                    . "'$mrp',"
                    . "'$color_name',"
                    . "'$salesPrice',"
                    . "'$quantity',"
                    . "'$unit_price',"
                    . "'$inStock',"
                    . "'$total_prices',"
                    . "'$serial_no',"
                    . "'$branch_id',"
                    . "'$_SESSION[user_id]',"
                    . "'$rand_inv')";

            $result = mysqli_query($db_connect, $sql);
            if ($result) {
                $message = "Product Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        //}
    }

//****#####--Update purches Product invoice info End--####***     
//****#####--Save Group info Function Start--####***
    public function save_customer_root_info($data) {

        $db_connect = $this->__construct();
        $root_name = $data[root_name];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_customer_root WHERE root_name ='" . $root_name . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry This Customer Root is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_customer_root` (root_name,status) VALUES ('$data[root_name]','$data[status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Customer Root Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Group info Function End--####***     
//****#####--Select Active Company Function Start--####***
    public function select_all_active_customer_root() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_customer_root` WHERE deletion_status= 0 AND status = 1 ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Company Function End--####***     
//****#####--Save Group info Function Start--####***
    public function save_product_without_serial_number($product_model, $quantity, $inStock, $color_name, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_destribution_serial_number` (pro_model,serial_number,quantity,instock,product_color,branch_id) VALUES ('$product_model','$product_model','$quantity','$inStock','$color_name','$branch_id')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Serial Number Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function save_product_without_serial_number_distributor($product_model, $quantity, $inStock, $color_name, $distributor_id) {

        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_destributor_serial_number_stock` (pro_model,serial_number,quantity,instock,product_color,distributor_id) VALUES ('$product_model','$product_model','$quantity','$inStock','$color_name','$distributor_id')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Serial Number Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function save_product_without_serial_number_update($product_model, $quantity, $inStock, $color_name, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_destribution_serial_number` set pro_model='$product_model',serial_number='$product_model',quantity='$quantity',instock='$inStock',product_color='$color_name',branch_id='$branch_id' where  pro_model = '$product_model'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Serial Number Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Group info Function End--####***
//****#####--Save Group info Function Start--####***
    public function save_product_without_serial_number_old($product_model, $quantity, $inStock, $color_name, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_product_serial_number` (product_model,serial_number,total_quantity,instock,color_name,branch_id) VALUES ('$product_model','$product_model','$quantity','$inStock','$color_name','$branch_id')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Serial Number Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Group info Function End--####***  
//****#####--Save Supplier info Function Start--####***
    public function save_customer_info($data) {

        $db_connect = $this->__construct();

        $customer_code = $data[customer_code];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_add_seller WHERE customer_code ='" . $customer_code . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this Custeomer is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_add_seller` (cus_group_name,cus_root_id,branch_id,seller_name,customer_code,contact_num,address,previousDues,debit_balance,credit_balance) VALUES ('$data[cus_group_name]','$data[cus_root_id]','$data[branch_id]','$data[seller_name]','$data[customer_code]','$data[contact_num]','$data[address]','$data[previousDues]','$data[debit_balance]','$data[credit_balance]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Customer Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Supplier info Function End--####*** 
//****#####--Save Supplier info Function Start--####***
    public function save_reference_info($data) {

        $db_connect = $this->__construct();

        $reference_code = $data[reference_code];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_reference WHERE reference_code ='" . $reference_code . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this Reference is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_reference` (reference_name,branch_id,reference_code,address,contact_num,opening_balance,status) VALUES ('$data[reference_name]','$data[branch_id]','$data[reference_code]','$data[address]','$data[contact_num]','$data[opening_balance]','$data[status]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Reference Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }

//****#####--Save Supplier info Function End--####***
//****#####--Select Daly Sales info Function Start--####***    


//****#####--Select Daly Sales info Function End--####*** 
//****#####--Select Daly Sales info Function Start--####***    
    public function select_all_purchases_info_by_group($group_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE pro_group = '$group_id'  ORDER BY id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***     
    //****#####--Select Daly Sales info Function Start--####***    
    public function select_all_purchases_info_by_brand($brand_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE product_brand = '$brand_id'  ORDER BY id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Daly Sales info Function End--####***   
    //****#####--Select Daly Sales info Function Start--####***    

//****#####--Select Daly Sales info Function End--####*** 
    public function updateProductSales($invoice_id, $quantity, $net_amount, $netCost_price, $totalAmount) {
        $db_connect = $this->__construct();

        $sql = "UPDATE tbl_product_salse SET
                    quantity='$quantity',
                    net_amount='$net_amount',
                    netCost_price='$netCost_price',
                    totalAmount='$totalAmount'
           WHERE id = $invoice_id";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function insertProductSales($proCode, $quantity, $cl_desc, $unit_price, $net_amount, $netCost_price, $inStock, $invoice_number) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_product_salse`(pCode,quantity,cl_desc,unit_price,net_amount,netCost_price,inStock,invoice_number) 
            VALUES('$proCode','$quantity','$cl_desc','$unit_price','$net_amount','$netCost_price','$inStock','$invoice_number')";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function RemoveFromProductSlaes($ids) {
        $db_connect = $this->__construct();

        $sql = "DELETE FROM tbl_product_salse WHERE id IN ($ids)";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function start--####***
    public function show_sales_product_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####***
//****#####--Show All Company Branch Function start--####***
    public function show_sales_invoice_product_info($invNum) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_salse` WHERE invoice_number ='$invNum' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Show All Company Branch Function End--####*** 
//****#####--Save Brand info Function Start--####***
    public function save_product_color_info($data) {

        $db_connect = $this->__construct();
        $color_name = $data[color_name];
        $query = mysqli_query($db_connect, "SELECT * FROM tbl_product_color WHERE color_name ='" . $color_name . "' AND branch_id = '$_SESSION[branch_id]' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $message = "<span style='color: white;background:#f44336;padding:8px;'>Sorry this Brand is alrady exits !!</span>";
            return $message;
        } else {
            $sql = "INSERT INTO `tbl_product_color` (color_name,status,branch_id) VALUES ('$data[color_name]','$data[status]','$_SESSION[branch_id]')";
            if (mysqli_query($db_connect, $sql)) {
                $message = "Product Color Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    }
    

//****#####--Save Brand info Function End--####*** 
    
//****#####--Select Active Material Function start--####*** 
    public function select_all_color_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_color` WHERE deletion_status= 0 AND status = 1 AND branch_id = '$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_color_info_wo_br() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_color` WHERE deletion_status= 0 AND status = 1 AND branch_id != 2";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
//****#####--Select Active Material Function End--####***   
//****#####--Select Active Material Function start--####*** 
    public function select_all_serial_info_info($branchID) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_serial_number` WHERE branch_id = '$branchID' AND status_flug = 1 AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Material Function End--####***  
//****#####--Select Active Material Function start--####*** 


//****#####--Select Active Material Function End--####***  
//****#####--Select Active Material Function start--####*** 
 

//****#####--Select Active Material Function End--####*** 
//****#####--Select Active Material Function start--####*** 
    public function select_all_model_number_info($branchID) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_serial_number` WHERE pro_model = serial_number AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Select Active Material Function End--####***  
//****#####--Save Group info Function Start--####***


//****#####--Save Group info Function End--####*** 
//****#####--Save payment info Function Start--####***
    public function save_purchase_payment_info($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_purchase_paymet` "
                . "(product_sup_name,"
                . "branch_id,"
                . "address,"
                . "contact_number,"
                . "totalDues,"
                . "date,"
                . "payment_method,"
                . "paid,"
                . "currDues,"
                . "bankName,"
                . "cheque_num,"
                . "cheque_app_date,user_id) "
                . "VALUES "
                . "('$data[product_sup_name]',"
                . "'$_SESSION[branch_id]',"
                . "'$data[address]',"
                . "'$data[contact_number]',"
                . "'$data[totalDues]',"
                . "'$date',"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$data[currDues]',"
                . "'$data[bankName]',"
                . "'$data[cheque_num]',"
                . "'$data[cheque_app_date]','$_SESSION[user_id]')";


        if (mysqli_query($db_connect, $sql)) {
            $message = "Payment Info Update Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_update_purchase_payment_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_paymet` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save payment info Function End--####***    
//****#####--Update Stock Function Start--####***  
    public function update_status_flug($branchID) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_product_serial_number` SET status_flug ='1' where branch_id = '$branchID'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Destribution successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

//****#####--Update Stock Function End--####***  
//****#####--Save Group info Function Start--####***
    public function save_destribution_model_info($product_model, $serialNumber, $quantity, $branch_id) {
        $db_connect = $this->__construct();

        $query = mysqli_query($db_connect, "SELECT * FROM tbl_destribution_product WHERE pro_model ='" . $product_model . "' AND branch_id ='" . $branch_id . "'  AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $value = mysqli_fetch_assoc($query);
            $proModel = $value['pro_model'];
            $inStock = $value['inStock'];
            $newQty = $inStock + $quantity;
            $query5 = mysqli_query($db_connect, "UPDATE `tbl_destribution_product` SET inStock = '$newQty' where pro_model = '$product_model'");
        } else {
            $query125 = mysqli_query($db_connect, "SELECT * FROM tbl_product_purchase_info WHERE pro_model ='" . $product_model . "' AND deletion_status = 0 ");
            if ($query125) {

                $value2 = mysqli_fetch_assoc($query125);

                $product_brand = $value2['product_brand'];
                $pro_model2 = $value2['pro_model'];
                $pro_group = $value2['pro_group'];
                $unit_type = $value2['unit_type'];
                $discount = $value2['discount'];
                $description = $value2['description'];
                $rp = $value2['rp'];
                $mrp = $value2['mrp'];
                $color_name = $value2['color_name'];
                $ref_product = $value2['ref_product'];
                $salePrice = $value2['salePrice'];
                $unit_price = $value2['unit_price'];
                $common_id = $value2['common_id'];
                $status_flag = "1";
                $insert = mysqli_query($db_connect, "INSERT INTO `tbl_destribution_product` (product_brand,pro_model,pro_group,unit_type,discount,description,rp,mrp,color_name,ref_product,salePrice,quantity,unit_price,inStock,branch_id,common_id,status_flag) VALUES ('$product_brand','$pro_model2','$pro_group','$unit_type','$discount','$description','$rp','$mrp','$color_name','$ref_product','$salePrice','$quantity','$unit_price','$quantity','$branch_id','$common_id','$status_flag')");
            }
        }

        $sql = "INSERT INTO `tbl_destribution_serial_number` (pro_model,serial_number,quantity,branch_id) VALUES ('$product_model','$serialNumber','$quantity','$branch_id')";
        $res = mysqli_query($db_connect, $sql);
        if ($res) {
            $query6 = mysqli_query($db_connect, "SELECT * FROM tbl_product_purchase_info WHERE pro_model ='" . $product_model . "' AND deletion_status = 0 ");
            if (mysqli_num_rows($query6) > 0) {

                $purcUp = mysqli_fetch_assoc($query6);
                $purModel = $purcUp['pro_model'];
                $purStock = $purcUp['inStock'];
                $curQty = $purStock - $quantity;
                $query7 = mysqli_query($db_connect, "UPDATE `tbl_product_purchase_info` SET inStock = '$curQty' where pro_model = '$product_model'");
                if ($query7) {
                    $message = "Destributation Successfully";
                    return $message;
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Group info Function End--####***  
//****#####--Save Group info Function Start--####***
    public function save_destribution_model_info_branch_wise($product_model2, $serialNumber2, $quantity, $branch_id, $branchID) {
        $db_connect = $this->__construct();

        $query = mysqli_query($db_connect, "SELECT * FROM tbl_destribution_product WHERE pro_model ='" . $product_model2 . "' AND brnch_id ='" . $branch_id . "'  AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $value = mysqli_fetch_assoc($query);
            $proModel = $value['pro_model'];
            $inStock = $value['inStock'];
            $newQty = $inStock + $quantity;
            $query5 = mysqli_query($db_connect, "UPDATE `tbl_destribution_product` SET inStock = '$newQty' where pro_model = '$product_model2' AND brnch_id ='" . $branch_id . "'");
        } else {
            $query125 = mysqli_query($db_connect, "SELECT * FROM tbl_product_purchase_info WHERE pro_model ='" . $product_model2 . "' AND deletion_status = 0 ");
            if ($query125) {

                $value2 = mysqli_fetch_assoc($query125);

                $product_brand = $value2['product_brand'];
                $pro_model2 = $value2['pro_model'];
                $pro_group = $value2['pro_group'];
                $unit_type = $value2['unit_type'];
                $discount = $value2['discount'];
                $description = $value2['description'];
                $rp = $value2['rp'];
                $mrp = $value2['mrp'];
                $color_name = $value2['color_name'];
                $ref_product = $value2['ref_product'];
                $salePrice = $value2['salePrice'];
                $unit_price = $value2['unit_price'];
                $common_id = $value2['common_id'];
                $status_flag = "1";
                $insert = mysqli_query($db_connect, "INSERT INTO `tbl_destribution_product` (product_brand,pro_model,pro_group,unit_type,discount,description,rp,mrp,color_name,ref_product,salePrice,quantity,unit_price,inStock,branch_id,common_id,status_flag) VALUES ('$product_brand','$pro_model2','$pro_group','$unit_type','$discount','$description','$rp','$mrp','$color_name','$ref_product','$salePrice','$quantity','$unit_price','$quantity','$branch_id','$common_id','$status_flag')");
            }
        }

        $sql = "UPDATE `tbl_destribution_serial_number` SET branch_id = '$branch_id' where pro_model = '$product_model2' AND serial_number = '$serialNumber2'";
        $res = mysqli_query($db_connect, $sql);
        if ($res) {
            $query6 = mysqli_query($db_connect, "SELECT * FROM tbl_destribution_product WHERE pro_model ='" . $product_model2 . 
			"' AND branch_id ='" . $branchID . "' AND deletion_status = 0 ");
            if (mysqli_num_rows($query6) > 0) {

                $purcUp = mysqli_fetch_assoc($query6);
                $purModel = $purcUp['pro_model'];
                $purStock = $purcUp['inStock'];
                $curQty = $purStock - $quantity;
                $query7 = mysqli_query($db_connect, "UPDATE `tbl_destribution_product` SET inStock = '$curQty' where pro_model = '$product_model2' AND branch_id = '$branchID'");
                if ($query7) {
                    $message = "Destributation Successfully";
                    return $message;
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

//****#####--Save Group info Function End--####***   
//****#####--Save Group info Function Start--####***
    public function save_destribution_serial_no_info_branch_wise($model2, $serial2, $proQty, $branch_id, $branchID) {
        $db_connect = $this->__construct();

        $query = mysqli_query($db_connect, "SELECT * FROM tbl_destribution_product WHERE pro_model ='" . $model2 . "' AND branch_id ='" . $branch_id . "' AND deletion_status = 0 ");
        if (mysqli_num_rows($query) > 0) {
            $value = mysqli_fetch_assoc($query);
            $proModel = $value['pro_model'];
            $inStock = $value['inStock'];
            $newQty = $inStock + 1;
            $query5 = mysqli_query($db_connect, "UPDATE `tbl_destribution_product` SET inStock = '$newQty' where pro_model = '$model2' AND brnch_id ='" . $branch_id . "'");
        } else {
            $query125 = mysqli_query($db_connect, "SELECT * FROM tbl_product_purchase_info WHERE pro_model ='" . $model2 . "' AND deletion_status = 0 ");
            if ($query125) {

                $value2 = mysqli_fetch_assoc($query125);

                $product_brand = $value2['product_brand'];
                $pro_model2 = $value2['pro_model'];
                $pro_group = $value2['pro_group'];
                $unit_type = $value2['unit_type'];
                $discount = $value2['discount'];
                $description = $value2['description'];
                $rp = $value2['rp'];
                $mrp = $value2['mrp'];
                $color_name = $value2['color_name'];
                $ref_product = $value2['ref_product'];
                $salePrice = $value2['salePrice'];
                $quantity = '1';
                $unit_price = $value2['unit_price'];
                $inStock = "1";
                $common_id = $value2['common_id'];
                $status_flag = "1";
                $insert = mysqli_query($db_connect, "INSERT INTO `tbl_destribution_product` (product_brand,pro_model,pro_group,unit_type,discount,description,rp,mrp,color_name,ref_product,salePrice,quantity,unit_price,inStock,branch_id,common_id,status_flag) VALUES ('$product_brand','$pro_model2','$pro_group','$unit_type','$discount','$description','$rp','$mrp','$color_name','$ref_product','$salePrice','$quantity','$unit_price','$inStock','$branch_id','$common_id','$status_flag')");
            }
        }

        $sql = "UPDATE `tbl_destribution_serial_number` SET branch_id = '$branch_id' where pro_model = '" . $model2 . "' AND serial_number = '$serial2'";

        $res = mysqli_query($db_connect, $sql);
        if ($res) {
            $query6 = mysqli_query($db_connect, "SELECT * FROM tbl_destribution_product WHERE pro_model ='" . $model2 . "' AND branch_id ='" . $branchID . "' AND deletion_status = 0 ");
            if (mysqli_num_rows($query6) > 0) {

                $purcUp = mysqli_fetch_assoc($query6);
                $purModel = $purcUp['pro_model'];
                $purStock = $purcUp['inStock'];
                $curQty = $purStock - 1;
                $query7 = mysqli_query($db_connect, "UPDATE `tbl_destribution_product` SET inStock = '$curQty' where pro_model = '$model2' AND branch_id = '$branchID'");
                if ($query7) {
                    $message = "Destributation Successfully";
                    return $message;
                }
            }
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


//****#####--Save Group info Function End--####*** 
//Saiful Starts 18-03-2018
public function select_all_active_product_model_purchase()
{
    $db_connect = $this->__construct();
    $sql = "SELECT DISTINCT pro_model FROM `tbl_destribution_product` WHERE branch_id = '$_SESSION[branch_id]'";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    } 
}
public function select_all_active_product_model_purchase_wo_br()
{
    $db_connect = $this->__construct();
    $sql = "SELECT DISTINCT pro_model FROM `tbl_destribution_product` WHERE branch_id !=2 ";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    } 
}
public function select_all_active_serial($branch_id)
{
    $db_connect = $this->__construct();
    $sql = "SELECT DISTINCT pro_model FROM `tbl_destribution_product` WHERE deletion_status = 0 AND inStock > 0 AND branch_id = '$branch_id' ";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    } 
}
public function select_all_active_pro_model($branch_id)
{
    $db_connect = $this->__construct();
    $sql = "SELECT DISTINCT pro_model FROM `tbl_destribution_product`  WHERE branch_id = '$branch_id' ";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    } 
}
public function select_all_active_serial_return_sale($branch_id)
{
    $db_connect = $this->__construct();
    $sql = "SELECT DISTINCT pro_model FROM `tbl_destribution_product` WHERE branch_id = '$branch_id'";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    } 
}
    public function select_all_sales_return_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }  
        public function select_all_sales_return_info_daily($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE branch_id = '$branch_id' AND date = DATE(NOW()) AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_all_sales_return_info_monthly($branch_id) {

        $db_connect = $this->__construct();
        
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $db_date = date("Y-m", $timestamp);

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE branch_id = '$branch_id' AND date_month = '$db_date' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
    
        public function delete_sales_return_product($id) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_sale_return_invoice` SET deletion_status = 1 WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    } 
    public function show_sales_return_invoc_info($rand,$user) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_sale_return` WHERE rand_inv = '$rand' and user_id = '$user'  ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_monthly_sales_return_info_by_date($from, $to, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE date BETWEEN '$from' AND '$to' AND branch_id = '$branch_id' AND deletion_status = 0 ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
       public function save_destribution_serial_no_info($sl_no,$branch_id) {

        $db_connect = $this->__construct();

         $sql = "UPDATE `tbl_destribution_serial_number` SET branch_id = '$branch_id' WHERE serial_number = '$sl_no' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return "Transfer Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_destribution_serial_no_info_distributor($sl_no,$branch_id) {

        $db_connect = $this->__construct();

         $sql = "UPDATE `tbl_destribution_serial_number` SET inStock = '0',deletion_status = 1 WHERE branch_id = '$branch_id' AND serial_number = '$sl_no' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return "Transfer Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function save_destribution_serial_no_info_distributor($sl_no,$branch_id,$distributor_id) {

        $db_connect = $this->__construct();
         $model = $this->getOneColBranch('pro_model', 'tbl_destribution_serial_number', 'serial_number', $sl_no,$branch_id);
         $color = $this->getOneColBranch('product_color','tbl_destribution_serial_number', 'serial_number', $sl_no,$branch_id);
         $sql = "INSERT INTO `tbl_destributor_serial_number_stock`(pro_model,serial_number,quantity,inStock,branch_id,deletion_status,product_color,distributor_id)VALUES('$model','$sl_no',1,1,'0',0,'$color','$distributor_id')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Transfer Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
   public function update_stock_mod_dis($pm,$stck_old,$branchID){
      $db_connect = $this->__construct();
       $sql = "UPDATE `tbl_destribution_product` SET inStock = '$stck_old' WHERE branch_id = '$branchID' AND pro_model = '$pm' ";
        if (mysqli_query($db_connect, $sql)) {
            
            return "Transfer Successfully";
        } else {
            die('Query problem Update' . mysqli_error($db_connect));
        }
    }
       public function update_stock_mod_dist($pm,$stck_old,$distributor_id){
      $db_connect = $this->__construct();
       $sql = "UPDATE `tbl_destribution_product` SET inStock = '$stck_old' WHERE distributor_id = '$distributor_id' AND pro_model = '$pm' ";
        if (mysqli_query($db_connect, $sql)) {
            
            return "Transfer Successfully";
        } else {
            die('Query problem Update' . mysqli_error($db_connect));
        }
    }

       public function update_stock_mod_dis_ser($pm,$stck_old,$branchID){
      $db_connect = $this->__construct();
       $sql = "UPDATE `tbl_destribution_serial_number` SET inStock = '$stck_old' WHERE branch_id = '$branchID' AND pro_model = '$pm' ";
        if (mysqli_query($db_connect, $sql)) {
            
            return "Transfer Successfully";
        } else {
            die('Query problem Update' . mysqli_error($db_connect));
        }
    }
          public function update_stock_mod_dist_ser($pm,$stck_old,$distributor_id){
      $db_connect = $this->__construct();
       $sql = "UPDATE `tbl_destribution_serial_number_stock` SET inStock = '$stck_old' WHERE distributor_id = '$distributor_id' AND pro_model = '$pm' ";
        if (mysqli_query($db_connect, $sql)) {
            
            return "Transfer Successfully";
        } else {
            die('Query problem Update' . mysqli_error($db_connect));
        }
    }
//Saiful End


// sohan start
    public function select_all_model_distribution_tbl($branchID) {
        $db_connect = $this->__construct();

        $sql = "SELECT pro_model FROM `tbl_destribution_product` WHERE branch_id = '$branchID' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
// sohan end


// sohan start 27.03.2018
    public function select_all_active_account_head() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account_head` WHERE deletion_status= 0 AND status = 1 ORDER BY id";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
    public function select_all_active_category_head() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account_category`";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
    public function select_all_active_main_inc_head() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_main_inc_head` WHERE deletion_status= 0 AND status = 1 ORDER BY id";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
       public function select_all_active_account_cash() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_opening_cash`";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_active_master_head() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_master_head` WHERE deletion_status= 0 AND status = 1 ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    

    public function save_account_head($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($data['creation_date']));
         $date_month = date('Y-m', strtotime($data['date']));
          $hd = $this->getOneCol('id','tbl_account_head','id',$data['head_name']);
         if(empty($hd)){
        $sql = "INSERT INTO `tbl_account_head` (head_name,head_code,creation_date,status) VALUES ('$data[head_name]','$data[head_code]','$date','$data[status]')";
        if (mysqli_query($db_connect, $sql)) {
            return "Account Head Info Save Successfully";           
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }else{
        return "Already Exist";  
    }
    }
    
    public function save_account_category($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d');
          $hd = $this->getOneCol('id','tbl_account_category','id',$data['category']);
         if(empty($hd)){
        $sql = "INSERT INTO `tbl_account_category` (category,date) VALUES ('$data[category]','$date')";
        if (mysqli_query($db_connect, $sql)) {
            return "Account Category Info Save Successfully";           
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }else{
        return "Already Exist";  
    }
    }
    
    public function save_main_inc_head($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($data['creation_date']));
         $date_month = date('Y-m', strtotime($data['date']));
          $hd = $this->getOneCol('id','tbl_main_inc_head','id',$data['head_name']);
         if(empty($hd)){
        $sql = "INSERT INTO `tbl_main_inc_head` (head_name,head_code,creation_date,status) VALUES ('$data[head_name]','$data[head_code]','$date','$data[status]')";
        if (mysqli_query($db_connect, $sql)) {
            return "Account Head Info Save Successfully";           
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }else{
        return "Already Exist";  
    }
    }
    
    public function delete_main_inc_head($id) {

        $db_connect = $this->__construct();
        //$sql = "DELETE FROM `tbl_purches_pro_comn_info` WHERE id = '$id'";

        $sql = "DELETE FROM`tbl_main_inc_head` WHERE id='$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
        public function save_opening_cash($data) {

        $db_connect = $this->__construct();
         $date = date('Y-m-d', strtotime($data['creation_date']));

        $sql = "INSERT INTO `tbl_opening_cash` (amount,user_id,date) VALUES ('$data[amount]','$_SESSION[user_id]','$date')";
        if (mysqli_query($db_connect, $sql)) {
            return "Opening Cash Save Successfully";           
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function save_master_head($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($data['creation_date']));
         $date_month = date('Y-m', strtotime($data['date']));

        $sql = "INSERT INTO `tbl_master_head` (head_name,creation_date,status) VALUES ('$data[head_name]','$date','$data[status]')";
        if (mysqli_query($db_connect, $sql)) {
            return "Account Head Info Save Successfully";           
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    // public function save_account_info($data,$user_id,$rand) {

    //     $db_connect = $this->__construct();

    //     $date = date('Y-m-d', strtotime($data['date']));
    //     $date_month = date('Y-m', strtotime($data['date']));
    //     $sql = "INSERT INTO `tbl_account_inv` (date,total_amount,invoice_number,date_month,user_id) VALUES ('$date','$data[total_amount]','$data[invoice_number]','$date_month','$user_id')";
    //     if (mysqli_query($db_connect, $sql)) {
    //         $rand = mysqli_insert_id($db_connect);
    //         $loop = count($data['amount']);
    //         $j = 0;
    //         for($i = 0; $i < $loop; $i++){
    //             $head = $data['account_head'][$i];
                
    //             $amn = $data['amount'][$i];
    //             $pur = $data['purpose'][$i];
    //             $account_type = $data['account_type'][$i];
    //             $voucher_no = $data['voucher_no'][$i];
    //             $mode = $data['mode'][$i];
    //             $bank_desc = $data['bank_desc'][$i];
    //             $sql = "INSERT INTO `tbl_account` (account_head,amount,branch_id,account_type,date,purpose,date_month,user_id,rand_inv,voucher_no,mode,bank_desc) VALUES ('$head','$amn','$_SESSION[branch_id]','$account_type','$date','$pur','$date_month','$user_id','$rand','$voucher_no','$mode','$bank_desc')";
    //             if (mysqli_query($db_connect, $sql)) {
    //                     $j = 1;
    //             } else {
    //                 die('Query problem' . mysqli_error($db_connect));
    //             } 
    //         }
    //         if($j==1){
    //             return 'Account Added Successfully!';
    //         }
                      
    //     } else {
    //         die('Query problem' . mysqli_error($db_connect));
    //     }      
    // }
    public function save_account_info($data,$user_id,$rand) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($data['date']));
         $date_month = date('Y-m', strtotime($data['date']));

        $sql = "INSERT INTO `tbl_account` (account_head,amount,branch_id,account_type,date,purpose,status,date_month,user_id,rand_inv,category) VALUES ('$data[account_head]','$data[amount]','$data[branch_id]','$data[account_type]','$date','$data[purpose]','$data[status]','$date_month','$user_id','$rand','$data[category]')";
        if (mysqli_query($db_connect, $sql)) {
            return "Account Info Save Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function save_main_inc_info($data,$user_id,$rand) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($data['date']));
         $date_month = date('Y-m', strtotime($data['date']));

        $sql = "INSERT INTO `tbl_main_inc` (account_head,amount,branch_id,account_type,date,purpose,status,date_month,user_id,rand_inv) VALUES ('$data[account_head]','$data[amount]','$data[branch_id]','$data[account_type]','$date','$data[purpose]','$data[status]','$date_month','$user_id','$rand')";
        if (mysqli_query($db_connect, $sql)) {
            return "Account Info Save Successfully";           
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_account_info_print($rand,$branch_id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account`  WHERE deletion_status= '0' AND id='$rand' AND branch_id ='$branch_id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
      public function select_account_invoice_info_print($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account`  WHERE deletion_status= '0' AND rand_inv='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_account($id) {

        $db_connect = $this->__construct();
        //$sql = "DELETE FROM `tbl_purches_pro_comn_info` WHERE id = '$id'";

        $sql = "DELETE FROM `tbl_account` WHERE id='$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_vip_deliver($id) {

        $db_connect = $this->__construct();

        $sql = "DELETE FROM `tbl_vip_deliver` WHERE id='$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_main_inc($id) {

        $db_connect = $this->__construct();
        //$sql = "DELETE FROM `tbl_purches_pro_comn_info` WHERE id = '$id'";

        $sql = "DELETE FROM `tbl_main_inc` WHERE id='$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    
      public function select_account_inv_info($id) {

        $db_connect = $this->__construct();
        //$sql = "DELETE FROM `tbl_purches_pro_comn_info` WHERE id = '$id'";

        $sql = "SELECT * FROM tbl_account_inv order by id desc";

        if (mysqli_query($db_connect, $sql)) {
            $message = mysqli_query($db_connect, $sql);
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_account_head($id) {

        $db_connect = $this->__construct();
        //$sql = "DELETE FROM `tbl_purches_pro_comn_info` WHERE id = '$id'";

        $sql = "UPDATE `tbl_account_head` SET deletion_status = 1 WHERE id='$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
        public function delete_master_head($id) {

        $db_connect = $this->__construct();
        //$sql = "DELETE FROM `tbl_purches_pro_comn_info` WHERE id = '$id'";

        $sql = "UPDATE `tbl_master_head` SET deletion_status = 1 WHERE id='$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_account_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE deletion_status= 0 AND branch_id='$_SESSION[branch_id]'  order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_account_info_limit() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE deletion_status= 0 AND branch_id='$_SESSION[branch_id]' order by id desc limit 500";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_main_inc_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_main_inc` WHERE deletion_status= 0 AND branch_id='$_SESSION[branch_id]'  order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function show_account_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_account` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function show_main_inc_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_main_inc` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
     public function show_account_head_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_account_head` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function show_main_inc_head_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_main_inc_head` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
     public function show_master_head_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_master_head` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
     public function update_account_info($data,$user_id) {
        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "UPDATE `tbl_account` SET "
                . "account_head='$data[account_head]',"
                . "amount='$data[amount]',"
                . "account_type='$data[account_type]',"
                . "date ='$date',"
                . "user_id ='$user_id',"
                . "purpose ='$data[purpose]',"
                . "status ='$data[status]'"
                . " WHERE id='$data[id]'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Account Info Update successfully";
            header('Location: manage_account.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_main_inc_info($data,$user_id) {
        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "UPDATE `tbl_main_inc` SET "
                . "account_head='$data[account_head]',"
                . "amount='$data[amount]',"
                . "account_type='$data[account_type]',"
                . "date ='$date',"
                . "user_id ='$user_id',"
                . "purpose ='$data[purpose]',"
                . "status ='$data[status]'"
                . " WHERE id='$data[id]'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Account Info Update successfully";
            header('Location: manage_main_inc_content.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_account_head_info($data) {
        $db_connect = $this->__construct();

     $date = date('Y-m-d', strtotime($data['creation_date']));

        $sql = "UPDATE `tbl_account_head` SET "
                . "head_name='$data[head_name]',"
                . "head_code='$data[head_code]',"
                . "creation_date ='$date',"
                . "status ='$data[status]'"
                . " WHERE id='$data[id]'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Account Head Info Update successfully";
            header('Location:account_head.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_main_inc_head_info($data) {
        $db_connect = $this->__construct();

     $date = date('Y-m-d', strtotime($data['creation_date']));

        $sql = "UPDATE `tbl_main_inc_head` SET "
                . "head_name='$data[head_name]',"
                . "head_code='$data[head_code]',"
                . "creation_date ='$date',"
                . "status ='$data[status]'"
                . " WHERE id='$data[id]'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Account Head Info Update successfully";
            header('Location:add_main_inc_head.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
        public function update_master_head_info($data) {
        $db_connect = $this->__construct();

     $date = date('Y-m-d', strtotime($data['creation_date']));

        $sql = "UPDATE `tbl_master_head` SET "
                . "head_name='$data[head_name]',"
                . "creation_date ='$date',"
                . "status ='$data[status]'"
                . " WHERE id='$data[id]'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Master Head Info Update successfully";
            header('Location:master_head.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
// sohan end 27.03.2018


// sohan start 28.03.2018

public function save_cash_book_opening_balance($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($data['date']));
         $month = date('Y-m', strtotime($data['month']));

        $sql = "INSERT INTO `tbl_cash_book` (opening_balance,branch_id,date,status,month) VALUES ('$data[opening_balance]','$data[branch_id]','$date','$data[status]','$month')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Opening Balance Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_previous_motnh_balance($branch) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_cash_book` WHERE branch_id = '$branch'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function select_sale_in_cash_search($branch_id,$from,$to){
        $db_connect = $this->__construct();

        $sql = "SELECT cash_paid,customer_id,date FROM `tbl_sale_invoice` WHERE branch_id = '$branch_id' and date between '$from' and '$to' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        } 
    }
 
    public function select_collection_in_cash_search($branch_id,$from,$to){
       $db_connect = $this->__construct();

        $sql = "SELECT paid,date,customer_name FROM `tbl_sales_payment_info` WHERE date between '$from' and '$to' and branch_id = '$branch_id' and payment_method='Cash' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }    
    }
    public function select_expense_in_cash_search($branch_id,$from,$to){
       $db_connect = $this->__construct();

        $sql = "SELECT account_head,date,amount FROM `tbl_account` WHERE date between '$from' and '$to' and branch_id = '$branch_id' and account_type='1' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
    }
        public function select_return_in_cash_search($branch_id,$from,$to){
       $db_connect = $this->__construct();

        $sql = "SELECT customer_id,date,cash_paid FROM `tbl_sale_return_invoice` WHERE date between '$from' and '$to'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
    }

       public function select_payment_in_cash_search($branch_id,$from,$to){
       $db_connect = $this->__construct();

        $sql = "SELECT paid,date,product_sup_name FROM `tbl_purchase_paymet` WHERE date between '$from' and '$to' and branch_id = '$branch_id' and payment_method='Cash' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }    
    }

 public function select_sale_in_cash($branch_id,$date){
        $db_connect = $this->__construct();

        $sql = "SELECT cash_paid,customer_id,date FROM `tbl_sale_invoice` WHERE branch_id = '$branch_id' and date = '$date' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        } 
    }
    public function select_collection_in_cash($branch_id,$date){
       $db_connect = $this->__construct();

        $sql = "SELECT paid,date,customer_name FROM `tbl_sales_payment_info` WHERE date = '$date' and branch_id = '$branch_id' and payment_method='Cash' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }    
    }
    public function select_expense_in_cash($branch_id,$date){
       $db_connect = $this->__construct();

        $sql = "SELECT account_head,date,amount FROM `tbl_account` WHERE date = '$date' and branch_id = '$branch_id' and account_type='1' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
    }
        public function select_return_in_cash($branch_id,$date){
       $db_connect = $this->__construct();

        $sql = "SELECT customer_id,date,cash_paid FROM `tbl_sale_return_invoice` WHERE date = '$date'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }   
    }
        public function select_payment_in_cash($branch_id,$date){
       $db_connect = $this->__construct();

        $sql = "SELECT paid,date,product_sup_name FROM `tbl_purchase_paymet` WHERE date = '$date' and branch_id = '$branch_id' and payment_method='Cash' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }    
    }
       public function select_purchase_in_cash($branch_id,$date){
       $db_connect = $this->__construct();

        $sql = "SELECT cashPaid as totalAmount,date,supplier_id FROM `tbl_productpurcheseinvoice_info` WHERE branch_id = '$branch_id' and payment_method='Cash' AND date='$date'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
   }
       public function select_purchase_in_cash_search($branch_id,$from,$to){
       $db_connect = $this->__construct();

        $sql = "SELECT cashPaid as totalAmount,date,supplier_id FROM `tbl_productpurcheseinvoice_info` WHERE date between '$from' and '$to' and branch_id = '$branch_id' and payment_method='Cash' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }    
    }
public function select_cashbook_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_cash_book` WHERE status = 1 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function delete_cashbook_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE from `tbl_cash_book` WHERE id='$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_cashbook_info_by_id($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_cash_book` WHERE id='$id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function update_cashbook_info($data) {

        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $month = date('Y-m', strtotime($data['month']));
        $sql = "UPDATE `tbl_cash_book` SET opening_balance='$data[opening_balance]',date='$date',month='$month',status='$data[status]' where id='$data[id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Cashbook Info Updated Successfully";
            header('Location: manage_daily_cash_book.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

 public function select_all_supplier($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * from tbl_product_supplier";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_supplier_laser_info_single($supplier,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_paymet` WHERE branch_id='$branch_id' AND product_sup_name = '$supplier'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_supplier_laser_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_paymet` WHERE branch_id = '$branch_id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


public function select_total_dues_by_supplier($supplier,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * from tbl_product_supplier where id='$supplier' AND branch_id = '$branch_id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_total_dues($branch_id) {

        $db_connect = $this->__construct();
        $sql="SELECT * from tbl_product_supplier WHERE branch_id = '$branch_id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


// sohan end 28.03.2018

// saiful 28.03.2018
  public function tsi_balance_sheet($from,$to) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_sale,sum(vat) as vat,sum(TotalCostAmount) as total_cost, sum(total_discount) as total_discount , sum(inst_discount) as discount  from tbl_sale_invoice where branch_id = '$_SESSION[branch_id]' AND date BETWEEN '$from' AND '$to'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function tsri_balance_sheet($from,$to) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_return,sum(TotalCostAmount) as total_return_back,sum(total_discount_back) as return_discount from tbl_sale_return_invoice where branch_id = '$_SESSION[branch_id]' AND date BETWEEN '$from' AND '$to'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function ta_balance_sheet($from,$to) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(amount) as expenses from tbl_account where account_type='1' AND branch_id = '$_SESSION[branch_id]' AND date BETWEEN '$from' AND '$to' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
        public function daily_tsi_balance_sheet($date_month) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_sale,sum(vat) as vat,sum(TotalCostAmount) as total_cost, sum(tk_dis) as total_discount , sum(inst_discount) as discount from tbl_sale_invoice where date='$date_month' AND branch_id = '$_SESSION[branch_id]' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_tsri_balance_sheet($date_month) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_return,sum(TotalCostAmount) as total_return_back,sum(total_discount_back) as return_discount from tbl_sale_return_invoice where date='$date_month' AND branch_id = '$_SESSION[branch_id]'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function daily_ta_balance_sheet($date_month) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(amount) as expenses from tbl_account where date='$date_month' and account_type='1' AND branch_id = '$_SESSION[branch_id]'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

        public function daily_tsi_balance_sheet_dash($date_month,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_sale,sum(TotalCostAmount) as total_cost, sum(inst_discount) as total_discount from tbl_sale_invoice where date='$date_month' AND branch_id = '$branch_id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_tsri_balance_sheet_dash($date_month,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_return,sum(TotalCostAmount) as total_return_back,sum(total_discount_back) as return_discount from tbl_sale_return_invoice where date='$date_month' AND branch_id = '$branch_id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function daily_ta_balance_sheet_dash($date_month,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(amount) as expenses from tbl_account where date='$date_month' and account_type='1' AND branch_id = '$branch_id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_customer_sale_history_all($customer,$branch,$from,$to){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE customer_id = '$customer' AND branch_id = '$branch' AND date BETWEEN '$from' AND '$to' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_customer_sale_return_history_all($customer,$branch,$from,$to){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE customer_id = '$customer' AND branch_id = '$branch' AND date BETWEEN '$from' AND '$to' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_customer_sale_payment_history_all($customer,$branch,$from,$to){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE customer_name = '$customer' AND branch_id = '$branch' AND date BETWEEN '$from' AND '$to' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_customer_sale_history_customer($customer,$branch){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE customer_id = '$customer' AND branch_id = '$branch' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_customer_sale_return_history_customer($customer,$branch){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE customer_id = '$customer' AND branch_id = '$branch' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

 public function select_customer_sale_payment_history_customer($customer,$branch){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE customer_name = '$customer' AND branch_id = '$branch' AND deletion_status= 0";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
// saiful 28.03.2018

// sohan start 29.03.2018
public function select_all_monthly_purchase_info_by_month($from,$to,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE date_month BETWEEN '$from' AND '$to' AND branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_distinct_active_customer($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT seller_name, id FROM `tbl_add_seller` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_active_customer_by_id($customer,$branch) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE branch_id = '$branch' and customer_name = '$customer' ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_active_customer_full($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE branch_id = '$branch_id' ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_total_dues_by_customer($customer) {

        $db_connect = $this->__construct();

        $sql = "SELECT seller_name, sum(`previousDues`) as dues from tbl_add_seller WHERE seller_name='$customer'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_total_dues_customer($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT distinct seller_name, sum(`previousDues`) as dues from tbl_add_seller where branch_id='$branch_id' group by seller_name";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_daily_purches_info_by_user_rand_inv($branch_id,$today_date,$user_id,$rand_inv) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE date = '$today_date' AND branch_id = '$branch_id' AND rand_inv = '$rand_inv' AND user_id = '$user_id' AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_daily_purches_info_by_user_rand_inv_popup($branch_id,$user_id,$rand_inv) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE  branch_id = '$branch_id' AND rand_inv = '$rand_inv' AND user_id = '$user_id' AND deletion_status= 0 ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
public function select_all_monthly_purchase_info_current_month($current_month,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_productpurcheseinvoice_info` WHERE date_month = '$current_month' AND branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

// sohan end 29.03.2018

//saiful 29.03
   public function select_all_active_sales_invoice_info($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE deletion_status= 0 AND branch_id = '$branch_id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function collection_popup($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE id = '$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
       public function select_all_update_payment_info_by_date($customer_name, $from, $to, $branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE branch_id = '$branch_id' AND (customer_name = '$customer_name' OR (date BETWEEN '$from' AND '$to' )) ORDER BY id DESC";

        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_daily_update_payment_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE branch_id = '$branch_id' AND ( date = DATE(NOW()) AND deletion_status= 0) ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

        public function payment_popup($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_purchase_paymet` WHERE deletion_status= 0 AND id = '$id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }   
    // public function update_final_cuestoer_dues_info($cuesId, $finalDues) {
    //     $db_connect = $this->__construct();

    //     $sql = "UPDATE `tbl_add_seller` SET previousDues = '$finalDues' WHERE id = '$cuesId'";
    //     $result = mysqli_query($db_connect, $sql);
    //     if ($result) {
    //         $message = "Collection Successfully";
    //         return $message;
    //     } else {
    //         die('Query Problem' . mysqli_error($db_connect));
    //     }
    // }
//saiful 29.03
public function select_all_active_product_model() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE deletion_status= 0 AND branch_id='$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
 public function select_all_daily_wise_sales_product_info_search($from, $to, $model_id,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT `pro_model`,sum(quantity) as qty,`unit_price`, `totalAmount` FROM  `tbl_product_salse` WHERE date BETWEEN '$from' AND '$to' AND branch_id = '$branch_id' AND pro_model = '$model_id' OR pro_model = '$model_id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_daily_wise_sales_product_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT `pro_model`,sum(quantity) as qty,`unit_price`, `totalAmount`FROM `tbl_product_salse` WHERE branch_id = '$branch_id' AND deletion_status= 0 GROUP BY `pro_model` DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_purchases_info_by_model($pro_model) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_product_purchase_info` WHERE pro_model = '$pro_model'  ORDER BY id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_group_wise_purchases_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_destribution_product` WHERE  deletion_status= 0 ORDER BY id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
// sohan start 24.04.2018
public function add_product_distribution_report_serial($mod,$sl_no,$branchID,$branch_id,$st_clr) {
        $db_connect = $this->__construct();

            $sql = "INSERT INTO `product_distribution_report_serial`("
                    . "pro_model,"
                    . "serial_number,"
                    . "quantity,"
                    . "inStock,"
                    . "from_branch,"
                    . "to_branch,"
                    . "deletion_status,"
                    . "product_color) "
                    . "VALUES "
                    . "('$mod',"
                    . "'$sl_no',"
                    . "'1',"
                    . "'1',"
                    . "'$branchID',"
                    . "'$branch_id',"
                    . "'0',"
                    . "'$st_clr')";

            $result = mysqli_query($db_connect, $sql);
            if ($result) {
                $message = "Product Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
    }

public function add_product_distribution_report_model($brnd,$mod,$grp,$clr,$rp,$mrp,$des,$up,$quan,$branchID,$branch_id) {
        $db_connect = $this->__construct();

            $sql = "INSERT INTO `product_distribution_report_model`("
                    . "product_brand,"
                    . "pro_model,"
                    . "pro_group,"
                    . "description,"
                    . "rp,"
                    . "mrp,"
                    . "color_name,"
                    . "quantity,"
                    . "unit_price,"
                    . "from_branch,"
                    . "to_branch) "
                    . "VALUES "
                    . "('$brnd',"
                    . "'$mod',"
                    . "'$grp',"
                    . "'$des',"
                    . "'$rp',"
                    . "'$mrp',"
                    . "'$clr',"
                    . "'$quan',"
                    . "'$up',"
                    . "'$branchID',"
                    . "'$branch_id')";

            $result = mysqli_query($db_connect, $sql);
            if ($result) {
                $message = "Product Info Save Successfully";
                return $message;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }

    }

// sohan end 24.04.2018
public function select_all_distributed_pro_model($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `product_distribution_report_model` WHERE from_branch = '$branch_id' AND deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_distributed_pro_serial($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `product_distribution_report_serial` WHERE from_branch = '$branch_id' AND deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
// sohan start 08.05.2018
public function select_all_todays_sales_return_payment($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE branch_id = '$branch_id' AND date = DATE(NOW()) AND deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_todays_sales_payment_tot() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` WHERE  date = DATE(NOW()) AND deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_todays_sales_return_payment_tot() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_return_invoice` WHERE  date = DATE(NOW()) AND deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_todays_cullection_payment_tot() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_payment_info` WHERE  date = DATE(NOW()) AND deletion_status = 0 ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function daily_tsi_balance_sheet_dash_tots($date_month,$branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_sale,sum(TotalCostAmount) as total_cost, sum(inst_discount) as total_discount from tbl_sale_invoice where date='$date_month'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function daily_tsri_balance_sheet_dash_tots($date_month) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(totalAmount) as total_return,sum(TotalCostAmount) as total_return_back,sum(total_discount_back) as return_discount from tbl_sale_return_invoice where date='$date_month'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function daily_ta_balance_sheet_dash_tots($date_month) {

        $db_connect = $this->__construct();

        $sql = "SELECT sum(amount) as expenses from tbl_account where date='$date_month' and account_type='1'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_branch_except_main(){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_branch` WHERE  deletion_status= 0 AND id != 1 ORDER BY id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_all_active_customer_search($branch_id,$cust_group) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_seller` WHERE branch_id = '$branch_id' AND cus_group_name = $cust_group AND deletion_status= 0 AND status = 1 ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
// sohan end 08.05.2018

  public function add_customer(){
    $db_connect = $this->__construct();
    $cus_group_name =$_POST["cus_group_name"];
    $cus_root_id =$_POST["cus_root_id"];
    $branch_id =$_POST["branch_id"];
    $seller_name = $_POST["seller_name"];
    $customer_code =$_POST["customer_code"];
    $contact_num =$_POST["contact_num"];
    $address = $_POST["address"];
    $previousDues =$_POST["opening_balance"];
    $opening_balance =$_POST["opening_balance"];
    $msg = $_POST["msg"];
    $query = "INSERT INTO `tbl_add_seller`(cus_group_name,cus_root_id,branch_id,seller_name,customer_code,contact_num,address,previousDues,opening_balance,status)VALUES('$cus_group_name','$cus_root_id',$branch_id,'$seller_name','$customer_code','$contact_num','$address','$previousDues','$opening_balance','1')
             ";
    if (mysqli_query($db_connect, $query)) {
        return "Customer Added Successfully!";
    }
    else
    {
     die('Query Problem' . mysqli_error($db_connect));   
    }
}
 public function upname($name,$back) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_purchase_invoice_info` SET pro_model = '$name' WHERE pro_model= '$back' AND branch_id = '$_SESSION[branch_id]'";
        $sql1 = "UPDATE `tbl_destribution_product` SET pro_model = '$name' WHERE pro_model= '$back' AND branch_id = '$_SESSION[branch_id]'";
        if (mysqli_query($db_connect, $sql)) {
           $done = mysqli_query($db_connect, $sql1);

           if($done)
           return $name;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_damage_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_damage_invoice` WHERE branch_id = '$branch_id' AND date = DATE(NOW())  ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function insertdamageinvoiceproduct($branch_id,$invoice_number,$date,$pro_model,$unit_price,$total,$user_id,$qty,$month){
   $db_connect = $this->__construct();
    $sql = "INSERT INTO tbl_damage_invoice(branch_id,invoice_number,date,pro_model,total,user_id,qty,date_month,unit_price)VALUES('$branch_id','$invoice_number','$date','$pro_model','$total','$user_id','$qty','$month','$unit_price')";
        if (mysqli_query($db_connect, $sql)) {           
            return $message = "Damage Info Saved Successfully";
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
}

function random() { 
    $chars = "1522683945967890935543437841023415678097864439"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 
    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 
    $hudai = $pass; 
    $ano = substr($hudai, -2);
    $check = mb_substr($pass, 0, 3);
    return $ano.$check;
}
    public function save_soil_budget_info($data) {

        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        $fromformat = date('Ymd', strtotime($_POST['from_date']));
        $toformat = date('Ymd', strtotime($_POST['to_date']));

        $sql = "INSERT INTO `tbl_soil_budget` "
                . "(from_date,"
                . "to_date,"
                . "total_budget,"
                . "date,"
                . "fromformat,"
                . "toformat,"
                . "user_id)"
                . "VALUES "
                . "('$from_date',"
                . "'$to_date',"
                . "'$data[total_budget]',"
                . "'$date',"
                . "'$fromformat',"
                . "'$toformat',"
                . "'$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Budget Added Succcessfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_soil_budget_info(){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_budget` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_soil_budget_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_budget` WHERE id='$id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
    public function delete_soil_budget_info($id){
        $db_connect = $this->__construct();

        $sql = "DELETE FROM `tbl_soil_budget` WHERE id = '$id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
           $_SESSION['message'] = "Delete Successfully";
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_soil_budget_info($data) {
        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        $fromformat = date('Ymd', strtotime($_POST['from_date']));
        $toformat = date('Ymd', strtotime($_POST['to_date']));

        $sql = "UPDATE `tbl_soil_budget` SET from_date='$from_date',to_date='$to_date',total_budget='$data[total_budget]',date='$date',fromformat='$fromformat',toformat='$toformat',user_id='$_SESSION[user_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Updated Successfully";
            header("Location: manage_soil_budget.php");
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function save_koyla_budget_info($data) {

        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        $fromformat = date('Ymd', strtotime($_POST['from_date']));
        $toformat = date('Ymd', strtotime($_POST['to_date']));

        $sql = "INSERT INTO `tbl_koyla_budget` "
                . "(from_date,"
                . "to_date,"
                . "total_budget,"
                . "date,"
                . "fromformat,"
                . "toformat,"
                . "user_id)"
                . "VALUES "
                . "('$from_date',"
                . "'$to_date',"
                . "'$data[total_budget]',"
                . "'$date',"
                . "'$fromformat',"
                . "'$toformat',"
                . "'$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Budget Added Succcessfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_koyla_budget_info(){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_budget` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_koyla_budget_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_budget` WHERE id='$id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
    public function delete_koyla_budget_info($id){
        $db_connect = $this->__construct();

        $sql = "DELETE FROM `tbl_koyla_budget` WHERE id = '$id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
           $_SESSION['message'] = "Delete Successfully";
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_koyla_budget_info($data) {
        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $from_date = date('Y-m-d', strtotime($_POST['from_date']));
        $to_date = date('Y-m-d', strtotime($_POST['to_date']));
        $fromformat = date('Ymd', strtotime($_POST['from_date']));
        $toformat = date('Ymd', strtotime($_POST['to_date']));

        $sql = "UPDATE `tbl_koyla_budget` SET from_date='$from_date',to_date='$to_date',total_budget='$data[total_budget]',date='$date',fromformat='$fromformat',toformat='$toformat',user_id='$_SESSION[user_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Updated Successfully";
            header("Location: manage_koyla_budget.php");
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function save_mess_budget_info($data) {

        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_mess_budget` "
                . "(total_budget,"
                . "date,"
                . "user_id)"
                . "VALUES "
                . "('$data[total_budget]',"
                . "'$date',"
                . "'$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Budget Added Succcessfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_mess_budget_info(){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_mess_budget` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_mess_budget_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_mess_budget` WHERE id='$id'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
    public function delete_mess_budget_info($id){
        $db_connect = $this->__construct();

        $sql = "DELETE FROM `tbl_mess_budget` WHERE id = '$id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
           $_SESSION['message'] = "Delete Successfully";
            header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_mess_budget_info($data) {
        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', strtotime($_POST['date']));


        $sql = "UPDATE `tbl_mess_budget` SET total_budget='$data[total_budget]',date='$date',user_id='$_SESSION[user_id]'";
        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Updated Successfully";
            header("Location: manage_mess_budget.php");
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 

    //emran
    public function add_kacha_sorder($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_kacha_sordar`(seller_name,contact_num,address,father_name,mother_name,nid_no,bank_acc_no,bank_name,branch_name,previousDues,opening_balance,user_id)VALUES('$data[seller_name]','$data[contact_num]','$data[address]','$data[father_name]','$data[mother_name]','$data[nid_no]','$data[bank_acc_no]','$data[bank_name]','$data[branch_name]','$data[opening_balance]','$data[opening_balance]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Kacha Sorder added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function add_staff_sorder($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_add_sorder_staff`(name,mobile,address,nid,salary_scale,weekly,category,balance,manual)VALUES('$data[seller_name]','$data[contact_num]','$data[address]','$data[nid_no]','$data[salary_scale]','$data[weekly]','$data[category]','0','$data[manual]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Kacha Sorder added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_staff_sorder_pro($data,$id) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_staff_sorder_pro`(sorder_id,week,production,attendance,date)VALUES('$id','$data[week]','$data[production]','$data[attendance]','$data[date]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Data added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_staff_sorder_payment($data,$id) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_staff_sorder_payment`(sorder_id,payment,new_total,comment,date)VALUES('$id','$data[payment]','$data[total]','$data[comment]','$data[date]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Data added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_main_balance_sorder($data,$id) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_add_sorder_staff` SET balance='$data[total]' WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Data added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_staff_sorder() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_sorder_staff` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_staff_sorder_pro($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_staff_sorder_pro` WHERE sorder_id='$id' ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_staff_sorder_payment($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_staff_sorder_payment` WHERE sorder_id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function staff_sorder($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_sorder_staff` WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_kacha_sorder() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sordar` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_kacha_sorder_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_kacha_sordar` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_staff_sorder_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_add_sorder_staff` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_kacha_sorder_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sordar` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_sorder_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_kacha_sordar` SET "
               . "seller_name = '$data[seller_name]',"
               . "contact_num = '$data[contact_num]',"
               . "address = '$data[address]',"
               . "father_name = '$data[father_name]',"
               . "mother_name = '$data[mother_name]',"
               . "nid_no = '$data[nid_no]',"
               . "bank_acc_no = '$data[bank_acc_no]',"
               . "bank_name = '$data[bank_name]',"
               . "branch_name = '$data[branch_name]'"
               
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Kacha Sorder Info Updated successfully";
            header('Location: kacha_sorder.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_sorder_balance($company_id,$paid) {
        $db_connect = $this->__construct();
        $amountt = $this->getOneCol('previousDues','tbl_kacha_sordar','id',$company_id);
        $amnt = $amountt + $paid ;
        $sql = "UPDATE `tbl_kacha_sordar` SET previousDues = '$amnt' WHERE id = '$company_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Kacha Sorder Info Save Successfully";
            return $message;
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function save_purchase_payment_info_kacha_sorder($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_kacha_sordar_payment` "
                . "(customer_name,"
                . "branch_id,"
                . "address,"
                . "contact_number,"
                . "totalDues,"
                . "date,"
                . "season,"
                . "payment_method,"
                . "paid,"
                . "currDues,"
                . "bankName,"
                . "cheque_num,"
                . "cheque_app_date,user_id) "
                . "VALUES "
                . "('$data[customer_name]',"
                . "'$_SESSION[branch_id]',"
                . "'$data[address]',"
                . "'$data[contact_number]',"
                . "'$data[totalDues]',"
                . "'$date',"
                 . "'$data[season]',"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$data[currDues]',"
                . "'$data[bankName]',"
                . "'$data[cheque_num]',"
                . "'$data[cheque_app_date]','$_SESSION[user_id]')";


        if (mysqli_query($db_connect, $sql)) {
            // $soil = $data['paid'];
            // $nm = $this->getOneCol('name','tbl_kacha_sorder','id',$data['customer_name']); 
            // $msg = "Dear sir, $nm (Kacha Sorder) payment $soil"."tk need your approval.";
            // $this->sendSMS('Hairlife', $msg, "8801935222000");
           $update = $this->update_kacha_sorder_balance($data['customer_name'],$data['paid']);
           if($update){
            $message = "Payment Info Update Successfully";
            return $message;
        }else{
            $message = "Payment Info Update Something Wrong";
            return $message;
        }
            
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_kacha_sorderpay_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sordar_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_kacha_payment_info($id) {
        $db_connect = $this->__construct();  
        $sup = $this->getOneCol('customer_name','tbl_kacha_sordar_payment','id',$id);
        $paid = $this->getOneCol('paid','tbl_kacha_sordar_payment','id',$id);
        $pd = $this->getOneCol('previousDues','tbl_kacha_sordar','id',$sup);
        $fd = $pd - $paid;        
        $sql = "UPDATE `tbl_kacha_sordar` SET previousDues = '$fd' WHERE id = '$sup'";
            if (mysqli_query($db_connect, $sql)) {
                $sql1 = "DELETE FROM  `tbl_kacha_sordar_payment` WHERE id='$id'";
                 if (mysqli_query($db_connect, $sql1)) {
                    $_SESSION['message'] = "Delete Successfully";
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                } else {
                    die('Query Problem' . mysqli_error($db_connect));
                }
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }      
    }
    public function show_kacha_sorder_payment($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sordar_payment` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_sorder_payment_info($data) {
        $db_connect = $this->__construct();
        $old = $this->getOneCol('paid','tbl_kacha_sordar_payment','id',$data['id']);
        $paid = $data['paid'] - $old;
        $date = date('Y-m-d', strtotime($_POST['date']));
        $sql = "UPDATE `tbl_kacha_sordar_payment` SET "
               . "customer_name = '$data[customer_name]',"
               . "date = '$date',"
               . "address = '$data[address]',"
               . "contact_number = '$data[contact_number]',"
               . "season = '$data[season]',"
               . "paid = '$data[paid]',"
               . "payment_method = '$data[payment_method]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {

             $update = $this->update_kacha_sorder_balance($data['customer_name'],$paid);
           if($update){
            $_SESSION['message'] = "Kacha Sorder Payment Info Updated successfully";
            header('Location: kacha_sorder_payment_list.php');
        }else{
            $message = "Payment Info Update Something Wrong";
            return $message;
        }
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_sorder_balance_payment($company_id,$paid) {
        $db_connect = $this->__construct();
        $amountt = $this->getOneCol('previousDues','tbl_kacha_sordar','id',$company_id);
        $paid1 = $this->getOneCol('paid','tbl_kacha_sordar_payment','customer_name',$company_id);
        $pdues = $amountt - $paid ;
         $amnt = $pdues + $paid ;

        $sql = "UPDATE `tbl_kacha_sordar` SET previousDues = '$amnt' WHERE id = '$company_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Kacha Sorder Info Save Successfully";
            return $message;
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_approved_st($id){
        $db_connect = $this->__construct();
        
        $sql = "UPDATE `tbl_kacha_sordar_payment` SET app_st = 1  WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Approved";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }  
    }
    public function payment_popup_sorder($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sordar_payment` WHERE deletion_status= 0 AND id = '$id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function payment_popup_sajano_sorder($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sajano_sorder_payment` WHERE id = '$id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }   
    public function select_all_kacha_sorderpay_info_app($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sordar_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 AND app_st = 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    //emran 

    ///sohan
    public function add_soil_seller($data) {
    $db_connect = $this->__construct();

    $sql = "INSERT INTO `tbl_soil_seller`(name,mobile,address,previousDues,field_qty,area_location,opening_balance,user_id)VALUES('$data[name]','$data[mobile]','$data[address]','$data[opening_balance]','$data[field_qty]','$data[area_location]','$data[opening_balance]','$_SESSION[user_id]')";
    if (mysqli_query($db_connect, $sql)) {
       
        return "Soil Seller added Successfully";
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
  } 

public function select_all_soil_seller() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_seller` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function save_soil_purchased_info_for_approval($data){
   $db_connect = $this->__construct();
   $date = date('Y-m-d',strtotime($data['date']));
    $sql = "INSERT INTO tbl_soil_purchase(seller_id,qty,soil_price,paid,date,approved_status,user_id,description,soil_rate,season)VALUES('$data[seller_id]','$data[qty]','$data[soil_price]','$data[paid]','$date',1,'$_SESSION[user_id]','$data[description]','$data[soil_rate]','$data[season]')";
        if (mysqli_query($db_connect, $sql)) { 
        $prev_stock = $this->getOneCol('stock','tbl_soil_stock','id',1);
        $new_stock = $prev_stock + $data['qty'];
        $sql2 = "UPDATE tbl_soil_stock SET stock = '$new_stock' WHERE id = 1";
        if(mysqli_query($db_connect, $sql2))
        {
            $curr_due = $this->getOneCol('previousDues','tbl_soil_seller','id',$data['seller_id']);
            $new_due = $curr_due + $data['soil_price'] - $data['paid'];
            $sql3 = "UPDATE tbl_soil_seller SET previousDues = '$new_due' WHERE id = '$data[seller_id]' ";
            if(mysqli_query($db_connect,$sql3))
            {
                return $message = "Soil Purchased Successfully!";
            } 
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
}

public function select_all_purchased_info_for_approval() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_order_info_for_approval() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_order_invoice` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_sale_info_for_approval() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_sale_info_for_processing() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` ORDER by id DESC";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_purchased_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase`  WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_soil_purchased_info_for_approval($data){
   $db_connect = $this->__construct();
   $date = date('Y-m-d',strtotime($data['date']));

    $sql = "UPDATE tbl_soil_purchase SET seller_id = '$data[seller_id]', qty = '$data[qty]', soil_price = '$data[soil_price]', paid = '$data[paid]', date = '$date' WHERE id = '$data[id]'";
        if (mysqli_query($db_connect, $sql)) { 
        $curr_stock = $this->getOneCol('stock','tbl_soil_stock','id',1);
        $prev_stock = $data['prev_qty'];
        $new_stock = ($curr_stock - $prev_stock) + $data['qty'];
        $sql2 = "UPDATE tbl_soil_stock SET stock = '$new_stock' WHERE id = 1";
        if(mysqli_query($db_connect, $sql2))
        {
            if($data['seller_id'] == $data['prev_seller_id'])
            {
                $curr_due = $this->getOneCol('previousDues','tbl_soil_seller','id',$data['seller_id']);
                $new_due = ($curr_due + $data['prev_paid'] - $data['prev_soil_price']) + $data['soil_price'] - $data['paid'];
                $sql3 = "UPDATE tbl_soil_seller SET previousDues = '$new_due' WHERE id = $data[seller_id] ";
                if(mysqli_query($db_connect,$sql3))
                {
                    $_SESSION['message'] = "Soil Purchase Updated For Approval!";
                    header("location: manage_soil_purchase.php");
                }
            }
            else
            {
                $curr_due_prev_s = $this->getOneCol('previousDues','tbl_soil_seller','id',$data['prev_seller_id']);
                $new_due_prev_s = $curr_due_prev_s + $data['prev_paid'] - $data['prev_soil_price'];
                $sql3 = "UPDATE tbl_soil_seller SET previousDues = '$new_due_prev_s' WHERE id = $data[prev_seller_id] ";
                if(mysqli_query($db_connect,$sql3))
                {
                    $curr_due_new_s = $this->getOneCol('previousDues','tbl_soil_seller','id',$data['seller_id']);
                    $new_due_new_s = $curr_due_new_s + $data['soil_price'] - $data['paid'];
                    $sql4 = "UPDATE tbl_soil_seller SET previousDues = '$new_due_new_s' WHERE id = $data[seller_id] ";
                    if(mysqli_query($db_connect,$sql4))
                    {
                        $_SESSION['message'] = "Soil Purchase Updated For Approval!";
                        header("location: manage_soil_purchase.php");
                    }
                }
            }
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
}

public function update_soil_seller_due($id) {
        $db_connect = $this->__construct();

        $seller_id = $this->getOneCol('seller_id','tbl_soil_purchase','id',$id);
        $paid = $this->getOneCol('paid','tbl_soil_purchase','id',$id);
        $curr_due = $this->getOneCol('previousDues','tbl_soil_seller','id',$seller_id);
        $due = $curr_due - $paid;
        $sql = "UPDATE `tbl_soil_seller` SET previousDues = '$due' WHERE id = '$seller_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $sql2 ="UPDATE tbl_soil_purchase SET approved_status = 1 WHERE id = '$id' ";
            if(mysqli_query($db_connect,$sql2))
            {
                $message = "Approved Successfully";
                return $message;
            }
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

    public function delete_soil_seller_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_soil_seller` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

    public function select_soil_seller_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_seller`  WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function update_soil_soil_seller_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_soil_seller` SET name = '$data[name]', mobile = '$data[mobile]', address = '$data[address]', opening_balance = '$data[opening_balance]', previousDues = '$data[opening_balance]' where id = '$data[id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Soil Seller Updated Successfully";
            header("location:matir_malik.php");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

    public function save_soil_seller_payment_info_for_approval($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_soil_purchase_payment` "
                . "(seller_id,"
                . "date,"
                . "season,"
                . "payment_method,"
                . "paid,"
                . "user_id,"
                . "approved_status) "
                . "VALUES "
                . "('$data[seller_id]',"
                . "'$date',"
                . "'$data[season]'"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$_SESSION[user_id]',"
                . "'1')";


        if (mysqli_query($db_connect, $sql)) {
            $curr_due = $this->getOneCol('previousDues','tbl_soil_seller','id',$data['seller_id']);
            $new_due = $curr_due - $data['paid'];
            $sql3 = "UPDATE tbl_soil_seller SET previousDues = '$new_due' WHERE id = '$data[seller_id]' ";
            if (mysqli_query($db_connect, $sql3)) {
                $message = "Payment Successfull For Soil Seller!";
                return $message;   
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
            
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_soil_seller_payment_info_for_approval() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase_payment` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_soil_seller_payment_info_for_approval_by_id($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase_payment` WHERE id = '$id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_soil_seller_payment_info_for_approval($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d',strtotime($data['date']));
        $sql = "UPDATE `tbl_soil_purchase_payment` SET seller_id = '$data[seller_id]', date = '$date', payment_method = '$data[payment_method]', paid = '$data[paid]' where id = '$data[id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Payment Updated Successfully For Approval!";
            header("location:soil_seller_payment_list.php");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function update_soil_seller_due_after_payment($id) {
        $db_connect = $this->__construct();

        $seller_id = $this->getOneCol('seller_id','tbl_soil_purchase_payment','id',$id);
        $paid = $this->getOneCol('paid','tbl_soil_purchase_payment','id',$id);
        $curr_due = $this->getOneCol('previousDues','tbl_soil_seller','id',$seller_id);
        $due = $curr_due - $paid;
        $sql = "UPDATE `tbl_soil_seller` SET previousDues = '$due' WHERE id = '$seller_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $sql2 ="UPDATE tbl_soil_purchase_payment SET approved_status = 1 WHERE id = '$id' ";
            if(mysqli_query($db_connect,$sql2))
            {
                $message = "Approved Successfully";
                return $message;
            }
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_all_approved_soil_purchased_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase` WHERE approved_status = 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_approved_soil_purchased_info_by_seller($seller_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase` WHERE approved_status = 1 AND seller_id = '$seller_id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_approved_soil_seller_payment_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase_payment` WHERE approved_status = 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_approved_soil_seller_payment_info_by_seller($seller_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_purchase_payment` WHERE approved_status = 1 AND seller_id = '$seller_id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function select_all_soil_stock() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_soil_stock` WHERE deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function save_kacha_it_stock_info($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
        $sql = "INSERT INTO tbl_kacha_it_stock_info(qty,date,user_id,season,jam_number,jam_type)VALUES('$data[qty]','$date','$_SESSION[user_id]','$data[season]','$data[jamno]','$data[jamtype]')";

        if (mysqli_query($db_connect, $sql)) {
            $prev_stock = $this->getOneCol('stock','tbl_kacha_it_stock','id',1);
            if(empty( $prev_stock )){
                 $prev_stock = 0;
            }
            $new_stock = $prev_stock + $data['qty'];

            $sql2 = "UPDATE tbl_kacha_it_stock SET stock = '$new_stock' WHERE id = 1";

            if(mysqli_query($db_connect, $sql2))
            {
                return $message = "Kacha It Saved Successfully!";
            }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
}

    public function select_all_kacha_it_stock_info() {

            $db_connect = $this->__construct();

            $sql = "SELECT * FROM `tbl_kacha_it_stock_info` order by id desc";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }

    public function delete_kacha_it_stock_info($id) {

            $db_connect = $this->__construct();

            $prev_stock = $this->getOneCol('stock','tbl_kacha_it_stock','id',1);
            $qty = $this->getOneCol('qty','tbl_kacha_it_stock_info','id',$id);
            $new_stock = $prev_stock - $qty;

            $sql = "UPDATE `tbl_kacha_it_stock` SET stock = '$new_stock' WHERE id = 1";

            if (mysqli_query($db_connect, $sql)) {
                $sql2 = "DELETE FROM tbl_kacha_it_stock_info WHERE id = '$id' ";
                if(mysqli_query($db_connect,$sql2))
                {
                    $message = "Deleted Successfully";
                    return $message;
                }
                
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }
        }

    public function select_all_kacha_it_stock_info_by_id($id) {

            $db_connect = $this->__construct();

            $sql = "SELECT * FROM `tbl_kacha_it_stock_info` WHERE id = '$id' ";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }

    public function update_kacha_it_stock_info($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
        $sql = "UPDATE tbl_kacha_it_stock_info SET qty = '$data[qty]',season = '$data[season]', date = '$date' WHERE id = '$data[id]' ";

        if (mysqli_query($db_connect, $sql)) {

        $curr_stock = $this->getOneCol('stock','tbl_kacha_it_stock','id',1);
        $new_stock = ($curr_stock - $data['prev_qty']) + $data['qty'];
        $sql2 = "UPDATE tbl_kacha_it_stock SET stock = '$new_stock' WHERE id = 1";

        if(mysqli_query($db_connect, $sql2))
        {
            $_SESSION['message'] = "Kacha It Stock Updated Successfully!";
            header("location: manage_kacha_it_stock.php");
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

    public function save_chapta_to_chula_transfer_info($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
        $sql = "INSERT INTO tbl_chapta_to_chula_transfer(qty,season,date,user_id)VALUES('$data[qty]','$data[season]','$date','$_SESSION[user_id]')";

        if (mysqli_query($db_connect, $sql)) {

        $prev_stock = $this->getOneCol('stock','tbl_kacha_it_stock','id',1);
        $new_stock = $prev_stock - ($data['qty']);
        $sql2 = "UPDATE tbl_kacha_it_stock SET stock = '$new_stock' WHERE id = 1";

        if(mysqli_query($db_connect, $sql2))
        {
            return $message = "Transfered Successfully!";
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

    public function select_all_chapta_to_chula_transfer_info() {

            $db_connect = $this->__construct();

            $sql = "SELECT * FROM `tbl_chapta_to_chula_transfer` order by id desc";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }


    public function delete_chapta_to_chula_transfer_info($id) {

            $db_connect = $this->__construct();

            $prev_stock = $this->getOneCol('stock','tbl_kacha_it_stock','id',1);
            $qty = $this->getOneCol('qty','tbl_chapta_to_chula_transfer','id',$id);
            $damage = $this->getOneCol('damage','tbl_chapta_to_chula_transfer','id',$id);
            $new_stock = $prev_stock + $qty + $damage;

            $sql = "UPDATE `tbl_kacha_it_stock` SET stock = '$new_stock' WHERE id = 1";

            if (mysqli_query($db_connect, $sql)) {
                $sql2 = "DELETE FROM tbl_chapta_to_chula_transfer WHERE id = '$id' ";
                if(mysqli_query($db_connect,$sql2))
                {
                   $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
                }
                
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }
        }


    public function select_all_chapta_to_chula_transfer_by_id($id) {

            $db_connect = $this->__construct();

            $sql = "SELECT * FROM `tbl_chapta_to_chula_transfer` WHERE id = '$id' ";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }

    public function update_chapta_to_chula_transfer_info($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
        $sql = "UPDATE tbl_chapta_to_chula_transfer SET qty = '$data[qty]',  season = '$data[season]', date = '$date' WHERE id = '$data[id]' ";

        if (mysqli_query($db_connect, $sql)) {

        $curr_stock = $this->getOneCol('stock','tbl_kacha_it_stock','id',1);
        $new_stock = ($curr_stock + $data['prev_qty']) - ($data['qty']);
        $sql2 = "UPDATE tbl_kacha_it_stock SET stock = '$new_stock' WHERE id = 1";

        if(mysqli_query($db_connect, $sql2))
        {
            $_SESSION['message'] = "Transfer Updated Successfully!";
            header("location: manage_chapta_to_chula_transfer.php");
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_distinct_season_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT DISTINCT season FROM `tbl_chapta_to_chula_transfer`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_distinct_season_info5() {

        $db_connect = $this->__construct();

        $sql = "SELECT DISTINCT season FROM `tbl_paka_it_stock_info`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_chapta_to_chula_transfer_info_by_season($season) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_chapta_to_chula_transfer` WHERE season = '$season' order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_kacha_it_stock() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_it_stock` WHERE deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function select_all_koyla_seller() {
    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_koyla_seller` ORDER BY id DESC ";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function add_koyla_seller($data) {
    $db_connect = $this->__construct();

    $sql = "INSERT INTO `tbl_koyla_seller`(name,mobile,address,previousDues,opening_balance,user_id)VALUES('$data[name]','$data[mobile]','$data[address]','$data[opening_balance]','$data[opening_balance]','$_SESSION[user_id]')";
    if (mysqli_query($db_connect, $sql)) {
       
        return "Seller Added Successfully";
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
  }

public function delete_koyla_seller_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_koyla_seller` WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Deleted Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_koyla_seller_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_seller`  WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_koyla_seller_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_koyla_seller` SET name = '$data[name]', mobile = '$data[mobile]', address = '$data[address]', opening_balance = '$data[opening_balance]', previousDues = '$data[opening_balance]' where id = '$data[id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Koyla Seller Updated Successfully";
            header("location:koyla_seller.php");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function save_koyla_purchased_info_for_approval($data){
   $db_connect = $this->__construct();
   $date = date('Y-m-d',strtotime($data['date']));
    $sql = "INSERT INTO tbl_koyla_purchase(seller_id,qty,price_ton,koyla_price,truck,paid,date,approved_status,user_id,season)VALUES('$data[seller_id]','$data[qty]','$data[price_ton]','$data[koyla_price]','$data[truck]','$data[paid]','$date',1,'$_SESSION[user_id]','$data[season]')";
        if (mysqli_query($db_connect, $sql)) { 
        $prev_stock = $this->getOneCol('stock','tbl_koyla_stock','id',1);
        $new_stock = $prev_stock + $data['qty'];
        $sql2 = "UPDATE tbl_koyla_stock SET stock = '$new_stock' WHERE id = 1";
        if(mysqli_query($db_connect, $sql2))
        {
            $curr_due = $this->getOneCol('previousDues','tbl_koyla_seller','id',$data['seller_id']);
            $new_due = $curr_due + $data['koyla_price'] - $data['paid'];
            $sql3 = "UPDATE tbl_koyla_seller SET previousDues = '$new_due' WHERE id = '$data[seller_id]' ";
            if(mysqli_query($db_connect,$sql3))
            {
                
                return $message = "Koyla Purchased Successfully!";
            } 
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
}

public function select_all_koyla_purchased_info_for_approval() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_koyla_seller_due($id) {
        $db_connect = $this->__construct();

        $seller_id = $this->getOneCol('seller_id','tbl_koyla_purchase','id',$id);
        $paid = $this->getOneCol('paid','tbl_koyla_purchase','id',$id);
        $curr_due = $this->getOneCol('previousDues','tbl_koyla_seller','id',$seller_id);
        $due = $curr_due - $paid;
        $sql = "UPDATE `tbl_koyla_seller` SET previousDues = '$due' WHERE id = '$seller_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $sql2 ="UPDATE tbl_koyla_purchase SET approved_status = 1 WHERE id = '$id' ";
            if(mysqli_query($db_connect,$sql2))
            {
                $message = "Approved Successfully";
                return $message;
            }
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_koyla_purchased_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase`  WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_koyla_purchased_info_for_approval($data){
   $db_connect = $this->__construct();
   $date = date('Y-m-d',strtotime($data['date']));

    $sql = "UPDATE tbl_koyla_purchase SET seller_id = '$data[seller_id]', qty = '$data[qty]', koyla_price = '$data[koyla_price]', truck = '$data[truck]', paid = '$data[paid]', date = '$date' WHERE id = '$data[id]'";
        if (mysqli_query($db_connect, $sql)) { 
        $curr_stock = $this->getOneCol('stock','tbl_koyla_stock','id',1);
        $prev_stock = $data['prev_qty'];
        $new_stock = ($curr_stock - $prev_stock) + $data['qty'];
        $sql2 = "UPDATE tbl_koyla_stock SET stock = '$new_stock' WHERE id = 1";
        if(mysqli_query($db_connect, $sql2))
        {
            if($data['seller_id'] == $data['prev_seller_id'])
            {
                $curr_due = $this->getOneCol('previousDues','tbl_koyla_seller','id',$data['seller_id']);
                $new_due = ($curr_due - $data['prev_koyla_price'] - $data['paid']) + $data['koyla_price'] + $data['prev_paid'];
                $sql3 = "UPDATE tbl_koyla_seller SET previousDues = '$new_due' WHERE id = $data[seller_id] ";
                if(mysqli_query($db_connect,$sql3))
                {
                    $_SESSION['message'] = "Koyla Purchase Updated For Approval!";
                    header("location: manage_koyla_purchase.php");
                }
            }
            else
            {
                $curr_due_prev_s = $this->getOneCol('previousDues','tbl_koyla_seller','id',$data['prev_seller_id']);
                $new_due_prev_s = $curr_due_prev_s - $data['prev_koyla_price'] + $data['prev_paid'];
                $sql3 = "UPDATE tbl_koyla_seller SET previousDues = '$new_due_prev_s' WHERE id = $data[prev_seller_id] ";
                if(mysqli_query($db_connect,$sql3))
                {
                    $curr_due_new_s = $this->getOneCol('previousDues','tbl_koyla_seller','id',$data['seller_id']);
                    $new_due_new_s = $curr_due_new_s + $data['koyla_price'] -$data['paid'];
                    $sql4 = "UPDATE tbl_koyla_seller SET previousDues = '$new_due_new_s' WHERE id = $data[seller_id] ";
                    if(mysqli_query($db_connect,$sql4))
                    {
                        $_SESSION['message'] = "Koyla Purchase Updated For Approval!";
                        header("location: manage_koyla_purchase.php");
                    }
                }
            }
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
}

public function save_koyla_seller_payment_info_for_approval($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_koyla_purchase_payment` "
                . "(seller_id,"
                . "date,"
                . "season,"
                . "payment_method,"
                . "paid,"
                . "user_id,"
                . "approved_status) "
                . "VALUES "
                . "('$data[seller_id]',"
                . "'$date',"
                . "'$data[season]'"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$_SESSION[user_id]',"
                . "'1')";
        if (mysqli_query($db_connect, $sql)) {
            $id = mysqli_insert_id($db_connect);
            $update = $this->update_koyla_seller_due_after_payment($id);
            if($update){
                $message = "Payment Successfull!";
                return $message;
            }else{
               $message = "Payment Something Wrong!";
                return $message; 
            }
            
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_koyla_seller_payment_info_for_approval() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase_payment` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_koyla_seller_due_after_payment($id) {
        $db_connect = $this->__construct();

        $seller_id = $this->getOneCol('seller_id','tbl_koyla_purchase_payment','id',$id);
        $paid = $this->getOneCol('paid','tbl_koyla_purchase_payment','id',$id);
        $curr_due = $this->getOneCol('previousDues','tbl_koyla_seller','id',$seller_id);
        $due = $curr_due - $paid;
        $sql = "UPDATE `tbl_koyla_seller` SET previousDues = '$due' WHERE id = '$seller_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $sql2 ="UPDATE tbl_koyla_purchase_payment SET approved_status = 1 WHERE id = '$id' ";
            if(mysqli_query($db_connect,$sql2))
            {
                $message = "Approved Successfully";
                return $message;
            }
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_all_koyla_seller_payment_info_for_approval_by_id($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase_payment` WHERE id = '$id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_koyla_seller_payment_info_for_approval($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d',strtotime($data['date']));
        $sql = "UPDATE `tbl_koyla_purchase_payment` SET seller_id = '$data[seller_id]', date = '$date', payment_method = '$data[payment_method]', paid = '$data[paid]' where id = '$data[id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
                $curr_due = $this->getOneCol('previousDues','tbl_koyla_seller','id',$data['seller_id']);
                $due = $curr_due + $data['prev_paid'] - $data['paid'];
                $sql1 = "UPDATE `tbl_koyla_seller` SET previousDues = '$due' WHERE id = '$data[seller_id]'";
                if(mysqli_query($db_connect, $sql1)) {
                    $_SESSION['message'] = "Payment Updated Successfully!";
                    header("location:koyla_seller_payment_list.php");
                }else {
                    die('Query Problem' . mysqli_error($db_connect));
                }      
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function save_koyla_vanga_estimate_info($data){
   $db_connect = $this->__construct();
   $date = date('Y-m-d',strtotime($data['date']));
    $sql = "INSERT INTO tbl_koyla_vanga_estimate_info(qty,date,user_id)VALUES('$data[qty]','$date','$_SESSION[user_id]')";
    if (mysqli_query($db_connect, $sql)) { 
        return $message = "Koyla Vanga Estimate Info Saved Successfully!";
    }           
     else {
        die('Query Problem' . mysqli_error($db_connect));
    }
}

public function select_all_koyla_vanga_estimate_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_vanga_estimate_info` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function delete_koyla_vanga_estimate_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_koyla_vanga_estimate_info` WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Deleted Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_koyla_vanga_estimate_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_vanga_estimate_info`  WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_koyla_vanga_estimate_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d',strtotime($data['date']));
        $sql = "UPDATE `tbl_koyla_vanga_estimate_info` SET qty = '$data[qty]', date = '$date' where id = '$data[id]'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Koyla Vanga Estimate Info Updated Successfully";
            header("location:manage_koyla_vanga_estimate.php");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function save_vanga_koyla_to_chula_and_stock_info($data){
   $db_connect = $this->__construct();
   $date = date('Y-m-d',strtotime($data['date']));
    $sql = "INSERT INTO tbl_vanga_koyla_to_chula_info(date,send_to_chula,season,user_id)VALUES('$date','$data[send_to_chula_qty]','$data[season]','$_SESSION[user_id]')";
    if (mysqli_query($db_connect, $sql)) { 
        // $curr_stock = $this->getOneCol('stock','tbl_vanga_koyla_stock','id',1);
        // $new_stock = $curr_stock + ($data['vanga_koyla_qty'] - $data['send_to_chula_qty']);
        // $sql2 = "UPDATE tbl_vanga_koyla_stock SET stock = '$new_stock' WHERE id = 1 ";
        // if(mysqli_query($db_connect,$sql2))
        // {
            $koyla_curr_stock = $this->getOneCol('stock','tbl_koyla_stock','id',1);
            $today_qty = $data['send_to_chula_qty']/1000;
            $koyla_new_stock = $koyla_curr_stock - $today_qty;
            $sql3 = "UPDATE tbl_koyla_stock SET stock = '$koyla_new_stock' WHERE id = 1 ";
            if(mysqli_query($db_connect,$sql3))
            {
                return $message = "Transfered Successfully!";
            }
        // }
    }           
     else {
        die('Query Problem' . mysqli_error($db_connect));
    }
}

public function select_all_vanga_koyla_to_chula_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vanga_koyla_to_chula_info` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function delete_vanga_koyla_to_chula_info($id) {

        $db_connect = $this->__construct();
        $send_to_chula = $this->getOneCol('send_to_chula','tbl_vanga_koyla_to_chula_info','id',$id);
        $sql = "DELETE FROM `tbl_vanga_koyla_to_chula_info` WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $curr_stock_vanga_koyla = $this->getOneCol('stock','tbl_vanga_koyla_stock','id',1);
            $new_stock = $curr_stock_vanga_koyla + $send_to_chula;
            $sql2 = "UPDATE tbl_vanga_koyla_stock SET stock = '$new_stock' WHERE id = 1 ";
            if(mysqli_query($db_connect,$sql2))
            {
                $message = "Deleted Successfully";
                return $message;
            }
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_all_vanga_koyla_to_chula_info_by_id($id){
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vanga_koyla_to_chula_info`  WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function update_vanga_koyla_to_chula_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d',strtotime($data['date']));
        $sql = "UPDATE `tbl_vanga_koyla_to_chula_info` SET date = '$date',  send_to_chula = '$data[send_to_chula_qty]', season = '$data[season]' where id = '$data[id]'";
        if (mysqli_query($db_connect, $sql)) {
            // $curr_stock_vanga_koyla = $this->getOneCol('stock','tbl_vanga_koyla_stock','id',1);
            // $new_stock_vanga_koyla = ($curr_stock_vanga_koyla - ($data['prev_vanga_koyla_qty'] - $data['prev_send_to_chula_qty'])) + ($data['vanga_koyla_qty'] - $data['send_to_chula_qty']);
            // $sql2 = "UPDATE tbl_vanga_koyla_stock SET stock = '$new_stock_vanga_koyla' WHERE id = 1 ";
            // if(mysqli_query($db_connect,$sql2))
            // {
                $curr_stock_koyla = $this->getOneCol('stock','tbl_koyla_stock','id',1);
                $new_stock_koyla = ($curr_stock_koyla + ($data['prev_send_to_chula_qty']/1000)) - ($data['send_to_chula_qty']/1000);
                $sql3 = "UPDATE tbl_koyla_stock SET stock = '$new_stock_koyla' WHERE id = 1 ";
                if(mysqli_query($db_connect,$sql3))
                {
                    $_SESSION['message'] = "Updated Successfully";
                    header("location:manage_vanga_koyla_to_chula.php");
                }
           // }
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_all_approved_koyla_purchased_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase` WHERE approved_status = 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_approved_koyla_purchased_info_by_seller($seller_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase` WHERE approved_status = 1 AND seller_id = '$seller_id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


public function select_all_approved_koyla_seller_payment_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase_payment` WHERE approved_status = 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_approved_koyla_seller_payment_info_by_seller($seller_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_purchase_payment` WHERE approved_status = 1 AND seller_id = '$seller_id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_koyla_vanga_estimate_info_by_user($user) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_vanga_estimate_info` WHERE user_id = '$user' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_active_user() {
    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_user` WHERE deletion_status = 0 ORDER BY user_id DESC ";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function select_all_koyla_stock() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_koyla_stock` WHERE deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_vanga_koyla_stock() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vanga_koyla_stock` WHERE deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


public function select_all_distinct_season_info_vkcr() {

        $db_connect = $this->__construct();

        $sql = "SELECT DISTINCT season FROM `tbl_vanga_koyla_to_chula_info`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

public function select_all_vanga_koyla_to_chula_info_by_season($season) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vanga_koyla_to_chula_info` WHERE season = '$season' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_fireman_sorder() {
    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_fireman_sorder` ORDER BY id DESC ";
    if (mysqli_query($db_connect, $sql)) {
        $query_result = mysqli_query($db_connect, $sql);
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function add_fireman_sorder($data) {
    $db_connect = $this->__construct();

    $sql = "INSERT INTO `tbl_fireman_sorder`(seller_name,contact_num,address,father_name,mother_name,nid_no,bank_acc_no,bank_name,branch_name,previousDues,opening_balance,user_id)VALUES('$data[seller_name]','$data[contact_num]','$data[address]','$data[father_name]','$data[mother_name]','$data[nid_no]','$data[bank_acc_no]','$data[bank_name]','$data[branch_name]','$data[opening_balance]','$data[opening_balance]','$_SESSION[user_id]')";
    if (mysqli_query($db_connect, $sql)) {
       
        return "Fireman Sorder added Successfully";
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function delete_fireman_sorder_info($id) {

    $db_connect = $this->__construct();
    $sql = "DELETE FROM `tbl_fireman_sorder` WHERE id = '$id'";
    if (mysqli_query($db_connect, $sql)) {
         $_SESSION['message'] = "Deleted Successfully";
         header("Location: {$_SERVER['HTTP_REFERER']}");
    } else {
        die('Query Problem' . mysqli_error($db_connect));
    }
}

public function show_fireman_sorder_info($id) {
    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_fireman_sorder` WHERE id='$id' ";
    if (mysqli_query($db_connect, $sql)) {
        $result = mysqli_query($db_connect, $sql);
        return $result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function update_fireman_sorder_info($data) {
    $db_connect = $this->__construct();

    $sql = "UPDATE `tbl_fireman_sorder` SET "
           . "seller_name = '$data[seller_name]',"
           . "contact_num = '$data[contact_num]',"
           . "address = '$data[address]',"
           . "father_name = '$data[father_name]',"
           . "mother_name = '$data[mother_name]',"
           . "nid_no = '$data[nid_no]',"
           . "bank_acc_no = '$data[bank_acc_no]',"
           . "bank_name = '$data[bank_name]',"
           . "branch_name = '$data[branch_name]'"
           
           . " WHERE id='$data[id]'";

    if (mysqli_query($db_connect, $sql)) {
        $_SESSION['message'] = "Fireman Sorder Info Updated successfully";
        header('Location: fireman_sorder.php');
    } else {
        die('Query Problem' . mysqli_error($db_connect));
    }
}

public function save_purchase_payment_info_fireman_sorder($data) {

    $db_connect = $this->__construct();

    $date = date('Y-m-d', strtotime($_POST['date']));

    $sql = "INSERT INTO `tbl_fireman_sorder_payment` "
            . "(customer_name,"
            . "branch_id,"
            . "address,"
            . "contact_number,"
            . "totalDues,"
            . "date,"
            . "season,"
            . "payment_method,"
            . "paid,"
            . "currDues,"
            . "bankName,"
            . "cheque_num,"
            . "round,"
            . "month,"
            . "cheque_app_date,user_id) "
            . "VALUES "
            . "('$data[customer_name]',"
            . "'$_SESSION[branch_id]',"
            . "'$data[address]',"
            . "'$data[contact_number]',"
            . "'$data[totalDues]',"
            . "'$date',"
             . "'$data[season]',"
            . "'$data[payment_method]',"
            . "'$data[paid]',"
            . "'$data[currDues]',"
            . "'$data[bankName]',"
            . "'$data[cheque_num]',"
            . "'$data[round]',"
            . "'$data[month]',"
            . "'$data[cheque_app_date]','$_SESSION[user_id]')";


    if (mysqli_query($db_connect, $sql)) {
        $nm = $this->getOneCol('name','tbl_fireman_sorder','id',$data['customer_name']); 
            $soil = $data['paid'];
            $msg = "Dear sir, $nm (Fireman Sorder) payment $soil"."tk need your approval.";
            $this->sendSMS('Hairlife', $msg, "8801935222000");
        $message = "Payment Info Update Successfully";
        return $message;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function update_approved_st_fireman_sorder($id){
    $db_connect = $this->__construct();
    
    $sql = "UPDATE `tbl_fireman_sorder_payment` SET app_st = 1  WHERE id='$id'";
    if (mysqli_query($db_connect, $sql)) {
        $message = "Approved";
        return $message;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }  
}

public function update_fireman_sorder_balance($company_id,$paid) {
    $db_connect = $this->__construct();
    $amountt = $this->getOneCol('previousDues','tbl_fireman_sorder','id',$company_id);
    $amnt = $amountt + $paid ;
    $sql = "UPDATE `tbl_fireman_sorder` SET previousDues = '$amnt' WHERE id = '$company_id'";
    $result = mysqli_query($db_connect, $sql);
    if ($result) {
        $message = "Fireman Sorder Balance Updated Successfully";
        return $message;
        
    } else {
        die('Query Problem' . mysqli_error($db_connect));
    }
}

public function select_all_fireman_sorderpay_info($branch_id) {

    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_fireman_sorder_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
    $query_result = mysqli_query($db_connect, $sql);
    if ($query_result) {
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function show_fireman_sorder_payment($id) {
    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_fireman_sorder_payment` WHERE id='$id' ";
    if (mysqli_query($db_connect, $sql)) {
        $result = mysqli_query($db_connect, $sql);
        return $result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function update_fireman_sorder_payment_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d', strtotime($_POST['date']));
        $sql = "UPDATE `tbl_fireman_sorder_payment` SET "
               . "customer_name = '$data[customer_name]',"
               . "date = '$date',"
               . "address = '$data[address]',"
               . "contact_number = '$data[contact_number]',"
               . "season = '$data[season]',"
               . "paid = '$data[paid]',"
               . "payment_method = '$data[payment_method]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Fireman Sorder Payment Info Updated successfully";
            header('Location: fireman_sorder_payment_list.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_all_fireman_sorderpay_info_app($branch_id) {

    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_fireman_sorder_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 AND app_st = 1 ORDER BY id DESC ";
    $query_result = mysqli_query($db_connect, $sql);
    if ($query_result) {
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
}

public function payment_popup_fireman_sorder($id) {

    $db_connect = $this->__construct();

    $sql = "SELECT * FROM `tbl_fireman_sorder_payment` WHERE deletion_status= 0 AND id = '$id' ORDER BY id DESC ";
    $query_result = mysqli_query($db_connect, $sql);
    if ($query_result) {
        return $query_result;
    } else {
        die('Query problem' . mysqli_error($db_connect));
    }
} 
    //sohan
    public function add_kacha_reza_sorder($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_kacha_reza_sorder`(seller_name,contact_num,address,father_name,mother_name,nid_no,bank_acc_no,bank_name,branch_name,previousDues,opening_balance,user_id)VALUES('$data[seller_name]','$data[contact_num]','$data[address]','$data[father_name]','$data[mother_name]','$data[nid_no]','$data[bank_acc_no]','$data[bank_name]','$data[branch_name]','$data[opening_balance]','$data[opening_balance]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Kacha Reza Sorder added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_kacha_reza_sorder() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_reza_sorder` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function show_kacha_reza_sorder_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_reza_sorder` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_reza_sorder_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_kacha_reza_sorder` SET "
               . "seller_name = '$data[seller_name]',"
               . "contact_num = '$data[contact_num]',"
               . "address = '$data[address]',"
               . "father_name = '$data[father_name]',"
               . "mother_name = '$data[mother_name]',"
               . "nid_no = '$data[nid_no]',"
               . "bank_acc_no = '$data[bank_acc_no]',"
               . "bank_name = '$data[bank_name]',"
               . "branch_name = '$data[branch_name]'"
               
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Kacha Sorder Info Updated successfully";
            header('Location: kacha_reza_sorder.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_kacha_reza_sorder_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_kacha_reza_sorder` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function save_purchase_payment_info_kacha_reza_sorder($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_kacha_reza_sorder_payment` "
                . "(customer_name,"
                . "branch_id,"
                . "address,"
                . "contact_number,"
                . "totalDues,"
                . "date,"
                . "season,"
                . "payment_method,"
                . "paid,"
                . "currDues,"
                . "bankName,"
                . "cheque_num,"
                . "cheque_app_date,user_id) "
                . "VALUES "
                . "('$data[customer_name]',"
                . "'$_SESSION[branch_id]',"
                . "'$data[address]',"
                . "'$data[contact_number]',"
                . "'$data[totalDues]',"
                . "'$date',"
                 . "'$data[season]',"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$data[currDues]',"
                . "'$data[bankName]',"
                . "'$data[cheque_num]',"
                . "'$data[cheque_app_date]','$_SESSION[user_id]')";


        if (mysqli_query($db_connect, $sql)) {
           
            $update = $this->update_kacha_reza_sorder_balance($data['customer_name'],$data['paid']);
            if($update){
                $message = "Payment Info Update Successfully";
                return $message;
            }else{
                $message = "Payment Info Update Something Wrong";
                return $message;               
            }
            
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_kacha_reza_sorderpay_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_reza_sorder_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_reza_sorder_balance($company_id,$paid) {
        $db_connect = $this->__construct();
        $amountt = $this->getOneCol('previousDues','tbl_kacha_reza_sorder','id',$company_id);
        $amnt = $amountt + $paid ;
        $sql = "UPDATE `tbl_kacha_reza_sorder` SET previousDues = '$amnt' WHERE id = '$company_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Kacha Reza Sorder Balance Updated Successfully";
            return $message;
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_approved_st_kacha_reza($id){
        $db_connect = $this->__construct();
        
        $sql = "UPDATE `tbl_kacha_reza_sorder_payment` SET app_st = 1  WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Approved";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }  
    }
    public function show_kacha_reza_sorder_payment($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_reza_sorder_payment` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_reza_sorder_payment_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d', strtotime($_POST['date']));
        $old = $this->getOneCol('paid','tbl_kacha_reza_sorder_payment','id',$data['id']);
        $paid = $data['paid'] - $old;
        $sql = "UPDATE `tbl_kacha_reza_sorder_payment` SET "
               . "customer_name = '$data[customer_name]',"
               . "date = '$date',"
               . "address = '$data[address]',"
               . "contact_number = '$data[contact_number]',"
               . "season = '$data[season]',"
               . "paid = '$data[paid]',"
               . "payment_method = '$data[payment_method]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $update = $this->update_kacha_reza_sorder_balance($data['customer_name'],$paid);
            if($update){
                $_SESSION['message'] = "Kacha Reza Sorder Payment Info Updated successfully";
                header('Location: kacha_reza_sorder_payment_list.php');
            }else{
                $message = "Payment Info Update Something Wrong";
                return $message;               
            }
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_kacha_reza_sorderpay_info_app($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_reza_sorder_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 AND app_st = 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function payment_popup_kacha_reza_sorder($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_reza_sorder_payment` WHERE deletion_status= 0 AND id = '$id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function save_chapta_to_chula_sorasori($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
        $sql = "INSERT INTO tbl_chapta_to_chula_sorasori(qty,season,date,user_id)VALUES('$data[qty]','$data[season]','$date','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            return $message = "Transfered Successfully!";         
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_chapta_to_chula_sorasori() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_chapta_to_chula_sorasori` order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_chapta_to_chula_sorasori_season() {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(qty) as qty,season FROM `tbl_chapta_to_chula_sorasori` GROUP BY season";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_kacha_it_stock_season() {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(qty) as qty,season FROM `tbl_kacha_it_stock_info` GROUP BY season";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_kacha_it_stock_to_chula_season() {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(qty) as qty,season FROM `tbl_chapta_to_chula_transfer` GROUP BY season";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
    public function select_all_chapta_to_chula_sorasori_season_search($season) {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(qty) as qty,season FROM `tbl_chapta_to_chula_sorasori` WHERE season = '$season'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_kacha_it_stock_season_search($season) {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(qty) as qty,season FROM `tbl_kacha_it_stock_info` WHERE season = '$season'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_kacha_it_stock_to_chula_season_search($season) {

        $db_connect = $this->__construct();

        $sql = "SELECT SUM(qty) as qty,season FROM `tbl_chapta_to_chula_transfer` WHERE season = '$season'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }   
    public function delete_chapta_to_chula_sorasori($id) {

        $db_connect = $this->__construct();
            $sql2 = "DELETE FROM tbl_chapta_to_chula_transfer WHERE id = '$id' ";
            if(mysqli_query($db_connect,$sql2))
            {
                $_SESSION['message'] = "Delete Successfully";
                header("Location: {$_SERVER['HTTP_REFERER']}");
            }
            
         else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_chapta_to_chula_sorasori($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
        $sql = "UPDATE tbl_chapta_to_chula_sorasori SET qty = '$data[qty]',  season = '$data[season]', date = '$date' WHERE id = '$data[id]' ";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Transfer Updated Successfully!";
            header("location: manage_chapta_to_chula_sorasori.php");
          
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_chapta_to_chula_sorasori_by_id($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_chapta_to_chula_sorasori` WHERE id = '$id' ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_distinct_season_info_2() {

        $db_connect = $this->__construct();

        $sql = "SELECT DISTINCT season FROM `tbl_chapta_to_chula_sorasori`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_distinct_season_info_3() {

        $db_connect = $this->__construct();

        $sql = "SELECT DISTINCT season FROM `tbl_kacha_it_stock_info`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_distinct_season_info_4() {

        $db_connect = $this->__construct();

        $sql = "SELECT DISTINCT season FROM `tbl_chapta_to_chula_transfer`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
    public function select_all_chapta_to_chula_sorasori_by_season($season) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_chapta_to_chula_sorasori` WHERE season = '$season' order by id desc";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_paka_reza_sorder($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_paka_reza_sorder`(seller_name,contact_num,address,father_name,mother_name,nid_no,bank_acc_no,bank_name,branch_name,previousDues,opening_balance,user_id)VALUES('$data[seller_name]','$data[contact_num]','$data[address]','$data[father_name]','$data[mother_name]','$data[nid_no]','$data[bank_acc_no]','$data[bank_name]','$data[branch_name]','$data[opening_balance]','$data[opening_balance]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Paka Reza Sorder added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_paka_reza_sorder() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_paka_reza_sorder` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function show_paka_reza_sorder_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_paka_reza_sorder` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_paka_reza_sorder_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_paka_reza_sorder` SET "
               . "seller_name = '$data[seller_name]',"
               . "contact_num = '$data[contact_num]',"
               . "address = '$data[address]',"
               . "father_name = '$data[father_name]',"
               . "mother_name = '$data[mother_name]',"
               . "nid_no = '$data[nid_no]',"
               . "bank_acc_no = '$data[bank_acc_no]',"
               . "bank_name = '$data[bank_name]',"
               . "branch_name = '$data[branch_name]'"
               
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Paka Sorder Info Updated successfully";
            header('Location: paka_reza_sorder.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function delete_paka_reza_sorder_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_paka_reza_sorder` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    } 
    public function save_purchase_payment_info_paka_reza_sorder($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_paka_reza_sorder_payment` "
                . "(customer_name,"
                . "branch_id,"
                . "address,"
                . "contact_number,"
                . "totalDues,"
                . "date,"
                . "season,"
                . "payment_method,"
                . "paid,"
                . "currDues,"
                . "bankName,"
                . "cheque_num,"
                . "cheque_app_date,user_id) "
                . "VALUES "
                . "('$data[customer_name]',"
                . "'$_SESSION[branch_id]',"
                . "'$data[address]',"
                . "'$data[contact_number]',"
                . "'$data[totalDues]',"
                . "'$date',"
                 . "'$data[season]',"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$data[currDues]',"
                . "'$data[bankName]',"
                . "'$data[cheque_num]',"
                . "'$data[cheque_app_date]','$_SESSION[user_id]')";


        if (mysqli_query($db_connect, $sql)) {
            $update = $this->update_paka_reza_sorder_balance($data['customer_name'],$data['paid']);
            if($update){
                $message = "Payment Info Update Successfully";
                return $message;
            }else{
                $message = "Payment Info Update Something Wrong";
                return $message;
            }
            
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_paka_reza_sorderpay_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_paka_reza_sorder_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_paka_reza_sorder_balance($company_id,$paid) {
        $db_connect = $this->__construct();
        $amountt = $this->getOneCol('previousDues','tbl_paka_reza_sorder','id',$company_id);
        $amnt = $amountt + $paid;
        $sql = "UPDATE `tbl_paka_reza_sorder` SET previousDues = '$amnt' WHERE id = '$company_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Paka Reza Sorder Balance Updated Successfully";
            return $message;
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_approved_st_paka_reza($id){
        $db_connect = $this->__construct();
        
        $sql = "UPDATE `tbl_paka_reza_sorder_payment` SET app_st = 1  WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Approved";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }  
    }
    public function show_paka_reza_sorder_payment($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_paka_reza_sorder_payment` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_paka_reza_sorder_payment_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d', strtotime($_POST['date']));
        $old = $this->getOneCol('paid','tbl_paka_reza_sorder_payment','id',$data['id']);
        $paid =  $data['paid'] - $old;
        $sql = "UPDATE `tbl_paka_reza_sorder_payment` SET "
               . "customer_name = '$data[customer_name]',"
               . "date = '$date',"
               . "address = '$data[address]',"
               . "contact_number = '$data[contact_number]',"
               . "season = '$data[season]',"
               . "paid = '$data[paid]',"
               . "payment_method = '$data[payment_method]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $update = $this->update_paka_reza_sorder_balance($data['customer_name'],$paid);
            if($update){
                $_SESSION['message'] = "Paka Reza Sorder Payment Info Updated successfully";
            header('Location: paka_reza_sorder_payment_list.php');
            }else{
                $message = "Payment Info Update Something Wrong";
                return $message;               
            }
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_paka_reza_sorderpay_info_app($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_paka_reza_sorder_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 AND app_st = 1 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }   
    public function payment_popup_paka_reza_sorder($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_paka_reza_sorder_payment` WHERE deletion_status= 0 AND id = '$id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function save_paka_it_stock_info($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
       $sql = "INSERT INTO tbl_paka_it_stock_info(qty,date,user_id,season,category)VALUES('$data[qty]','$date','$_SESSION[user_id]','$data[season]','$data[category]')";

        if (mysqli_query($db_connect, $sql)) {
            $prev_stock = $this->getOneCol('stock','tbl_paka_it_stock','category',$data['category']);
            if(empty( $prev_stock )){
                 $prev_stock = 0;
            }
            $new_stock = $prev_stock + $data['qty'];

            $sql2 = "UPDATE tbl_paka_it_stock SET stock = '$new_stock' WHERE category = '$data[category]'";

            if(mysqli_query($db_connect, $sql2))
            {
                return $message = "Paka It Saved Successfully!";
            }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
        public function select_all_paka_it_stock_info() {

            $db_connect = $this->__construct();

            $sql = "SELECT * FROM `tbl_paka_it_stock_info` order by id desc";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }

    public function delete_paka_it_stock_info($id) {

            $db_connect = $this->__construct();
            $cat = $this->getOneCol('category','tbl_paka_it_stock_info','id',$id);
            $prev_stock = $this->getOneCol('stock','tbl_paka_it_stock','category',$cat);
            $qty = $this->getOneCol('qty','tbl_paka_it_stock_info','id',$id);
            $new_stock = $prev_stock - $qty;

            $sql = "UPDATE `tbl_paka_it_stock` SET stock = '$new_stock' WHERE category = '$cat'";

            if (mysqli_query($db_connect, $sql)) {
                $sql2 = "DELETE FROM tbl_paka_it_stock_info WHERE id = '$id' ";
                if(mysqli_query($db_connect,$sql2))
                {
                    $_SESSION['message'] = "Delete Successfully";
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                }
                
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }
        }

    public function select_all_paka_it_stock_info_by_id($id) {

            $db_connect = $this->__construct();

            $sql = "SELECT * FROM `tbl_paka_it_stock_info` WHERE id = '$id' ";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }

    public function update_paka_it_stock_info($data){
       $db_connect = $this->__construct();
       $date = date('Y-m-d',strtotime($data['date']));
       $cat = $this->getOneCol('category','tbl_paka_it_stock_info','id',$data['id']);
       $stc = $this->getOneCol('stock','tbl_paka_it_stock','category',$cat);
      $sql = "UPDATE tbl_paka_it_stock_info SET qty = '$data[qty]',season = '$data[season]', date = '$date', category = '$data[category]' WHERE id = '$data[id]' ";

        if (mysqli_query($db_connect, $sql)) {

        $curr_stock = $this->getOneCol('stock','tbl_paka_it_stock','category',$data['category']);
        $new_stock = $stc - $data['prev_qty'];
        $ac_st =  $curr_stock + $data['qty'];
        $sql2 = "UPDATE tbl_paka_it_stock SET stock = '$new_stock' WHERE category = '$cat'";

        if(mysqli_query($db_connect, $sql2))
        {
            $sql3 = "UPDATE tbl_paka_it_stock SET stock = '$ac_st' WHERE category = '$data[category]'";
            if(mysqli_query($db_connect, $sql3)){
            $_SESSION['message'] = "Paka It Stock Updated Successfully!";
            header("location: manage_paka_it_stock.php");
            }else{
                die('Query Problem' . mysqli_error($db_connect));
            }
            
        }           
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_paka_it_stock() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_paka_it_stock` WHERE deletion_status = 0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_all_paka_reza() {

            $db_connect = $this->__construct();

            $sql = "SELECT sum(qty) as qty,category,season FROM `tbl_paka_it_stock_info` Group BY category";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
           public function select_all_paka_reza_search($season) {

            $db_connect = $this->__construct();

            $sql = "SELECT sum(qty) as qty,category,season FROM `tbl_paka_it_stock_info` WHERE season = '$season' Group BY category";
            $query_result = mysqli_query($db_connect, $sql);
            if ($query_result) {
                return $query_result;
            } else {
                die('Query problem' . mysqli_error($db_connect));
            }
        }
    // Dashboard
    public function soil_budget_info() {

        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $dateformat = date('Ymd', $timestamp);
        $sql = "SELECT * FROM tbl_soil_budget WHERE fromformat <= $dateformat AND toformat >= $dateformat";
        if (mysqli_query($db_connect, $sql)) {
            $message = mysqli_query($db_connect, $sql);
            foreach($message as $data);
            return $data;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function koyla_budget_info() {

        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $dateformat = date('Ymd', $timestamp);
        $sql = "SELECT * FROM tbl_koyla_budget WHERE fromformat <= $dateformat AND toformat >= $dateformat";
        if (mysqli_query($db_connect, $sql)) {
            $message = mysqli_query($db_connect, $sql);
            foreach($message as $data);
            return $data;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }    
    public function mess_budget_info() {

        $db_connect = $this->__construct();
        $timezone = 'Asia/Dhaka';
        date_default_timezone_set($timezone);
        $timestamp = time();
        $date = date('Y-m-d', $timestamp);
        $sql = "SELECT * FROM tbl_mess_budget WHERE date = '$date'";
        if (mysqli_query($db_connect, $sql)) {
            $message = mysqli_query($db_connect, $sql);
            foreach($message as $data);
            return $data;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }     
    public function daily_transaction($data) {

        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_account` WHERE date='$data'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return mysqli_num_rows($query_result);
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_credit($data) {

        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE date='$data' AND account_type=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_debit($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE date='$data' AND account_type=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_delivery($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(delivery) as amn FROM `tbl_sales_delivery` WHERE date='$data' AND pc='pc'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_delivery_ft($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(delivery) as amn_ft FROM `tbl_sales_delivery` WHERE date='$data' AND pc='ft'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn_ft'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function today_delivery_pc($data) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_delivery` WHERE date='$data' AND pc='pc'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function today_delivery($data) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_delivery` WHERE date='$data' AND pc='ft'";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function savings_in($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE purpose='Savings' AND account_type=2";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function savings_out($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE purpose='Savings' AND account_type=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_main_debit($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_main_inc` WHERE date='$data' AND account_type=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
      public function daily_debit_payment($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE date='$data' AND account_type=3";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
    
      public function daily_in($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE date='$data' AND account_type=2";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_main_in($data) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_main_inc` WHERE date='$data' AND account_type=2";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function today_invoice() {
        $db_connect = $this->__construct();
        date_default_timezone_set('Asia/Dhaka');
        $date = date('Y-m-d');
        $sql = "SELECT id FROM `tbl_sale_invoice` WHERE date='$date'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_all_in() {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_main_inc` WHERE  account_type=2";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_all_out() {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_main_inc` WHERE  account_type=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function daily_final($data) {

        $db_connect = $this->__construct();
        $sql = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE account_type=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            $debit = $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
        $sql2 = "SELECT SUM(amount) as amn FROM `tbl_account` WHERE account_type=2";
        $query_result = mysqli_query($db_connect, $sql2);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            $credit = $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
        $sql3 = "SELECT SUM(amount) as amn FROM `tbl_opening_cash`";
        $query_result = mysqli_query($db_connect, $sql3);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            $open = $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
        return $open + $credit - $debit;

    }
    public function soil_pur($from,$to){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(soil_price) as amn,SUM(truck) as truck,SUM(veku) as vec FROM `tbl_soil_purchase` WHERE date BETWEEN '$from' AND '$to' AND approved_status=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        } 
    }
    public function soil_payment($from,$to){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM `tbl_soil_purchase_payment` WHERE date BETWEEN '$from' AND '$to' AND approved_status=1";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        } 
    }
    public function soil_unapproved(){
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_soil_purchase_payment` WHERE  approved_status=0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            $pay = mysqli_num_rows($query_result);
        } else {
            die('Query problem' . mysqli_error($db_connect));
        } 
        $sql = "SELECT * FROM `tbl_soil_purchase` WHERE  approved_status=0";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            $pur = mysqli_num_rows($query_result);
        } else {
            die('Query problem' . mysqli_error($db_connect));
        } 

        return $pay + $pur;
    }

    public function add_kacha_sajano_sorder($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_kacha_sajano_sordar`(seller_name,contact_num,address,father_name,mother_name,nid_no,bank_acc_no,bank_name,branch_name,previousDues,opening_balance,user_id)VALUES('$data[seller_name]','$data[contact_num]','$data[address]','$data[father_name]','$data[mother_name]','$data[nid_no]','$data[bank_acc_no]','$data[bank_name]','$data[branch_name]','$data[opening_balance]','$data[opening_balance]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Kacha It Sajano Sorder added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_kacha_sajano_sorder() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sajano_sordar` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_kacha_sajano_sorder_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_kacha_sajano_sordar` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_kacha_sajano_sorder_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sajano_sordar` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_kacha_sajano_sorder_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_kacha_sajano_sordar` SET "
               . "seller_name = '$data[seller_name]',"
               . "contact_num = '$data[contact_num]',"
               . "address = '$data[address]',"
               . "father_name = '$data[father_name]',"
               . "mother_name = '$data[mother_name]',"
               . "nid_no = '$data[nid_no]',"
               . "bank_acc_no = '$data[bank_acc_no]',"
               . "bank_name = '$data[bank_name]',"
               . "branch_name = '$data[branch_name]'"
               
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Kacha It Sajano Info Updated successfully";
            header('Location: kacha_it_chulli_sorder.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function save_purchase_payment_info_kacha_sajano_sorder($data) {

        $db_connect = $this->__construct();

        $date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "INSERT INTO `tbl_kacha_sajano_sorder_payment` "
                . "(customer_name,"
                . "branch_id,"
                . "address,"
                . "contact_number,"
                . "totalDues,"
                . "date,"
                . "season,"
                . "payment_method,"
                . "paid,"
                . "currDues,"
                . "bankName,"
                . "cheque_num,"
                . "cheque_app_date,user_id) "
                . "VALUES "
                . "('$data[customer_name]',"
                . "'$_SESSION[branch_id]',"
                . "'$data[address]',"
                . "'$data[contact_number]',"
                . "'$data[totalDues]',"
                . "'$date',"
                 . "'$data[season]',"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$data[currDues]',"
                . "'$data[bankName]',"
                . "'$data[cheque_num]',"
                . "'$data[cheque_app_date]','$_SESSION[user_id]')";


        if (mysqli_query($db_connect, $sql)) {
            // $soil = $data['paid'];
            // $nm = $this->getOneCol('name','tbl_kacha_sorder','id',$data['customer_name']); 
            // $msg = "Dear sir, $nm (Kacha Sorder) payment $soil"."tk need your approval.";
            // $this->sendSMS('Hairlife', $msg, "8801935222000");
           $update = $this->update_kacha_sajano_sorder_balance($data['customer_name'],$data['paid']);
           if($update){
            $message = "Payment Info Update Successfully";
            return $message;
        }else{
            $message = "Payment Info Update Something Wrong";
            return $message;
        }
            
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_kacha_sajano_sorderpay_info($branch_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sajano_sorder_payment` WHERE branch_id = '$branch_id' AND deletion_status= 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }  
    public function update_kacha_sajano_sorder_balance($company_id,$paid) {
        $db_connect = $this->__construct();
        $amountt = $this->getOneCol('previousDues','tbl_kacha_sajano_sordar','id',$company_id);
       $amnt = $amountt + $paid ;
        $sql = "UPDATE `tbl_kacha_sajano_sordar` SET previousDues = '$amnt' WHERE id = '$company_id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Kacha Sajano Sorder Info Save Successfully";
            return $message;
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_kacha_sajano_sorder_payment($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_kacha_sajano_sorder_payment` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
        public function update_kacha_sajano_sorder_payment_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d', strtotime($_POST['date']));
        $old = $this->getOneCol('paid','tbl_kacha_sajano_sorder_payment','id',$data['id']);
        $paid = $data['paid'] - $old;
        $sql = "UPDATE `tbl_kacha_sajano_sorder_payment` SET "
               . "customer_name = '$data[customer_name]',"
               . "date = '$date',"
               . "address = '$data[address]',"
               . "contact_number = '$data[contact_number]',"
               . "season = '$data[season]',"
               . "paid = '$data[paid]',"
               . "payment_method = '$data[payment_method]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            

          $update = $this->update_kacha_sajano_sorder_balance($data['customer_name'],$paid);
           if($update){
            $_SESSION['message'] = "Kacha Sorder Payment Info Updated successfully";
            header('Location: kacha_sajano_sorder_payment_list.php');
        }else{
            $message = "Payment Info Update Something Wrong";
            return $message;
        }
            
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    //Dashboard  
        public function save_share_holder_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));     
        $directory = 'share_holder_img/';
        $directory1 = 'nominee_img/';
        $target_file1 = $directory1 . $_FILES['nominee_image']['name'];
        $target_file = $directory . $_FILES['image']['name'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
        $file_type1 = pathinfo($target_file1, PATHINFO_EXTENSION);
        $file_size = $_FILES['image']['size'];
        $file_size1 = $_FILES['nominee_image']['size'];
        $check = getimagesize($_FILES['image']['tmp_name']);
        $check1 = getimagesize($_FILES['nominee_image']['tmp_name']);
        if ($check && $check1) {
            if (file_exists($target_file) && file_exists($target_file1)) {
                $massage2 = "This file is already exists. please try new one";
                return $massage2;
            } else {
                if ($file_size > 10000000 && $file_size1 > 10000000) {
                    $massage2 = "File is too large. please try new one";
                    return $massage2;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type1 != 'jpg' && $file_type1 != 'png' && $file_type1 != 'jpeg') {
                        $massage2 = "File type is not valid. please try new one";
                        return $massage2;
                    } else {
                       if( move_uploaded_file($_FILES['image']['tmp_name'], $target_file) && move_uploaded_file($_FILES['nominee_image']['tmp_name'], $target_file1)){

                          $sql = "INSERT INTO `tbl_share_holder`(name,image,nominee_image,mobile_no,return_date,chq_number,father_name,nid_no,user_id,present_address,nominee_name,nominee_address,nominee_mobile_no,nid_nom,nominee_rltn,date,date_month,mother_name,status,profit_tk,profit_pr,amount,bricks_amount,ref,voucher,rate,ratepert) VALUES ('$data[name]','$target_file','$target_file1','$data[mobile_no]','$data[return_date]','$data[chq_number]','$data[father_name]','$data[nid_no]','$_SESSION[user_id]','$data[present_address]','$data[nominee_name]','$data[nominee_address]','$data[nominee_mobile_no]','$data[nid_nom]','$data[nominee_rltn]','$date','$date_month','$data[mother_name]',1,'$data[profit_tk]','$data[profit_pr]','$data[amount]','$data[bricks]','$data[ref]','$data[voucher]','$data[rate]','$data[ratepert]')";
                            if (mysqli_query($db_connect, $sql)) {    
                                $message = "Investor Info Save Successfully";
                                return $message;  
                                }else {
                                 die('Query problem' . mysqli_error($db_connect));
                                }
                       }  else{
                        return "Image Not Uploaded";
                    } 
                }

            }
        }
    }else{
          $sql = "INSERT INTO `tbl_share_holder`(name,mobile_no,return_date,chq_number,father_name,nid_no,user_id,present_address,nominee_name,nominee_address,nominee_mobile_no,nid_nom,nominee_rltn,date,date_month,mother_name,status) VALUES ('$data[name]','$data[mobile_no]','$data[return_date]','$data[chq_number]','$data[father_name]','$data[nid_no]','$_SESSION[user_id]','$data[present_address]','$data[nominee_name]','$data[nominee_address]','$data[nominee_mobile_no]','$data[nid_nom]','$data[nominee_rltn]','$date','$date_month','$data[mother_name]',1)";
                if (mysqli_query($db_connect, $sql)) {    
                    $message = "Share Holder Info Save Successfully";
                    return $message;  
                    }else {
                     die('Query problem' . mysqli_error($db_connect));
                    }
    }
    }
    public function select_all_share_holder_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_share_holder` " ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_vip_customer_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vip_customer` ORDER by id DESC" ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_delivery_all($invID) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sales_delivery` WHERE inv_id='$invID' ORDER by id DESC" ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_vip_customer_info_by_id($vip) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vip_customer` WHERE id=$vip ORDER by id DESC" ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_customer_info_by_id($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_seller` WHERE id=$id" ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_vip_delivery_info_by_id($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vip_deliver` WHERE vip_id=$id ORDER by date" ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_vip_delivery_info_by_date($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vip_deliver` WHERE id=$id" ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function vip_bricks_total($vip) {
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(bricks_quantity) as amn FROM `tbl_vip_deliver` WHERE vip_id='$vip'";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            foreach ($query_result as $key => $value);
            return $value['amn'];
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_customer_id_by_id($id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_customer_due` WHERE customer_id=$id ORDER by id DESC" ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_inactive_share_holder_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_share_holder` WHERE deletion_status= 0 AND branch_id = $_SESSION[branch_id] AND status= 0 " ;
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function save_vip_customer($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_vip_customer`(name,mobile_no,return_date,chq_number,nid_no,father_name,present_address,user_id,amount,bricks_amount,bricks_type,profit_pr,profit_tk,status,date,date_month,customer_id) VALUES ('$data[name]','$data[mobile_no]','$data[return_date]','$data[chq_number]','$data[nid_no]','$data[father_name]','$data[present_address]','$_SESSION[user_id]','$data[amount]','$data[bricks_amount]','$data[bricks_type]','$data[profit_pr]','$data[profit_tk]',1,'$date','$date_month','$data[customer_id]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "VIP Customer Save Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function update_vip_deliver($data) {
        $db_connect = $this->__construct();
        
        $date = date('Y-m-d', strtotime($_POST['date']));
        
        $sql = "UPDATE `tbl_vip_deliver` SET "
                . "date='$date',"
                . "memo='$data[memo]',"
                . "transport_no='$data[transport]',"
                . "bricks_quantity ='$data[bricks]',"
                . "driver ='$data[driver]'"
                . " WHERE id='$data[id]'";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "VIP Customer Update Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function savedue($customer_id,$due_amount,$user_id) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_customer_due`(customer_id, due, joma, user_id, date) 
            VALUES('$customer_id','$due_amount',0,'$user_id',NOW())";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Customer Due Added Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function savejoma($id,$due,$joma,$date,$user_id,$comment) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_customer_due`(customer_id, due, joma, user_id, date, comment) 
            VALUES('$id',$due,$joma,'$user_id','$date','$comment')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Customer Due Added Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function savenotification($message) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_notification` SET message='$message' WHERE id=1";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Notification has been Sent";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function savenotify($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_notification_2` SET notification='$data[notify]' WHERE id=1";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Notification has been Sent";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function getnotification() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_notification`";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
    public function save_vip_customer_delivery($data) {
        $db_connect = $this->__construct();
        
        $sql = "INSERT INTO `tbl_vip_deliver`(vip_id,date,memo,transport_no,bricks_quantity,delivery_place,driver, user_id) VALUES ('$data[vip_id]','$data[date]','$data[memo]','$data[tcno]','$data[bricksamn]','$data[deliveryplace]','$data[driver]','$_SESSION[user_id]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "VIP Customer Bricks Delivery Info Saved Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }

    public function delete_share_holder($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_share_holder` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
        public function show_share_holder_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_share_holder` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
public function update_share_holder_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));     

           $sql = "UPDATE `tbl_share_holder` SET name = '$data[name]', father_name = '$data[father_name]', mother_name = '$data[mother_name]',nominee_rltn = '$data[nominee_rltn]',present_address = '$data[present_address]',mobile_no = '$data[mobile_no]', nid_no = '$data[nid_no]', amount = '$data[amount]', profit_tk = '$data[profit_tk]', profit_pr = '$data[profit_pr]', nominee_name = '$data[nominee_name]',nominee_address = '$data[nominee_address]',nominee_mobile_no = '$data[nominee_mobile_no]',date = '$date',user_id ='$_SESSION[user_id]',date_month ='$date_month', status = 1 WHERE  id = '$data[id]'";
             if (mysqli_query($db_connect, $sql)) {
                     
            $_SESSION['message'] = "Share Holder info successfully";
            header('Location: manage_share_holder.php');
            
        }else{
            die('Query Problem' . mysqli_error($db_connect));
        }  
    } 

      public function select_all_document($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_reserve_document` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_reserve_document($id) {
        $db_connect = $this->__construct();
        $oldim = $this->getOneCol('doc','tbl_reserve_document','id',$id);

        $sql = "DELETE FROM `tbl_reserve_document` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
            unlink($oldim);
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function add_doc($data){
        //exit;
       // return 100;
         $db_connect = $this->__construct();
        $directory = 'doc_img/';
        $target_file = $directory . $_FILES['doc']['name'];
        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
         $file_size = $_FILES['doc']['size'];
        $check = getimagesize($_FILES['doc']['tmp_name']);
        if ($check) {
            if (file_exists($target_file)) {
                $massage2 = "This file is already exists. please try new one";
                return $massage2;
            } else {
                if ($file_size > 10000000 && $file_size1 > 10000000) {
                    $massage2 = "File is too large. please try new one";
                    return $massage2;
                } else {
                    if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg' && $file_type != 'docx' && $file_type != 'doc' && $file_type != 'pdf' && $file_type != 'xls') {
                        $massage2 = "File type is not valid. please try new one";
                        return $massage2;
                    } else {
                        
                       if( move_uploaded_file($_FILES['doc']['tmp_name'], $target_file)){

                          $sql = "INSERT INTO `tbl_reserve_document`(description,doc) VALUES ('$data[description]','$target_file')";
                            if (mysqli_query($db_connect, $sql)) {    
                                $message = "Document Info Save Successfully";
                                return $message;  
                                }else {
                                 die('Query problem' . mysqli_error($db_connect));
                                }
                       }  else{
                        return "Image Not Uploaded";
                    } 
                }

            }
        }

        }else{
            return "Please Upload an Image";
        }
    } 
    public function select_account_head_info_by_date($from, $to, $account_head) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE date BETWEEN '$from' AND '$to' AND account_head = '$account_head' AND deletion_status = 0 ORDER BY id";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_account_head_info_by_category($from, $to, $account_head,$category) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE date BETWEEN '$from' AND '$to' AND account_head = '$account_head' AND category = '$category' AND deletion_status = 0 ORDER BY id";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_account_income($in) {

        $db_connect = $this->__construct();
        date_default_timezone_set('Asia/Dhaka');
        $date = date('Y-m-d');

        $sql = "SELECT * FROM `tbl_account` WHERE date='$date' AND account_head = '$in' AND deletion_status = 0 ORDER BY id";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_account_income2($in) {

        $db_connect = $this->__construct();
        date_default_timezone_set('Asia/Dhaka');
        $date = date('Y-m-d');

        $sql = "SELECT * FROM `tbl_account` WHERE date='$date' AND account_head = '$in' AND deletion_status = 0 ORDER BY id";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_savings_in($in) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE purpose = '$in' AND account_type='2' ORDER BY id DESC";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_savings_out($in) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE purpose = '$in' AND account_type='1' ORDER BY id DESC";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_main_inc_head_info_by_date($from, $to, $account_head) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_main_inc` WHERE date BETWEEN '$from' AND '$to' AND account_head = '$account_head' AND deletion_status = 0 ORDER BY id";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
        public function select_main_inc_head_info_only_date($from, $to) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_main_inc` WHERE date BETWEEN '$from' AND '$to' AND deletion_status = 0 ORDER BY id DESC";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_account_head_info_only_head($account_head) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_account` WHERE account_head = '$account_head' AND deletion_status = 0 ORDER BY id DESC";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function select_main_inc_head_info_only_head($account_head) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_main_inc` WHERE account_head = '$account_head' AND deletion_status = 0 ORDER BY id DESC";
                $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_staff($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_staff`(name,contact_num,address,father_name,mother_name,nid_no,designation,user_id)VALUES('$data[name]','$data[contact_num]','$data[address]','$data[father_name]','$data[mother_name]','$data[nid_no]','$data[designation]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Staff added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_staff() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_staff` ORDER BY id";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function add_property($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_add_property`(property_name, details, quantity, amount, comment, date, user_id)VALUES('$data[property_name]','$data[details]','$data[pqty]','$data[amount]','$data[comment]',NOW(),'$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Property Added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    } 
    public function select_all_property() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_add_property` ORDER BY id";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_staff_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_staff` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_staff_info($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_staff` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $result = mysqli_query($db_connect, $sql);
            return $result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_staff_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_staff` SET "
               . "name = '$data[name]',"
               . "contact_num = '$data[contact_num]',"
               . "address = '$data[address]',"
               . "father_name = '$data[father_name]',"
               . "mother_name = '$data[mother_name]',"
               . "nid_no = '$data[nid_no]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Staff Info Updated successfully";
            header('Location: staff.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
   public function save_payment_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d', strtotime($_POST['date']));
        $sql = "INSERT INTO `tbl_sales_payment_info` "
                . "(customer_name,"
                . "address,"
                . "season,"
                . "contact_number,"
                . "branch_id,"
                . "totalDues,"
                . "TotalAmount,"
                . "date,"
                . "payment_method,"
                . "paid,"
                . "currDues,"
                . "bankName,"
                . "cheque_num,"
                . "cheque_app_date,user_id) "
                . "VALUES "
                . "('$data[customer_name]',"
                . "'$data[address]',"
                . "'$data[season]',"
                . "'$data[contact_number]',"
                . "'$data[branch_id]',"
                . "'$data[totalDues]',"
                . "'$data[TotalAmount]',"
                . "'$date',"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$data[currDues]',"
                . "'$data[bankName]',"
                . "'$data[cheque_num]',"
                . "'$data[cheque_app_date]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Collection Info Update Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_final_cuestoer_dues_info($cuesId,$finalDues) {
        $db_connect = $this->__construct();
        $sql = "UPDATE `tbl_add_seller` SET previousDues = '$finalDues' WHERE id = '$cuesId'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Collection Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function soil_purchase(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_soil_purchase ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }
    
    public function soil_payment_income(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_soil_purchase_payment ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }   
    public function koyla_purchase(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_koyla_purchase ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }
    
    public function koyla_payment_income(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_koyla_purchase_payment ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function kacha_payment(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_kacha_sordar_payment ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function kacha_reza_payment(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_kacha_reza_sorder_payment ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }  
    public function kacha_sajano_payment(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_kacha_sajano_sorder_payment ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function paka_reza_payment(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_paka_reza_sorder_payment ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }  
    public function sale(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(cash_paid) as paid FROM tbl_sale_invoice ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function collection(){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_sales_payment_info ";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }    


    //new
        public function soil_purchase_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_soil_purchase WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }
    
    public function soil_payment_income_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_soil_purchase_payment WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }   
    public function koyla_purchase_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_koyla_purchase WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }
    
    public function koyla_payment_income_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_koyla_purchase_payment WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function kacha_payment_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_kacha_sordar_payment WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function kacha_reza_payment_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_kacha_reza_sorder_payment WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }  
    public function kacha_sajano_payment_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_kacha_sajano_sorder_payment WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function paka_reza_payment_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_paka_reza_sorder_payment WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }  
    public function sale_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(cash_paid) as paid FROM tbl_sale_invoice WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function collection_search($season){
        $db_connect = $this->__construct();
        $sql = "SELECT SUM(paid) as paid FROM tbl_sales_payment_info WHERE season = '$season'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            foreach($result as $data);
            return $data['paid'];
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    } 
    public function select_all_active_pay_shareholder(){
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM tbl_share_holder_pay";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {      
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }
    public function add_share_pay($data){
        $db_connect = $this->__construct();
        $sql = "INSERT INTO tbl_share_holder_pay (share_holder_id,amount) VALUES ('$data[share_holder_id]','$data[amount]')";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {     
            return "Successfully Saved Payment";
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }   
    }
    
    public function expance_sales_summery(){
	$db_connect = $this->__construct();
    $sql = " SELECT sum(amount) as amount FROM `tbl_account` WHERE branch_id = '$_SESSION[branch_id]' AND `account_type` = '1'";
    	if (mysqli_query($db_connect, $sql)) {
    		return mysqli_query($db_connect, $sql);
    	} else {
            die('Query problem' . mysqli_error($db_connect));
        }
}
   public function total_dues__sales_summery(){
	$db_connect = $this->__construct();
    $sql = "SELECT sum(`previousDues`) as previousDues FROM `tbl_add_seller` WHERE branch_id = '$_SESSION[branch_id]'";
    	if (mysqli_query($db_connect, $sql)) {
    		return mysqli_query($db_connect, $sql);
    	} else {
            die('Query problem' . mysqli_error($db_connect));
        }
}

/********This Query Is For Investor Payment Report start Here********/

public function select_all_investor_payment_info($share_holder_id) {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_share_holder_pay` WHERE share_holder_id = '$share_holder_id' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function select_all_approved_investor_payment_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_share_holder_pay`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function select_all_investor_info() {
        $db_connect = $this->__construct();

        $sql = "SELECT name,id FROM `tbl_share_holder` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }


/********This Query Is For Investor Payment Report End Here********/
/*************************SMS Function Start*******************************/
public function send_individual_student_msg($data) {

        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $customer_id = $data['customer_id'];
        for ($i = 0; $i < count($customer_id); $i++) {
        $cust = $data['customer_id'][$i];
        $sql = "INSERT INTO `tbl_send_sms_individual` (customer_id,message,date,deletion_status,user_id) VALUES ('$cust','$data[message]','$date','0','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $sms = $data['message'];
            $number = $this->getOneCol('contact_num','tbl_add_seller','id',$cust);
            $this->sendSMS('Hairlife',$sms, '88'.$number);
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    return "Message Sent Successfully";
    }
    public function select_all_customer_msg_single() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_send_sms_individual` WHERE  deletion_status = 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_customer_msg_single($id) {

        $db_connect = $this->__construct();
        $sql = "UPDATE tbl_send_sms_individual SET deletion_status = 1 WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
/*************************SMS Function END*******************************/
/*************************Employee SMS Function Start*******************************/
public function select_all_active_product_staff_sms($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_staff`  ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function send_individual_student_msg_employee($data) {

        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $employee_id = $data['employee_id'];
        for ($i = 0; $i < count($employee_id); $i++) {
        $cust = $data['employee_id'][$i];
        $sql = "INSERT INTO `tbl_send_sms_employee` (employee_id,message,date,deletion_status,user_id) VALUES ('$cust','$data[message]','$date','0','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $sms = $data['message'];
            $number = $this->getOneCol('contact_num','tbl_staff','id',$cust);
            $this->sendSMS('Hairlife',$sms, '88'.$number);
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    return "Message Sent Successfully";
    }
    public function delete_employee_msg_sms($id) {

        $db_connect = $this->__construct();
        $sql = "UPDATE tbl_send_sms_employee SET deletion_status = 1 WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_employee_msg_single() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_send_sms_employee` WHERE  deletion_status = 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
/*************************Employee SMS Function END*******************************/

/*************************Raf Function Start*******************************/
public function raf_add_function($data) {
        $db_connect = $this->__construct();
        //$date = date('Y-m-d');
        $sql = "INSERT INTO tbl_raf (date,message,next_date,status) VALUES ('$data[date]','$data[message]','$data[next_date]','$data[status]')";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {     
            return "Successfully Saved";
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    
    }

    public function select_raf_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_raf`";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_raf($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_raf` WHERE id = '$id'";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_raf_info($id) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_raf` WHERE id='$id' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_raf_info($data) {
        $db_connect = $this->__construct();

        //$date = date('Y-m-d', strtotime($_POST['date']));

        $sql = "UPDATE `tbl_raf` SET "
                . "date='$data[date]',"
                . "message='$data[message]',"
                . "next_date='$data[next_date]',"
                . "status ='$data[status]'"
                . " WHERE id='$data[id]'";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $_SESSION['message'] = "Update successfully";
            header('Location: manage_raf.php');
            exit();
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
/*************************Raf Function END*******************************/

/*************************Production Function Start*******************************/
public function add_production_sorder($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_sordar_info`(seller_name,contact_num,emergency_num,address,nid_no,branch_id,previousDues,opening_balance,user_id)VALUES('$data[seller_name]','$data[contact_num]','$data[emergency_num]','$data[address]','$data[nid_no]','$data[branch_id]','$data[opening_balance]','$data[opening_balance]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "Sorder added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
     public function select_all_info_sorder() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sordar_info` ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_sorder_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_sordar_info` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function update_sorder_name_info($data) {
        $db_connect = $this->__construct();

        $sql = "UPDATE `tbl_sordar_info` SET "
               . "seller_name = '$data[seller_name]',"
               . "contact_num = '$data[contact_num]',"
               . "emergency_num = '$data[emergency_num]',"
               . "address = '$data[address]',"
               . "nid_no = '$data[nid_no]'"
               . " WHERE id='$data[id]'";

        if (mysqli_query($db_connect, $sql)) {
            $_SESSION['message'] = "Info Updated successfully";
            header('Location: add_production.php');
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_production($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sordar_info` WHERE branch_id = '$branch_id' ORDER BY id DESC ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function add_production_sale($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_production_sales`(date,seller_id,contact_num,nid_no,address,previousDues,qty,per_rate,discount,paid,description,branch_id,user_id)VALUES('$data[date]','$data[seller_id]','$data[contact_num]','$data[nid_no]','$data[address]','$data[previousDues]','$data[qty]','$data[per_rate]','$data[discount]','$data[paid]','$data[description]','$data[branch_id]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    

public function delete_production_sales($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_production_sales` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
/*************************Production Function END*******************************/

/*************************Visitor Function Start*******************************/
public function add_visitor($data) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_visitors`(date,visitor_id,visitor_name,m_no,address,company_name,description,next_date,branch_id,user_id)VALUES('$data[date]','$data[visitor_id]','$data[visitor_name]','$data[m_no]','$data[address]','$data[company_name]','$data[description]','$data[next_date]','$data[branch_id]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
           
            return "added Successfully";
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function select_all_visitor() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_visitors` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_visitor_info($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_visitors` WHERE id = '$id'";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    
    public function delete_invoice($id) {

        $db_connect = $this->__construct();
        $sql = "DELETE FROM `tbl_sale_invoice` WHERE id =$id";
        if (mysqli_query($db_connect, $sql)) {
             $_SESSION['message'] = "Delete Successfully";
             header("Location: {$_SERVER['HTTP_REFERER']}");
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }

public function select_all_visitor_name($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_visitors`  ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function send_individual_visitor_msg($data) {

        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $customer_id = $data['customer_id'];
        for ($i = 0; $i < count($customer_id); $i++) {
        $cust = $data['customer_id'][$i];
        $sql = "INSERT INTO `tbl_send_sms_visitor` (customer_id,message,date,deletion_status,user_id) VALUES ('$cust','$data[message]','$date','0','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $sms = $data['message'];
            $number = $this->getOneCol('m_no','tbl_visitors','id',$cust);
            $this->sendSMS('Hairlife',$sms, '88'.$number);
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    return "Message Sent Successfully";
    }
    public function select_all_visitor_msg_single() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_send_sms_visitor` WHERE  deletion_status = 0 ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function delete_visitor_msg_single($id) {

        $db_connect = $this->__construct();
        $sql = "UPDATE tbl_send_sms_visitor SET deletion_status = 1 WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Delete Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
/*************************Visitor Function END*******************************/
 public function select_all_production_sales($branch_id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_production_sales` WHERE branch_id = '$branch_id' ORDER BY id DESC";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    public function insertSaleProduction($customer_id, $invoice_number, $date, $date_month, $totalAmount, $cash_paid, $due_amount, $payment_method, $chequeAmount, $bank_name, $chequeNum, $chuque_app_date,$user_id,$vat,$discount,$transport,$season,$contact_num,$nid_no,$address,$previousDues,$remarks) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_sale_production`
                       (
                       customer_id,
                       season,
                       invoice_number,
                       date,
                       date_month,
                       totalAmount,
                       cash_paid,
                       due_amount,
                       payment_method,
                       chequeNum,
                       chequeAmount,
                       bank_name,
                       cheque_app_date,
                       vat,
                       discount,
                       transport,
                       contact_num,
                       nid_no,
                       address,
                       previousDues,
                       remarks,
                       user_id)
                        VALUES 
                        ('$customer_id',
                        '$season',
                        '$invoice_number',
                        '$date',
                        '$date_month',
                        '$totalAmount',
                        '$cash_paid',
                        '$due_amount',
                        '$payment_method',
                        '$chequeNum',
                        '$chequeAmount',
                        '$bank_name',
                        '$cheque_app_date',
                        '$vat',
                        '$discount',
                        '$transport',
                        '$contact_num',
                        '$nid_no',
                        '$address',
                        '$previousDues',
                        '$remarks',
                        '$user_id')";

        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $id = mysqli_insert_id($db_connect);
            return $id;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function insertsproduction_s($quantity,  $category, $pitch_code, $type, $unit_price, $net_amount,$user_id,$invoice_number,$date,$date_month,$rand_inv,$stock) {
        $db_connect = $this->__construct();

        $sql = "INSERT INTO `tbl_sale_productions`
                       (category,
                       pitch_code,
                       type,
                       quantity,
                       unit_price,
                       net_amount,
                       rand_inv,
                       user_id,
                       invoice_number,
                       date,
                       date_month,
                       stock)
                        VALUES 
                        ('$category',
                        '$pitch_code',
                        '$type',
                        '$quantity',
                        '$unit_price',
                        '$net_amount',
                        '$rand_inv',
                        '$user_id',
                        '$invoice_number',
                        '$date',
                        '$date_month',
                        '$stock')";

        if (mysqli_query($db_connect, $sql)) {
            $message = "Order Info Save Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_production_due($customer_id,$balance){
         $db_connect = $this->__construct();
        $sql2 = "UPDATE tbl_sordar_info
         SET previousDues = '$balance' WHERE id = '$customer_id'";
            if(mysqli_query($db_connect, $sql2))
            {
              return $message = "Sale Successfully";

            } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
 public function select_all_production_sales_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_production` ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function show_sale_invoice_production_info($rand) {
        $db_connect = $this->__construct();
        $sql = "SELECT * FROM `tbl_sale_productions` WHERE rand_inv = '$rand' ";
        if (mysqli_query($db_connect, $sql)) {
            $query_result = mysqli_query($db_connect, $sql);
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }

    //****#####--Delete Delete Salses Product Function Start--####*** 
    public function delete_sales_production_info($id) {
        $db_connect = $this->__construct();
           $sql = "DELETE FROM  `tbl_sale_production` WHERE id='$id'";
            if (mysqli_query($db_connect, $sql)) {
                $sql1 = "DELETE FROM  `tbl_sale_productions` WHERE rand_inv='$id'";
                 if (mysqli_query($db_connect, $sql1)) {
                $_SESSION['message'] = "Delete Successfully";
                header("Location: {$_SERVER['HTTP_REFERER']}");
                } else {
                    die('Query Problem' . mysqli_error($db_connect));
                } 
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }  
    }
     public function update_approved_st_sms($id){
        $db_connect = $this->__construct();
        $idc = $this->getOneCol('customer_id','tbl_sale_invoice','id',$id);
        $cust = $this->getOneCol('seller_name','tbl_add_seller','id',$idc);
        $to = $this->getOneCol('contact_num','tbl_add_seller','id',$idc);
         $amnt = $this->getOneCol('totalAmount','tbl_sale_invoice','id',$id);
         $cash_paid = $this->getOneCol('cash_paid','tbl_sale_invoice','id',$id);
         $due_amount = $this->getOneCol('due_amount','tbl_sale_invoice','id',$id);
         // $pd = $this->getOneCol('privious_dues', 'tbl_sale_invoice', 'id',$id);
         // $disc = $this->getOneCol('discount', 'tbl_sale_invoice', 'id',$id);
          // $dis = $this->getOneCol('total_discount', 'tbl_sale_invoice', 'id',$id);
         // if( $disc != '0'){
         //     $disa = ($amnt*$disc)/100;
         //    $disam = $amnt - $disa;
         //    }else{
         //  $disam = ($amnt - $dis); 
         //     }
         // $bal = $pd - $cash_paid;
         //    $val = $cash_paid + $bal;

        $sql = "UPDATE `tbl_sale_invoice` SET approved_status = 1  WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Messege Send Successfully";
             $sms = "Dear $cust! YourTotal Invoice Amount is $amnt Tk.You Paid $cash_paid tk Invoice Due is $due_amount tk Thank You.HASB";
           $this->sendSMS('Hairlife',$sms, '88'.$to);
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }  
    }
    
    public function update_delivery_ct_sms($id){
        $db_connect = $this->__construct();
        $idc = $this->getOneCol('customer_id','tbl_sale_invoice','id',$id);
        $cust = $this->getOneCol('seller_name','tbl_add_seller','id',$idc);
        $to = $this->getOneCol('contact_num','tbl_add_seller','id',$idc);
         $amnt = $this->getOneCol('totalAmount','tbl_sale_invoice','id',$id);
         $cash_paid = $this->getOneCol('cash_paid','tbl_sale_invoice','id',$id);
         $due_amount = $this->getOneCol('due_amount','tbl_sale_invoice','id',$id);
         // $pd = $this->getOneCol('privious_dues', 'tbl_sale_invoice', 'id',$id);
         // $disc = $this->getOneCol('discount', 'tbl_sale_invoice', 'id',$id);
          // $dis = $this->getOneCol('total_discount', 'tbl_sale_invoice', 'id',$id);
         // if( $disc != '0'){
         //     $disa = ($amnt*$disc)/100;
         //    $disam = $amnt - $disa;
         //    }else{
         //  $disam = ($amnt - $dis); 
         //     }
         // $bal = $pd - $cash_paid;
         //    $val = $cash_paid + $bal;

        $sql = "UPDATE `tbl_sale_invoice` SET delivery_status = 1  WHERE id='$id'";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Messege Send Successfully";
             $sms = "Dear $cust! Your Brick is delivered, Thank You.HASB";
           $this->sendSMS('Hairlife',$sms, '88'.$to);
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }  
    }

    public function save_production_collection_info($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d', strtotime($_POST['date']));
        $sql = "INSERT INTO `production_collection` "
                . "(customer_name,"
                . "address,"
                . "contact_number,"
                . "branch_id,"
                . "totalDues,"
                . "TotalAmount,"
                . "date,"
                . "payment_method,"
                . "paid,"
                . "currDues,"
                . "bankName,"
                . "cheque_num,"
                . "cheque_app_date,user_id) "
                . "VALUES "
                . "('$data[customer_name]',"
                . "'$data[address]',"
                . "'$data[contact_number]',"
                . "'$data[branch_id]',"
                . "'$data[totalDues]',"
                . "'$data[TotalAmount]',"
                . "'$date',"
                . "'$data[payment_method]',"
                . "'$data[paid]',"
                . "'$data[currDues]',"
                . "'$data[bankName]',"
                . "'$data[cheque_num]',"
                . "'$data[cheque_app_date]','$_SESSION[user_id]')";
        if (mysqli_query($db_connect, $sql)) {
            $message = "Collection Info Update Successfully";
            return $message;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    public function update_final_production_info($cuesId,$finalDues) {
        $db_connect = $this->__construct();
        $sql = "UPDATE `tbl_sordar_info` SET previousDues = '$finalDues' WHERE id = '$cuesId'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            $message = "Collection Successfully";
            return $message;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function select_all_update_production_collection_info() {

        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `production_collection` WHERE deletion_status= 0 AND branch_id = '$_SESSION[branch_id]' ORDER BY id DESC ";
        $query_result = mysqli_query($db_connect, $sql);
        if ($query_result) {
            return $query_result;
        } else {
            die('Query problem' . mysqli_error($db_connect));
        }
    }
    
    public function delete_sales_production_collection_info($id) {
        $db_connect = $this->__construct();  
        $cus = $this->getOneCol('customer_name','production_collection','id',$id);
        $paid = $this->getOneCol('paid','production_collection','id',$id);
        $pd = $this->getOneCol('previousDues','tbl_sordar_info','id',$cus);
        $fd = $pd + $paid;        
        $sql = "UPDATE `tbl_sordar_info` SET previousDues = '$fd' WHERE id = '$cus'";
            if (mysqli_query($db_connect, $sql)) {
                $sql1 = "DELETE FROM  `production_collection` WHERE id='$id'";
                 if (mysqli_query($db_connect, $sql1)) {
                    $_SESSION['message'] = "Delete Successfully";
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                } else {
                    die('Query Problem' . mysqli_error($db_connect));
                }
            } else {
                die('Query Problem' . mysqli_error($db_connect));
            }      
    }
    public function collection_production($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `production_collection` WHERE id = '$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function save_sorder_production($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_sorder_production`(date,season,week,date_from,sorder_1,production_1,nitt_1,comment_1,sorder_2,production_2,nitt_2,comment_2,sorder_3,production_3,nitt_3,comment_3,sorder_4,production_4,nitt_4,comment_4) VALUES ('$data[date]','$data[season]','$data[week]','$data[datefrom]','$data[sorder1]','$data[production1]','$data[total_production1]','$data[comment1]','$data[sorder2]','$data[production2]','$data[total_production2]','$data[comment2]','$data[sorder3]','$data[production3]','$data[total_production3]','$data[comment3]','$data[sorder4]','$data[production4]','$data[total_production4]','$data[comment4]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "Sorder Production Weekly Report Added Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function save_reja_production($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_reja_sorder_production`(`week`, `reja_sorder_name`, `section_a`, `prev_week_a`, `running_week_a`, `total_reja_a`, `section_b`, `prev_week_b`, `running_week_b`, `total_reja_b`) VALUES ('$data[week]','$data[reja_name]','$data[section_a]','$data[prev_weeka]','$data[running_weeka]','$data[total_rejaa]','$data[section_b]','$data[prev_weekb]','$data[running_weekb]','$data[total_rejab]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "Sorder Production Weekly Report Added Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function save_unload_production($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_unload_sorder_production`(`week`, `unload_name`, `section_a`, `prev_week_a`, `running_week_a`, `total_reja_a`, `section_b`, `prev_week_b`, `running_week_b`, `total_reja_b`) VALUES ('$data[week]','$data[unload_name]','$data[section_a]','$data[prev_weeka]','$data[running_weeka]','$data[total_rejaa]','$data[section_b]','$data[prev_weekb]','$data[running_weekb]','$data[total_rejab]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "Sorder Production Weekly Report Added Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function save_agun_production($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_agun_sorder_production`(`week`, `agun_name`, `section_a`, `prev_week_a`, `running_week_a`, `total_reja_a`, `section_b`, `prev_week_b`, `running_week_b`, `total_reja_b`) VALUES ('$data[week]','$data[agun_name]','$data[section_a]','$data[prev_weeka]','$data[running_weeka]','$data[total_rejaa]','$data[section_b]','$data[prev_weekb]','$data[running_weekb]','$data[total_rejab]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "Sorder Production Weekly Report Added Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function save_jam_production($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_jam_production`(`week`, `prev_jam`, `running_jam`, `total_jam`) VALUES ('$data[week]','$data[prev_jam]','$data[running_jam]','$data[total_jam]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "Jam Production Weekly Report Added Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function save_delivery_production($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_delivery_production`(`week`, `delivery1`, `week1`, `total1`, `delivery2`, `week2`, `total2`, `delivery3`, `week3`, `total3`) VALUES ('$data[week]','$data[bricks]','$data[bricks_delivery]','$data[total_bricks_delivery]','$data[adla]','$data[adla_delivery]','$data[total_adla_delivery]','$data[concrete]','$data[concrete_delivery]','$data[total_concrete_delivery]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "Delivery Production Weekly Report Added Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function save_income_production($data) {
        $db_connect = $this->__construct();
        $date = date('Y-m-d');
        $date_month = date('Y-m',strtotime($date));
        
        $sql = "INSERT INTO `tbl_income_production`(`week`, `income`, `week_income`, `total_income`, `expense`, `week_expense`, `total_expense`) VALUES ('$data[week]','$data[income]','$data[week_income]','$data[total_income]','$data[expense]','$data[week_expense]','$data[total_expense]')";
            
            if (mysqli_query($db_connect, $sql)) {    
                $message = "Income Production Weekly Report Added Successfully";
                return $message;  
                }else {
                    die('Query problem' . mysqli_error($db_connect));
                }
    }
    public function show_production() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sorder_production` ORDER by id DESC";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_production_by_week($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sorder_production` WHERE id='$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_reja_production_by_week($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_reja_sorder_production` WHERE id='$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_unload_production_by_week($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_unload_sorder_production` WHERE id='$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_agun_production_by_week($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_agun_sorder_production` WHERE id='$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_jam_production_by_week($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_jam_production` WHERE id='$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_delivery_production_by_week($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_delivery_production` WHERE id='$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_income_production_by_week($id) {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_income_production` WHERE id='$id'";
        $result = mysqli_query($db_connect, $sql);
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function show_notice_1() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_notification_2` ORDER BY id DESC limit 4";
        $result = mysqli_query($db_connect, $sql);
        
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function vip_random() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_vip_deliver` ORDER BY id DESC";
        $result = mysqli_query($db_connect, $sql);
        
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
    public function sale_invoice() {
        $db_connect = $this->__construct();

        $sql = "SELECT * FROM `tbl_sale_invoice` ORDER BY id DESC";
        $result = mysqli_query($db_connect, $sql);
        
        if ($result) {
            return $result;
        } else {
            die('Query Problem' . mysqli_error($db_connect));
        }
    }
}
