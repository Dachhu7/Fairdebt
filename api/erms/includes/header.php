<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session variable 'uid' is not set or empty
if (strlen($_SESSION['uid']) == 0) {
    header('location:logout.php');
    exit;  // Stop further script execution after redirection
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
                        Employee Dashboard
                    </span>
                </li>
            </ul>

      <!-- Right-side Content -->
      <ul class="navbar-nav ml-auto">
        <!-- Notification for Leave Status -->
        <?php
        $empid = $_SESSION['uid'];

        // Fetch notifications for leave status (approved or rejected)
        $result = mysqli_query($con, "SELECT COUNT(*) AS leave_notifications FROM notifications WHERE EmpID='$empid' AND Status = 0");
        $row = mysqli_fetch_assoc($result);
        $leaveNotifications = $row['leave_notifications'];
        ?>
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle text-white" href="#" id="leaveNotificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-family: 'Poppins', sans-serif; font-weight: 500; text-transform: uppercase; padding: 10px 20px; transition: all 0.2s ease;">
            <i class="fas fa-bell fa-fw"></i>
            <?php if ($leaveNotifications > 0) { ?>
              <span class="badge badge-danger badge-counter" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 50%;"><?php echo $leaveNotifications; ?></span>
            <?php } ?>
          </a>
          <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="leaveNotificationDropdown" style="background: #f8f9fc; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h6 class="dropdown-header">Leave Notifications</h6>
            <?php
            if ($leaveNotifications > 0) {
              $notifQuery = mysqli_query($con, "SELECT ID, Description FROM notifications WHERE EmpID='$empid' AND Status = 0");
              while ($notifRow = mysqli_fetch_assoc($notifQuery)) {
                echo '<a class="dropdown-item" href="#" style="background-color: #e9ecef; color: #4e73df;">' . $notifRow['Description'] . '</a>';
              }
            } else {
              echo '<a class="dropdown-item" href="#">No new leave notifications.</a>';
            }
            ?>
            <div class="dropdown-divider"></div>
            <!-- Mark All as Read Button -->
            <a class="dropdown-item text-center" href="#" id="markAllAsRead" style="text-align: center;">Mark All as Read</a>
          </div>
        </li>

        <!-- Search Icon for XS Screens -->
        <li class="nav-item dropdown no-arrow d-sm-none">
          <a class="nav-link dropdown-toggle text-white" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-search fa-fw"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown" style="background: #f8f9fc; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <form class="form-inline mr-auto w-100 navbar-search">
              <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" style="font-family: 'Poppins', sans-serif; border-radius: 8px;">
                <div class="input-group-append">
                  <button class="btn btn-light" type="button" style="border-radius: 8px;">
                    <i class="fas fa-search fa-sm"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </li>

        <div class="topbar-divider d-none d-sm-block" style="border-left: 1px solid #d1d3e2;"></div>

        <!-- User Profile -->
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-family: 'Poppins', sans-serif; font-weight: 500; text-transform: uppercase; padding: 10px 20px; transition: all 0.2s ease;">
            <?php
            // Fetch user details
            $empid = $_SESSION['uid'];
            $ret = mysqli_query($con, "SELECT EmpFname, EmpLname FROM employeedetail WHERE ID='$empid'");
            $row = mysqli_fetch_array($ret);
            $fname = $row['EmpFname'];
            $lname = $row['EmpLname'];
            ?>
            <span class="mr-2 d-none d-lg-inline text-white small"><?php echo $fname . " " . $lname; ?></span>
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

<script>
  // Mark all notifications as read when clicked
  document.getElementById('markAllAsRead').addEventListener('click', function() {
    fetch('mark_all_notifications_read.php')
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          alert('All notifications marked as read.');
          location.reload();  // Reload the page to reflect the changes
        } else {
          console.error('Error Message:', data.message);  // Log detailed error message from PHP
          alert('Error marking notifications as read: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error marking notifications as read.');
      });
  });
</script>
