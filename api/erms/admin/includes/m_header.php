<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variable 'aid' is not set or empty
if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;  // Stop further script execution after redirection
}

// Fetch the manager's ID (admin ID or manager ID) from session
$adminid = $_SESSION['aid'];

// Fetch pending leave notifications for the manager's employees
$query = "SELECT COUNT(*) AS pending_leaves 
          FROM leaves 
          INNER JOIN employeedetail ON leaves.EmpID = employeedetail.ID
          WHERE leaves.Status = 'Pending' 
          AND employeedetail.ManagerID = '$adminid'";

$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pendingLeaves = $row['pending_leaves'];
} else {
    // Handle query failure
    die("Error executing query: " . mysqli_error($con));
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
                        Manager Dashboard
                    </span>
                </li>
            </ul>

      <!-- Right-side Content -->
      <ul class="navbar-nav ml-auto">
        <!-- Notification for Pending Leave Applications -->
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle text-white" href="#" id="leaveNotificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-family: 'Poppins', sans-serif; font-weight: 500; text-transform: uppercase; padding: 10px 20px; transition: all 0.2s ease;">
            <i class="fas fa-bell fa-fw"></i>
            <?php if ($pendingLeaves > 0) { ?>
              <span class="badge badge-danger badge-counter" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 50%;"><?php echo $pendingLeaves; ?></span>
            <?php } ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="leaveNotificationDropdown" style="background: #f8f9fc; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h6 class="dropdown-header">Leave Notifications</h6>
            <?php if ($pendingLeaves > 0) { ?>
              <a class="dropdown-item" href="leaves-employee.php" style="background-color: #e9ecef; color: #4e73df;">You have <?php echo $pendingLeaves; ?> pending leave applications.</a>
            <?php } else { ?>
              <a class="dropdown-item" href="#">No pending leave applications.</a>
            <?php } ?>
          </div>
        </li>
        <div class="topbar-divider d-none d-sm-block" style="border-left: 1px solid #d1d3e2;"></div>

        <!-- User Profile -->
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-family: 'Poppins', sans-serif; font-weight: 500; text-transform: uppercase; padding: 10px 20px; transition: all 0.2s ease;">
            <?php
            // Fetch admin details
            $ret = mysqli_query($con, "SELECT AdminName FROM tbladmin WHERE ID='$adminid'");
            $row = mysqli_fetch_array($ret);
            $name = $row['AdminName'];
            ?>
            <span class="mr-2 d-none d-lg-inline text-white small"><?php echo htmlspecialchars($name); ?></span>
            <img class="img-profile rounded-circle" src="front/images/Profile.png" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid white;">
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
