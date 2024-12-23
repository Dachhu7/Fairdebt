<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Get employee details from tblusers based on session user ID
$userId = $_SESSION['user_id']; // Assuming user_id is set in session
$query = "SELECT * FROM tblusers WHERE ID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <!-- Include necessary CSS files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <?php 
        if (file_exists('includes/header.php')) {
            include_once("includes/header.php"); 
        } else {
            echo "<p>Error: Header file not found.</p>";
        }
        ?>

        <!-- Sidebar -->
        <?php 
        if (file_exists('includes/sidebar.php')) {
            include_once("includes/sidebar.php"); 
        } else {
            echo "<p>Error: Sidebar file not found.</p>";
        }
        ?>

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <!-- Page Content -->
            <div class="content container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Profile</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Profile Card -->
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="profile-view">
                                    <div class="profile-img-wrap">
                                        <div class="profile-img">
                                            <a href="#"><img src="assets/img/profiles/avatar-02.jpg" alt="Profile Image"></a>
                                        </div>
                                    </div>
                                    <div class="profile-basic">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="profile-info-left">
                                                    <h3 class="user-name m-t-0 mb-0"><?php echo htmlentities($employee['FullName']); ?></h3>
                                                    <h6 class="text-muted"><?php echo htmlentities($employee['Department']); ?></h6>
                                                    <small class="text-muted"><?php echo htmlentities($employee['Role']); ?></small>
                                                    <div class="staff-id">Employee ID : <?php echo htmlentities($employee['EmployeeID']); ?></div>
                                                    <div class="small doj text-muted">Date of Join : <?php echo date("d M Y", strtotime($employee['DOJ'])); ?></div>
                                                    <div class="staff-msg"><a class="btn btn-custom" href="chat.php">Send Message</a></div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <ul class="personal-info">
                                                    <li>
                                                        <div class="title">Phone:</div>
                                                        <div class="text"><a href="tel:<?php echo htmlentities($employee['Phone']); ?>"><?php echo htmlentities($employee['Phone']); ?></a></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Email:</div>
                                                        <div class="text"><a href="mailto:<?php echo htmlentities($employee['Email']); ?>"><?php echo htmlentities($employee['Email']); ?></a></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Birthday:</div>
                                                        <div class="text"><?php echo htmlentities($employee['Birthday']); ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Address:</div>
                                                        <div class="text"><?php echo htmlentities($employee['Address']); ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Gender:</div>
                                                        <div class="text"><?php echo htmlentities($employee['Gender']); ?></div>
                                                    </li>
                                                    <li>
                                                        <div class="title">Reports to:</div>
                                                        <div class="text">
                                                            <div class="avatar-box">
                                                                <div class="avatar avatar-xs">
                                                                    <img src="assets/img/profiles/avatar-16.jpg" alt="Manager Avatar">
                                                                </div>
                                                            </div>
                                                            <a href="profile.php">Mr Sreenivas</a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pro-edit"><a data-target="#profile_info" data-toggle="modal" class="edit-icon" href="#"><i class="fa fa-pencil"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Profile Card -->
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    <!-- Footer -->
    <?php 
    if (file_exists('includes/footer.php')) {
        include_once("includes/footer.php"); 
    } else {
        echo "<p>Error: Footer file not found.</p>";
    }
    ?>

    <!-- Scripts -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
