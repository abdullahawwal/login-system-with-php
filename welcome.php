<?php
ob_start();
session_start();
error_reporting(0);
require './Admin.php';
$obj_user = new Admin();
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        /* Reset and font styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Background and alignment */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #6e45e2, #88d3ce);
            color: #ffffff;
            text-align: center;
            overflow: hidden;
        }

        /* Welcome container */
        .welcome {
            background: rgba(0, 0, 0, 0.3);
            padding: 30px;
            border-radius: 15px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1.5s ease-in-out;
        }

        /* Title and text styling */
        .welcome h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #ffde59;
        }

        .welcome h3 {
            font-size: 1.3rem;
            color: #ffffff;
        }

        /* Button styling */
        .welcome a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            background-color: #ff6f61;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s, transform 0.3s;
        }

        .welcome a:hover {
            background-color: #ff3d33;
            transform: translateY(-5px);
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
<body>
    <div class="welcome">
        <h1>Congratulations!</h1>
        <h3>Hello Mr <?php echo $user_name; ?>, You have successfully logged in.</h3>
        <a href="logout.php?id=<?= $user_id; ?>" id="logout-btn">Logout</a>
    </div>

    <script>
        // Adding a message fade-out effect on logout button click
        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault();
            document.querySelector('.welcome').style.transition = "opacity 0.8s ease";
            document.querySelector('.welcome').style.opacity = 0;
            setTimeout(() => {
                window.location.href = event.target.href;
            }, 800);
        });
    </script>
</body>
</html>
