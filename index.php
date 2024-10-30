<?php
require './Admin.php';
error_reporting(E_ALL);
$obj_user = new Admin();
session_start();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    if ($user_id != NULL) {
        header('Location: welcome.php');
    }
}
if (isset($_POST['btn'])) {
    $message = $obj_user->admin_login_check($_POST);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Login | Abdullah Awwal</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Page background */
        body.form {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #8EC5FC, #E0C3FC);
            color: #333;
            overflow: hidden;
        }

        /* Form container */
        .form-container {
            width: 400px;
            max-width: 90%;
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        /* Form title */
        h1 {
            font-size: 2rem;
            color: #4A47A3;
            margin-bottom: 20px;
        }

        /* Field wrappers */
        .field-wrapper {
            margin-bottom: 20px;
            position: relative;
        }

        /* Input fields styling */
        .form-control {
            width: 100%;
            padding: 10px;
            padding-left: 35px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #8EC5FC;
            box-shadow: 0 0 5px rgba(142, 197, 252, 0.6);
        }

        /* Icon styling in input fields */
        .field-wrapper svg {
            position: absolute;
            left: 10px;
            top: 10px;
            fill: #666;
            color: #666;
        }

        /* Button styling */
        .btn {
            padding: 10px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #4A47A3;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn:hover {
            background-color: #3B3A80;
            transform: translateY(-3px);
        }

        /* Show Password toggle styling */
        .toggle-pass label {
            cursor: pointer;
            color: #666;
            font-size: 0.9rem;
        }

        /* Custom checkbox styling */
        .new-checkbox {
            display: inline-block;
        }

        /* Checkbox */
        .new-checkbox input {
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }

        /* Forgot password */
        .forgot-pass-link {
            display: block;
            margin-top: 15px;
            font-size: 0.9rem;
            color: #4A47A3;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-pass-link:hover {
            color: #3B3A80;
        }
        /* Wrapper for the checkbox label */
.toggle-pass label {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: #666;
    cursor: pointer;
}

/* Hide default checkbox */
.toggle-pass input[type="checkbox"] {
    display: none;
}

/* Custom switch styling */
.toggle-pass .slider {
    position: relative;
    width: 40px;
    height: 20px;
    background-color: #ccc;
    border-radius: 15px;
    margin-left: 10px;
    transition: background-color 0.3s;
    cursor: pointer;
}

/* Circular indicator inside the switch */
.toggle-pass .slider::before {
    content: "";
    position: absolute;
    top: 2px;
    left: 2px;
    width: 16px;
    height: 16px;
    background-color: white;
    border-radius: 50%;
    transition: transform 0.3s;
}

/* Checked state */
.toggle-pass input[type="checkbox"]:checked + .slider {
    background-color: #4A47A3;
}

.toggle-pass input[type="checkbox"]:checked + .slider::before {
    transform: translateX(20px);
}


        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="form">
    <div class="form-container">
        <div class="form-content">
            <h1>Log In</h1>
            <form class="text-left" method="post">
                <div class="form">
                    <div id="username-field" class="field-wrapper input">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <input id="username" name="user_name" type="text" class="form-control" placeholder="Username">
                    </div>

                    <div id="password-field" class="field-wrapper input mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <input id="password" name="password" type="password" class="form-control" placeholder="Password">
                    </div>
                    <div class="d-sm-flex justify-content-between">
                        <div class="field-wrapper toggle-pass">
                            <p class="d-inline-block">Show Password</p>
                            <label class="switch s-primary">
                                <input type="checkbox" id="toggle-password" class="d-none">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="field-wrapper">
                            <button name="btn" type="submit" class="btn btn-primary" value="">Log In</button>
                        </div>
                    </div>

                    <div class="field-wrapper text-center keep-logged-in">
                        <div class="new-checkbox checkbox-outline-primary">
                            <label>
                                <input type="checkbox" class="new-control-input">
                                <span class="new-control-indicator"></span>Keep me logged in
                            </label>
                        </div>
                    </div>

                    <div class="field-wrapper">
                        <a href="#" class="forgot-pass-link">Forgot Password?</a>
                    </div>
                </div>
            </form>                        
            <p class="terms-conditions">© All Rights Reserved by Abdullah Awwal. </p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            if (this.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        });
    </script>
</body>
</html>
