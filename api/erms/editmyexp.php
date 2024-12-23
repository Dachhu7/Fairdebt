<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
//error_reporting(0);
if (strlen($_SESSION['uid']==0)) {
  header('location:logout.php');
  } else{


if(isset($_POST['submit']))
  {
    $eid=$_SESSION['uid'];
    $emp1name=$_POST['emp1name'];
    $emp1des=$_POST['emp1des'];
    $emp1ctc=$_POST['emp1ctc'];
    $emp1wd=$_POST['emp1workduration'];
    $emp2name=$_POST['emp2name'];
    $emp2des=$_POST['emp2des'];
    $emp2ctc=$_POST['emp2ctc'];
    $emp2wd=$_POST['emp2workduration'];
    $emp3name=$_POST['emp3name'];
    $emp3des=$_POST['emp3des'];
    $emp3ctc=$_POST['emp3ctc'];
    $emp3wd=$_POST['emp3workduration'];
    
     $query=mysqli_query($con, "update empexpireince set Employer1Name='$emp1name',  Employer1Designation ='$emp1des', Employer1CTC ='$emp1ctc', Employer1WorkDuration='$emp1wd', Employer2Name='$emp2name',  Employer2Designation ='$emp2des', Employer2CTC ='$emp2ctc', Employer2WorkDuration='$emp2wd', Employer3Name='$emp3name',  Employer3Designation ='$emp3des', Employer3CTC ='$emp3ctc', Employer3WorkDuration='$emp3wd'  where EmpID='$eid'");
    if ($query) {
    $msg="Your Expirence has been updated.";
  }
  else
    {
      $msg="Something Went Wrong. Please try again.";
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

  <title>Edit My Expirence</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
        }

        .custom-heading {
            font-size: 2rem;
            font-weight: bold;
            color: #4e73df;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 800px;
        }

        .form-control {
            border: 2px solid #ced4da;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78, 115, 223, 0.5);
        }

        .btn-primary {
            background: #4e73df;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background: #375a9e;
        }

        .table {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            padding: 15px;
            text-align: left;
        }

        .msg {
            text-align: center;
            font-size: 1.2rem;
            color: #28a745;
        }

        .error-msg {
            text-align: center;
            font-size: 1.2rem;
            color: #dc3545;
        }
        .modal-header {
            background-color: #4e73df;
            color: white;
        }
    </style>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
  <?php include_once('includes/sidebar.php')?>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
         <?php include_once('includes/header.php')?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-4 custom-heading">Edit My Experience</h1>

<p style="font-size:16px; color:red" align="center"> <?php if($msg){
    echo $msg;
  }  ?> </p>

<form class="user" method="post" action="">
  <?php
 $empid=$_SESSION['uid'];
$ret=mysqli_query($con,"select * from empexpireince where EmpID='$empid'");
$num=mysqli_num_rows($ret);
if($num>0){
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
               <div class="row">
                <div class="col-4 mb-3">Employer1 Name</div>
                   <div class="col-8 mb-3">   <input type="text" class="form-control form-control-user" id="emp1name" name="emp1name" aria-describedby="emailHelp" value="<?php  echo $row['Employer1Name'];?>"></div>
                    </div>  
                    <div class="row">
                      <div class="col-4 mb-3">Employer1 Designation </div>
                     <div class="col-8 mb-3">  <input type="text" class="form-control form-control-user" id="emp1des" name="emp1des" aria-describedby="emailHelp" value="<?php  echo $row['Employer1Designation'];?>"></div>  
                     </div>



                    <div class="row">
                    <div class="col-4 mb-3">Employer1 CTC </div>
                    <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp1ctc" name="emp1ctc" aria-describedby="emailHelp" value="<?php  echo $row['Employer1CTC'];?>"></div>
                    </div>

                    <div class="row">
                      <div class="col-4 mb-3">Employer1 WorkDuration</div>
                     <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp1workduration" name="emp1workduration" aria-describedby="emailHelp" value="<?php  echo $row['Employer1WorkDuration'];?>">
                    </div></div>
                    <div class="row">
                <div class="col-4 mb-3">Employer2 Name</div>
                   <div class="col-8 mb-3">   <input type="text" class="form-control form-control-user" id="emp2name" name="emp2name" aria-describedby="emailHelp" value="<?php  echo $row['Employer2Name'];?>"></div>
                    </div>  
                    <div class="row">
                      <div class="col-4 mb-3">Employer2 Designation </div>
                     <div class="col-8 mb-3">  <input type="text" class="form-control form-control-user" id="emp2des" name="emp2des" aria-describedby="emailHelp" value="<?php  echo $row['Employer2Designation'];?>"></div>  
                     </div>



                    <div class="row">
                    <div class="col-4 mb-3">Employer2 CTC </div>
                    <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp2ctc" name="emp2ctc" aria-describedby="emailHelp" value="<?php  echo $row['Employer2CTC'];?>"></div>
                    </div>

                    <div class="row">
                      <div class="col-4 mb-3">Employer2 WorkDuration</div>
                     <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp2workduration" name="emp2workduration" aria-describedby="emailHelp" value="<?php  echo $row['Employer2WorkDuration'];?>">
                    </div></div>
                    <div class="row">
                <div class="col-4 mb-3">Employer3 Name</div>
                   <div class="col-8 mb-3">   <input type="text" class="form-control form-control-user" id="emp3name" name="emp3name" aria-describedby="emailHelp" value="<?php  echo $row['Employer3Name'];?>"></div>
                    </div>  
                    <div class="row">
                      <div class="col-4 mb-3">Employer3 Designation </div>
                     <div class="col-8 mb-3">  <input type="text" class="form-control form-control-user" id="emp3des" name="emp3des" aria-describedby="emailHelp" value="<?php  echo $row['Employer3Designation'];?>"></div>  
                     </div>



                    <div class="row">
                    <div class="col-4 mb-3">Employer3 CTC </div>
                    <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp3ctc" name="emp3ctc" aria-describedby="emailHelp" value="<?php  echo $row['Employer3CTC'];?>"></div>
                    </div>

                    <div class="row">
                      <div class="col-4 mb-3">Employer3 WorkDuration</div>
                     <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp3workduration" name="emp3workduration" aria-describedby="emailHelp" value="<?php  echo $row['Employer3WorkDuration'];?>">
                    </div></div>
<?php } ?>
                    <div class="row" style="margin-top:4%">
                      <div class="col-4"></div>
                      <div class="col-4">
                      <input type="submit" name="submit"  value="Update" class="btn btn-primary btn-user btn-block">
                    </div>
                    </div>
                  
                  </form>


<?php } else {?>

  <div class="row" style="margin-top:4%">
                      <div class="col-12" style="font-size:18px; color:red;">First Add your experience details after that you can edit experience details.</div>
                   
                    </div>
<?php } ?>


        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
   <?php include_once('includes/footer.php');?>
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
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

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
<?php }  ?>
