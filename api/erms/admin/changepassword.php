<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['aid'] == 0)) {
  header('location:logout.php');
} else {

  $adminid = $_SESSION['aid'];
  $query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
  $result = mysqli_fetch_array($query);
  $role = $result['Role'];

  if (isset($_POST['submit'])) {
    $adminid = $_SESSION['aid'];
    $cpassword = $_POST['currentpassword'];
    $newpassword = $_POST['newpassword'];

    // Query to check if the current password is correct
    $query = mysqli_query($con, "SELECT ID FROM tbladmin WHERE ID='$adminid' AND Password='$cpassword'");
    $row = mysqli_fetch_array($query);

    if ($row > 0) {
      // Update the password with the new password (plain text)
      $ret = mysqli_query($con, "UPDATE tbladmin SET Password='$newpassword' WHERE ID='$adminid'");
      $msg = "Your password was successfully changed";
    } else {
      $msg = "Your current password is wrong";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Change Password</title>

  <!-- Custom fonts for this template-->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <script type="text/javascript">
    function checkpass() {
      if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
        alert('New Password and Confirm Password fields do not match');
        document.changepassword.confirmpassword();
        return false;
      }
      return true;
    }
  </script>
  <style>
        .custom-heading {
            font-size: 2rem;
            font-weight: 700;
            color: #4e73df;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
    </style>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <?php
            if ($role == 'Super Admin') {
                include_once('includes/sidebar.php');
            } elseif ($role == 'HR') {
                include_once('includes/hr_sidebar.php');
            } elseif ($role == 'Manager') {
                include_once('includes/m_sidebar.php');
            } else {
                echo "<p style='color: red;'>Access Denied: Role not recognized.</p>";
                exit();
            }
        ?>
        <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php
                if ($role == 'Super Admin') {
                    include_once('includes/header.php');
                } elseif ($role == 'HR') {
                    include_once('includes/hr_header.php');
                } elseif ($role == 'Manager') {
                    include_once('includes/m_header.php');
                } else {
                    echo "<p style='color: red;'>Access Denied: Role not recognized.</p>";
                    exit();
                }
                ?>
                <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 custom-heading">Change Password</h1>

          <!-- Display Message -->
          <p style="font-size:16px; color:red" align="center"> <?php if ($msg) {
                                                                  echo $msg;
                                                                } ?> </p>

          <!-- Change Password Form -->
          <form name="changepassword" class="user" method="post" onsubmit="return checkpass();">
            <?php
            $adminid = $_SESSION['aid'];
            $ret = mysqli_query($con, "SELECT * FROM tbladmin WHERE ID='$adminid'");
            $cnt = 1;
            while ($row = mysqli_fetch_array($ret)) {
            ?>
              <div class="row">
                <div class="col-4 mb-3">Current Password</div>
                <div class="col-8 mb-3">
                  <input type="Password" class="form-control form-control-user" id="Password" name="currentpassword" value="" required="true">
                </div>
              </div>
              <div class="row">
                <div class="col-4 mb-3">New Password</div>
                <div class="col-8 mb-3">
                  <input type="Password" class="form-control form-control-user" id="newpassword" name="newpassword" value="" required="true">
                </div>
              </div>

              <div class="row">
                <div class="col-4 mb-3">Confirm Password</div>
                <div class="col-8 mb-3">
                  <input type="Password" class="form-control form-control-user" id="confirmpassword" name="confirmpassword" value="" required="true">
                </div>
              </div>

            <?php } ?>
            <div class="row" style="margin-top:4%">
              <div class="col-4"></div>
              <div class="col-4">
                <input type="submit" name="submit" value="Change" class="btn btn-primary btn-user btn-block">
              </div>
            </div>

          </form>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include_once('includes/footer.php'); ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <script type="text/javascript">
    $(".jDate").datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    }).datepicker("update", "10/10/2016");
  </script>

</body>

</html>
<?php } ?>
