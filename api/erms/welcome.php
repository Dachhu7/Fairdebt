<?php
session_start();
include('includes/dbconnection.php');

// Redirect to logout if session is not set
if (strlen($_SESSION['uid']) == 0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Welcome to Fairdebt</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        /* Custom CSS to center Today's Date card */
        .center-date-card {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Custom CSS for Today's Date */
        .today-date-header {
            color: #FF5733; /* Customize this color */
        }

        /* .today-date-text {
            color: #2D92D8;
        } */
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include_once('includes/header.php'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="row">

                    <!-- Welcome Widget -->
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card shadow-sm custom-card rounded-lg">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div class="text-info">
                                    <h5 class="font-weight-bold">Welcome Back!</h5>
                                    <?php
                                    $empid = $_SESSION['uid'];
                                    $ret = mysqli_query($con, "SELECT EmpFname, EmpLname FROM employeedetail WHERE ID='$empid'");
                                    $row = mysqli_fetch_array($ret);
                                    $fname = $row['EmpFname'];
                                    $lname = $row['EmpLname'];
                                    ?>
                                    <h6 class="text-muted"><?php echo $fname . " " . $lname; ?></h6>
                                </div>
                                <div>
                                    <i class="fas fa-user-circle fa-3x text-gray-500"></i>
                                </div>
                            </div>
                        </div>
                    </div>


                        <!-- Thought for the Day Widget -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card shadow-sm custom-card rounded-lg">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                    <div class="text-success">
                                    <h5 class="font-weight-bold">Thought for the Day</h5>
                                            <?php
                                            // Example static thought for the day
                                            $thought_for_day = "The only way to do great work is to love what you do. - Steve Jobs";
                                            ?>
                                            <div class="h7 mb-0 text-muted text-gray-800"><?php echo $thought_for_day; ?></div>
                                        </div>
                                        <div class="col-auto ml-4">
                                            <i class="fas fa-lightbulb fa-3x text-gray-500"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Display Today's Date -->
                    <div class="row center-date-card">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card shadow-sm custom-card rounded-lg">
                                <div class="card-body text-center">
                                    <h5 class="font-weight-bold today-date-header">Today's Date</h5>
                                    <?php
                                    $currentDate = date('l, F j, Y'); // Day, Month Day, Year
                                    ?>
                                    <h6 class="today-date-text"><?php echo $currentDate; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements Section -->
                    <div class="row">
                        <div class="col-xl-12 col-md-12 mb-4">
                            <div class="card shadow-sm custom-card rounded-lg">
                                <div class="card-header text-white bg-primary">
                                    <h5 class="mb-0">Announcements</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // Fetch announcements from the database
                                    $announcementsQuery = "SELECT Title, Description, AnnouncementDate FROM announcements ORDER BY AnnouncementDate DESC";
                                    $announcementsResult = mysqli_query($con, $announcementsQuery);

                                    // Check for query errors
                                    if (!$announcementsResult) {
                                        echo "Error fetching announcements: " . mysqli_error($con);
                                    } elseif (mysqli_num_rows($announcementsResult) > 0) {
                                        // Display announcements
                                        while ($announcement = mysqli_fetch_assoc($announcementsResult)) {
                                            echo "<div class='mb-3'>";
                                            echo "<strong>" . htmlspecialchars($announcement['Title']) . "</strong> - " . htmlspecialchars($announcement['AnnouncementDate']) . "<br>";
                                            echo "<p>" . htmlspecialchars($announcement['Description']) . "</p>";
                                            echo "</div>";
                                        }
                                    } else {
                                        echo "No announcements available.";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Holiday Section -->
                    <div class="row">
                        <div class="col-xl-12 col-md-12 mb-4">
                            <div class="card shadow-sm custom-card rounded-lg">
                                <div class="card-header text-white bg-warning">
                                    <h5 class="mb-0">Upcoming Holiday</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $today = date('Y-m-d'); // Get today's date
                                    $query = "SELECT * FROM holidays WHERE Holiday_Date >= '$today' ORDER BY Holiday_Date ASC LIMIT 1";
                                    $result = mysqli_query($con, $query);
                                    $holiday = mysqli_fetch_assoc($result);
                                    if ($holiday) {
                                        echo "<div class='mb-3'>";
                                        echo "<strong>" . $holiday['Holiday_Name'] . " - " . $holiday['Holiday_Date'] . "</strong>";
                                        echo "</div>";
                                    } else {
                                        echo "<div class='mb-3'>No upcoming holidays.</div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
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

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- jQuery UI JS -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <!-- Custom scripts for all pages -->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
<?php } ?>
