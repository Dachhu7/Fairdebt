<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit();
}

// Fetch user role from the database
$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

// Delete record functionality
if (isset($_GET['delid'])) {
    $eid = $_GET['delid'];
    $query = mysqli_query($con, "DELETE employeedetail, empexpireince, empeducation FROM employeedetail
    LEFT JOIN empexpireince ON empexpireince.EmpID=employeedetail.ID
    LEFT JOIN empeducation ON empeducation.EmpID=employeedetail.ID
    WHERE employeedetail.ID='$eid'");
    echo "<script>alert('Record Deleted successfully');</script>";
    echo "<script>window.location.href='allemployees.php'</script>";
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

    <title>Employees Details</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Arial', sans-serif;
        }

        .custom-heading {
            font-size: 2.5rem;
            font-weight: bold;
            color: #4e73df;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 20px 0;
        }

        .table-responsive {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table.table {
            border-collapse: collapse;
            width: 100%;
        }

        table.table thead {
            background-color: #4e73df;
            color: white;
        }

        table.table th, table.table td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
            padding: 10px;
        }

        table.table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        table.table tbody tr:hover {
            background-color: #d1ecf1;
        }

        .btn-group .btn {
            margin: 2px;
        }

        .btn-info {
            background-color: #36b9cc;
            border: none;
        }

        .btn-danger {
            background-color: #e74a3b;
            border: none;
        }

        .btn-info:hover,
        .btn-danger:hover {
            opacity: 0.9;
        }

        .scroll-to-top {
            background-color: #4e73df;
        }

        .scroll-to-top:hover {
            background-color: #224abe;
        }

        .alert-message {
            text-align: center;
            font-size: 16px;
            color: red;
            font-weight: bold;
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
                    <h1 class="custom-heading">Employees Details</h1>
                    <p class="alert-message">
                        <?php if ($msg) {
                            echo $msg;
                        } ?>
                    </p>

                    <!-- Employees Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>S no.</th>
                                    <th>Code</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Contact no</th>
                                    <th>Joining Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($role == 'Manager') {
                                    $query = "SELECT * FROM employeedetail WHERE ManagerID = '$adminid'";
                                } else {
                                    $query = "SELECT * FROM employeedetail";
                                }

                                $ret = mysqli_query($con, $query);
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo $row['EmpCode']; ?></td>
                                        <td><?php echo $row['EmpFname']; ?></td>
                                        <td><?php echo $row['EmpLName']; ?></td>
                                        <td><?php echo $row['EmpEmail']; ?></td>
                                        <td><?php echo $row['EmpContactNo']; ?></td>
                                        <td><?php echo $row['EmpJoingdate']; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Edit
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="editempprofile.php?editid=<?php echo $row['ID']; ?>">Edit Profile</a>
                                                    <a class="dropdown-item" href="empkycdetails.php?editid=<?php echo $row['ID']; ?>">KYC Details</a>
                                                    <a class="dropdown-item" href="editempexp.php?editid=<?php echo $row['ID']; ?>">Edit Experience</a>
                                                </div>
                                                <a href="allemployees.php?delid=<?php echo $row['ID']; ?>" class="btn btn-danger ml-2" onclick="return confirm('Do you really want to delete this record?');">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                    $cnt++;
                                } ?>
                            </tbody>
                        </table>
                    </div>

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
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>

</body>

</html>
