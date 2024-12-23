<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $uname = mysqli_real_escape_string($con, $_POST['username']); // Sanitize input
    $Password = mysqli_real_escape_string($con, $_POST['Password']); // Sanitize input

    // Check in tbladmin for Admin login by either AdminuserName or Email
    $queryAdmin = mysqli_query($con, "SELECT ID, AdminuserName, Email, Password FROM tbladmin WHERE AdminuserName = '$uname' OR Email = '$uname'");
    $admin = mysqli_fetch_array($queryAdmin);

    if ($admin && $Password === $admin['Password']) {
        // Admin login successful
        $_SESSION['aid'] = $admin['ID'];
        header('location:welcome.php'); // Redirect to Admin dashboard
        exit;
    } else {
        $msg = "Invalid Username or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin Login - Employee Record Management System">
    <meta name="author" content="Your Name">

    <title>ERMS Admin Login</title>

    <!-- Custom fonts for this template -->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Inline styles for background image -->
    <style>
        .bg-login-image {
            background: url('front/images/Admin_login.jpg') no-repeat center center;
            background-size: cover;
        }
        
        h3 {
            font-weight: bold;
        }

        .card {
            border-radius: 15px;
        }

        .container h3 {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            padding-top: 6%;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            letter-spacing: 1.5px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .bg-login-image {
                display: none;
            }
        }
        .custom-heading {
        font-size: 2rem; /* Larger font size */
        font-weight: 700; /* Bold text */
        color: #4e73df; /* Change color */
        text-align: center; /* Center align */
        text-transform: uppercase; /* Uppercase text */
        letter-spacing: 2px; /* Spacing between letters */
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1); /* Adding shadow */
        margin-top: 20px;
        margin-bottom: 20px;
    }
    </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <h3>Fairdebt Admin Login</h3>

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
                                            <input type="text" class="form-control form-control-user" id="username" name="username" required placeholder="Enter Username or Email">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" id="Password" name="Password" required placeholder="Password">
                                        </div>
                                        <button type="submit" name="login" class="btn btn-primary btn-user btn-block">Login</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small text-white" href="forgotpassword.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small text-white" href="registeradmin.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="../js/sb-admin-2.min.js"></script>

</body>

</html>
