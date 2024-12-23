<?php
// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('includes/dbconnection.php');

// Ensure the user is logged in and role is set
if (!isset($_SESSION['role'])) {
    echo "<script>alert('You need to login first!'); window.location.href='loginerms.php';</script>";
    exit;
}

// Fetch current permissions for the logged-in user
$role = $_SESSION['role'];
$permissions = [];

// Query to fetch the permissions based on the logged-in user's role
$query = "SELECT * FROM user_permissions WHERE role = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $role);
$stmt->execute();
$result = $stmt->get_result();

// Store permissions in an associative array
while ($row = $result->fetch_assoc()) {
    $permissions[$row['menu_item']] = $row['has_access'];
}

// Sidebar menu items (these items are fixed in the sidebar)
$menuItems = [
    "Dashboard" => "welcome.php",
    "My Exp" => "myexp.php",
    "Edit My Exp" => "editmyexp.php",
    "My Education" => "myeducation.php",
    "Edit My Education" => "editmyeducation.php",
    "LMS" => "apply_leave.php"
];
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" style="background: linear-gradient(135deg, #4e73df, #1d3c6d);">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="welcome.php" style="color: white; text-decoration: none;">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3" style="font-family: 'Poppins', sans-serif; font-weight: bold; text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);">Fairdebt Solutions</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" style="border-color: #d1d3e2;">

    <!-- Nav Item - Dashboard -->
    <?php if (isset($permissions['Dashboard']) && $permissions['Dashboard'] == 1) { ?>
        <li class="nav-item">
            <a class="nav-link" href="welcome.php" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
    <?php } ?>

    <!-- Divider -->
    <hr class="sidebar-divider" style="border-color: #d1d3e2;">

    <!-- Main Section -->
    <div class="sidebar-heading" style="color: white; font-weight: 600;">Main</div>

    <!-- Nav Items based on Permissions -->
    <?php if (isset($permissions['My Exp']) && $permissions['My Exp'] == 1) { ?>
        <li class="nav-item">
            <a class="nav-link" href="myexp.php" style="font-family: 'Poppins', sans-serif;">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>My Exp</span>
            </a>
        </li>
    <?php } ?>

    <?php if (isset($permissions['Edit My Exp']) && $permissions['Edit My Exp'] == 1) { ?>
        <li class="nav-item">
            <a class="nav-link" href="editmyexp.php" style="font-family: 'Poppins', sans-serif;">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Edit My Exp</span>
            </a>
        </li>
    <?php } ?>

    <?php if (isset($permissions['My Education']) && $permissions['My Education'] == 1) { ?>
        <li class="nav-item">
            <a class="nav-link" href="myeducation.php" style="font-family: 'Poppins', sans-serif;">
                <i class="fas fa-fw fa-table"></i>
                <span>My Education</span>
            </a>
        </li>
    <?php } ?>

    <?php if (isset($permissions['Edit My Education']) && $permissions['Edit My Education'] == 1) { ?>
        <li class="nav-item">
            <a class="nav-link" href="editmyeducation.php" style="font-family: 'Poppins', sans-serif;">
                <i class="fas fa-fw fa-table"></i>
                <span>Edit My Education</span>
            </a>
        </li>
    <?php } ?>

    <?php if (isset($permissions['LMS']) && $permissions['LMS'] == 1) { ?>
        <li class="nav-item">
            <a class="nav-link" href="apply_leave.php" style="font-family: 'Poppins', sans-serif;">
                <i class="fas fa-fw fa-table"></i>
                <span>LMS</span>
            </a>
        </li>
    <?php } ?>

    <!-- Divider -->
    <hr class="sidebar-divider" style="border-color: #d1d3e2;">

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="logout.php" style="font-family: 'Poppins', sans-serif;">
            <i class="fas fa-fw fa-power-off"></i>
            <span>Logout</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" style="border-color: #d1d3e2;">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle" style="background-color: #4e73df; border: none;"></button>
    </div>

</ul>
