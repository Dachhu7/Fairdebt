<?php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
} else {

$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];


    // Get Employee ID from URL
    $eid = $_GET['editid'];
    
    // Fetch employee details from database
    $empQuery = mysqli_query($con, "SELECT EmpCode, EmpFname, EmpLName, EmpEmail, EmpContactNo, EmpJoingdate FROM employeedetail WHERE ID='$eid'");
    $employee = mysqli_fetch_array($empQuery);
    
    if (isset($_POST['submit'])) {
        // Define target directory for uploads
        $target_dir = "uploads/";
        
        // File upload handling with validation
        $aadharCard = $_FILES['aadhar']['name'];
        $panCard = $_FILES['pan']['name'];
        $profilePhoto = $_FILES['profile']['name'];
        
        // Paths for file uploads
        $aadharPath = $target_dir . basename($aadharCard);
        $panPath = $target_dir . basename($panCard);
        $photoPath = $target_dir . basename($profilePhoto);

        // Check if directory exists, if not, create it
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Move uploaded files and validate success
        $aadharUploaded = move_uploaded_file($_FILES['aadhar']['tmp_name'], $aadharPath);
        $panUploaded = move_uploaded_file($_FILES['pan']['tmp_name'], $panPath);
        $photoUploaded = move_uploaded_file($_FILES['profile']['tmp_name'], $photoPath);

        // Check if all files uploaded successfully
        if ($aadharUploaded && $panUploaded && $photoUploaded) {
            // Insert or update KYC details in the database
            $query = mysqli_query($con, "INSERT INTO empkyc (EmpID, AadharCard, PanCard, ProfilePhoto) VALUES ('$eid', '$aadharPath', '$panPath', '$photoPath') ON DUPLICATE KEY UPDATE AadharCard='$aadharPath', PanCard='$panPath', ProfilePhoto='$photoPath'");
            
            if ($query) {
                $msg = "KYC details have been updated successfully.";
            } else {
                $msg = "Something went wrong. Please try again.";
            }
        } else {
            $msg = "Failed to upload one or more files. Please check file sizes and formats.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Employee KYC Details</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
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
                    <h1 class="h3 mb-4 custom-heading">Edit Employee KYC Details</h1>
                    <p style="color:red;"><?php if (isset($msg)) { echo $msg; } ?></p>

                    <!-- Display Employee Information -->
                    <div class="card mb-4">
                        <div class="card-header">Employee Details</div>
                        <div class="card-body">
                            <p><strong>Employee Code:</strong> <?php echo $employee['EmpCode']; ?></p>
                            <p><strong>First Name:</strong> <?php echo $employee['EmpFname']; ?></p>
                            <p><strong>Last Name:</strong> <?php echo $employee['EmpLName']; ?></p>
                            <p><strong>Email:</strong> <?php echo $employee['EmpEmail']; ?></p>
                            <p><strong>Contact Number:</strong> <?php echo $employee['EmpContactNo']; ?></p>
                            <p><strong>Joining Date:</strong> <?php echo $employee['EmpJoingdate']; ?></p>
                        </div>
                    </div>

                    <!-- Form for KYC Details Update -->
                    <form method="post" enctype="multipart/form-data" class="user">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Aadhar Card:</label>
                            <div class="col-sm-8">
                                <input type="file" name="aadhar" class="form-control-file" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Pan Card:</label>
                            <div class="col-sm-8">
                                <input type="file" name="pan" class="form-control-file" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Profile Photo:</label>
                            <div class="col-sm-8">
                                <input type="file" name="profile" class="form-control-file" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">Update KYC</button>
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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>

</body>
</html>

<?php } ?>
