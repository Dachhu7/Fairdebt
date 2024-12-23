<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $usernameOrEmail = mysqli_real_escape_string($con, $_POST['usernameOrEmail']);
    $Password = mysqli_real_escape_string($con, $_POST['Password']);

    // Query to fetch the user record based on username or email
    $query = mysqli_query($con, "SELECT ID, EmpPassword, role FROM employeedetail WHERE EmpEmail='$usernameOrEmail' OR EmpCode='$usernameOrEmail'");
    $ret = mysqli_fetch_array($query);

    // Check if the user exists and validate the password
    if ($ret > 0) {
        // Compare entered password with the plain-text password stored in the database
        if ($Password === $ret['EmpPassword']) {
            $_SESSION['uid'] = $ret['ID'];
            $_SESSION['role'] = $ret['role']; // Store user role in session

            header('location:welcome.php');
            exit;
        } else {
            $msg = "Invalid password.";
        }
    } else {
        $msg = "Invalid username or email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Employee Record Management System">
    <meta name="author" content="Your Name">

    <title>Fairdebt Employee Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styling for background image -->
    <style>
        .bg-login-image {
            background: url('front/images/Employee_login.jpg') no-repeat center center;
            background-size: cover;
        }

        h3 {
            font-weight: bold;
        }

        .card {
            border-radius: 15px;
        }

        @media (max-width: 768px) {
            .bg-login-image {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <h3 align="center" style="
            color: white; 
            font-size: 2rem; 
            font-weight: bold; 
            padding-top: 6%; 
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 1.5px;">
            Fairdebt Employee Login
        </h3>

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>

                                    <?php if (isset($msg)) { ?>
                                        <p class="text-center text-danger font-weight-bold"><?php echo $msg; ?></p>
                                    <?php } ?>

                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="usernameOrEmail" name="usernameOrEmail" aria-describedby="emailHelp" placeholder="Enter EmpCode or Email..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="Password" name="Password" placeholder="Password" required>
                                        </div>
                                        <button type="submit" name="login" class="btn btn-primary btn-user btn-block">Login</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small text-white" href="forgotpassword.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small text-white" href="registererms.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
