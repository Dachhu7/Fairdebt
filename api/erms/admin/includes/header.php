<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variable 'aid' is not set or empty
if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit; // Stop further script execution after redirection
}

// Include database connection
include('includes/dbconnection.php');

// Handle marking all notifications as read
if (isset($_GET['markAllAsRead']) && $_GET['markAllAsRead'] == 'true') {
    $query = "UPDATE leaves SET Notified = 1 WHERE Notified = 0 AND Status IN ('Approved', 'Rejected')";
    $result = mysqli_query($con, $query);
    if (!$result) {
        echo "Error updating notifications: " . mysqli_error($con);
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light topbar mb-4 static-top shadow" style="background: linear-gradient(135deg, #4e73df, #1d3c6d); color: white; transition: all 0.3s ease;">
    <div class="container-fluid">
        <!-- Logo and Brand Name -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #fff;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Centered Links or Placeholder -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <span class="nav-link text-white font-weight-bold" style="font-size: 1.25rem; font-family: 'Poppins', sans-serif; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);">
                        Admin Dashboard
                    </span>
                </li>
            </ul>

            <!-- Right-side Content -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications -->
                <?php
                // Fetch pending leave notifications
                $pendingLeaves = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) AS pending_leaves FROM leaves WHERE Status = 'Pending'"))['pending_leaves'];

                // Fetch new approved/rejected leave notifications
                $notificationResult = mysqli_query($con, "SELECT leaves.ID, leaves.Status, employeedetail.EmpFname, employeedetail.EmpLName 
                                                          FROM leaves 
                                                          INNER JOIN employeedetail ON leaves.EmpID = employeedetail.ID
                                                          WHERE leaves.Status IN ('Approved', 'Rejected') AND leaves.Notified = 0");
                $leaveNotifications = $notificationResult ? mysqli_fetch_all($notificationResult, MYSQLI_ASSOC) : [];
                ?>
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="leaveNotificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-family: 'Poppins', sans-serif; font-weight: 500; text-transform: uppercase; padding: 10px 20px; transition: all 0.2s ease;">
                        <i class="fas fa-bell fa-fw"></i>
                        <?php if ($pendingLeaves > 0) { ?>
                            <span class="badge badge-danger badge-counter"><?php echo $pendingLeaves; ?></span>
                        <?php } ?>
                        <?php if (count($leaveNotifications) > 0) { ?>
                            <span class="badge badge-warning badge-counter"><?php echo count($leaveNotifications); ?></span>
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="leaveNotificationDropdown" style="background: #f8f9fc; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <h6 class="dropdown-header">Leave Notifications</h6>
                        <?php if ($pendingLeaves > 0) { ?>
                            <a class="dropdown-item" href="leaves-employee.php" style="color: #4e73df;">You have <?php echo $pendingLeaves; ?> pending leave applications.</a>
                        <?php } else { ?>
                            <a class="dropdown-item" href="#">No pending leave applications.</a>
                        <?php } ?>
                        <?php if (count($leaveNotifications) > 0) {
                            foreach ($leaveNotifications as $notification) { ?>
                                <a class="dropdown-item" href="leaves-employee.php?id=<?php echo $notification['ID']; ?>" style="color: #4e73df;">
                                    <?php echo $notification['EmpFname'] . " " . $notification['EmpLName']; ?> - Leave Status: <?php echo $notification['Status']; ?>
                                </a>
                        <?php }
                        } else { ?>
                            <a class="dropdown-item" href="#">No new notifications.</a>
                        <?php } ?>
                        <?php if ($pendingLeaves > 0 || count($leaveNotifications) > 0) { ?>
                            <a class="dropdown-item text-center" href="?markAllAsRead=true">Mark All as Read</a>
                        <?php } ?>
                    </div>
                </li>
                <div class="topbar-divider d-none d-sm-block" style="border-left: 1px solid #d1d3e2;"></div>
                <!-- User Profile -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php
                        $adminid = $_SESSION['aid'];
                        $adminName = mysqli_fetch_assoc(mysqli_query($con, "SELECT AdminName FROM tbladmin WHERE ID='$adminid'"))['AdminName'];
                        ?>
                        <span class="mr-2 d-none d-lg-inline"><?php echo htmlspecialchars($adminName); ?></span>
                        <img class="img-profile rounded-circle" src="front/images/Profile.png" alt="Profile" style="width: 40px; height: 40px; object-fit: cover;">
                    </a>
                    <!-- Dropdown -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="adminprofile.php"><i class="fas fa-user fa-sm fa-fw mr-2"></i> My Profile</a>
                        <a class="dropdown-item" href="changepassword.php"><i class="fas fa-cogs fa-sm fa-fw mr-2"></i> Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
