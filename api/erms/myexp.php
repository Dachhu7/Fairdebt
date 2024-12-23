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
    
     $query=mysqli_query($con, "insert into empexpireince ( EmpID,Employer1Name, Employer1Designation, Employer1CTC,  Employer1WorkDuration, Employer2Name,  Employer2Designation, Employer2CTC, Employer2WorkDuration, Employer3Name, Employer3Designation, Employer3CTC, Employer3WorkDuration) value('$eid','$emp1name', '$emp1des', '$emp1ctc', '$emp1wd', '$emp2name', '$emp2des', '$emp2ctc', '$emp2wd', '$emp3name', '$emp3des', '$emp3ctc', '$emp3wd' )");
    if ($query) {
    $msg="Your Expirence data has been submitted succeesfully.";
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

  <title>My Experience</title>

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
          <h1 class="h3 mb-4 custom-heading">My Experience</h1>

<p style="font-size:16px; color:red" align="center"> <?php if($msg){
    echo $msg;
  }  ?> </p>

  <?php 
$empid=$_SESSION['uid'];
$query=mysqli_query($con,"select * from empexpireince where EmpID=$empid");
$row=mysqli_fetch_array($query);
if($row>0)
{

?>

<!-- Modal Trigger -->
<script>
  window.onload = function () {
  $('#experienceModal').modal('show');
  };
</script>

<!-- Modal -->
<div class="modal fade" id="experienceModal" tabindex="-1" aria-labelledby="experienceModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="experienceModalLabel">Notice</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
    <div class="modal-body">
        Your Experience details are already added. Now you can only edit the record.
  </div>
<div class="modal-footer">
  <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
</div>
  </div>
</div>
</div>


<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

<tr>
  <th>First Employer Name</th>
  <td><?php echo $row['Employer1Name'];?></td>
</tr>
<tr>
  <th>First Employer Designation</th>
  <td><?php echo $row['Employer1Designation'];?></td>
</tr>
<tr>
  <th>First Employer CTC</th>
  <td><?php echo $row['Employer1CTC'];?></td>
</tr>
<tr>
  <th>First Employer Work Duration</th>
  <td><?php echo $row['Employer1WorkDuration'];?></td>
</tr>
<tr>
  <th>Second Employer Name</th>
  <td><?php echo $row['Employer2Name'];?></td>
</tr>
<tr>
  <th>Second Employer Designation</th>
  <td><?php echo $row['Employer2Designation'];?></td>
</tr>
<tr>
  <th>Second Employer CTC</th>
  <td><?php echo $row['Employer2CTC'];?></td>
</tr>
<tr>
  <th>Second Employer Work Duration</th>
  <td><?php echo $row['Employer2WorkDuration'];?></td>
</tr>
<tr>
  <th>Third Employer Name</th>
  <td><?php echo $row['Employer3Name'];?></td>
</tr>
<tr>
  <th>Third Employer Designation</th>
  <td><?php echo $row['Employer3Designation'];?></td>
</tr>
<tr>
  <th>Third Employer CTC</th>
  <td><?php echo $row['Employer3CTC'];?></td>
</tr>
<tr>
  <th>Third Employer Work Duration</th>
  <td><?php echo $row['Employer3WorkDuration'];?></td>
</tr>

</table>




<?php } else {?>

<form class="user" method="post" action="">
 
               <div class="row">
                <div class="col-4 mb-3">Employer1 Name</div>
                   <div class="col-8 mb-3">   <input type="text" class="form-control form-control-user" id="emp1name" name="emp1name" aria-describedby="emailHelp" value=""></div>
                    </div>  
                    <div class="row">
                      <div class="col-4 mb-3">Employer1 Designation </div>
                     <div class="col-8 mb-3">  <input type="text" class="form-control form-control-user" id="emp1des" name="emp1des" aria-describedby="emailHelp" value=""></div>  
                     </div>



                    <div class="row">
                    <div class="col-4 mb-3">Employer1 CTC </div>
                    <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp1ctc" name="emp1ctc" aria-describedby="emailHelp" value=""></div>
                    </div>

                    <div class="row">
                      <div class="col-4 mb-3">Employer1 WorkDuration</div>
                     <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp1workduration" name="emp1workduration" aria-describedby="emailHelp" value="">
                    </div></div>
                    <div class="row">
                <div class="col-4 mb-3">Employer2 Name</div>
                   <div class="col-8 mb-3">   <input type="text" class="form-control form-control-user" id="emp2name" name="emp2name" aria-describedby="emailHelp" value=""></div>
                    </div>  
                    <div class="row">
                      <div class="col-4 mb-3">Employer2 Designation </div>
                     <div class="col-8 mb-3">  <input type="text" class="form-control form-control-user" id="emp2des" name="emp2des" aria-describedby="emailHelp" value=""></div>  
                     </div>



                    <div class="row">
                    <div class="col-4 mb-3">Employer2 CTC </div>
                    <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp2ctc" name="emp2ctc" aria-describedby="emailHelp" value=""></div>
                    </div>

                    <div class="row">
                      <div class="col-4 mb-3">Employer2 WorkDuration</div>
                     <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp2workduration" name="emp2workduration" aria-describedby="emailHelp" value="">
                    </div></div>
                    <div class="row">
                <div class="col-4 mb-3">Employer3 Name</div>
                   <div class="col-8 mb-3">   <input type="text" class="form-control form-control-user" id="emp3name" name="emp3name" aria-describedby="emailHelp" value=""></div>
                    </div>  
                    <div class="row">
                      <div class="col-4 mb-3">Employer3 Designation </div>
                     <div class="col-8 mb-3">  <input type="text" class="form-control form-control-user" id="emp3des" name="emp3des" aria-describedby="emailHelp" value=""></div>  
                     </div>



                    <div class="row">
                    <div class="col-4 mb-3">Employer3 CTC </div>
                    <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp3ctc" name="emp3ctc" aria-describedby="emailHelp" value=""></div>
                    </div>

                    <div class="row">
                      <div class="col-4 mb-3">Employer3 WorkDuration</div>
                     <div class="col-8 mb-3">
                      <input type="text" class="form-control form-control-user" id="emp3workduration" name="emp3workduration" aria-describedby="emailHelp" value="">
                    </div></div>

                    <div class="row" style="margin-top:4%">
                      <div class="col-4"></div>
                      <div class="col-4">
                      <input type="submit" name="submit"  value="submit" class="btn btn-primary btn-user btn-block">
                    </div>
                    </div>
                  
                  </form>

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
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/datatables-demo.js"></script>
</body>

</html>
<?php }  ?>
