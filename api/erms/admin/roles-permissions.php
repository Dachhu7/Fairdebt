<?php
session_start();
include('includes/dbconnection.php');

// Ensure session variable exists
if (!isset($_SESSION['aid'])) {
    echo "<script>alert('Admin ID is not set in the session. Please log in again.');</script>";
    header('Location: login.php');
    exit();
}

// Sidebar menu items
$menuItems = [
    "Dashboard" => "welcome.php",
    "My Exp" => "myexp.php",
    "Edit My Exp" => "editmyexp.php",
    "My Education" => "myeducation.php",
    "Edit My Education" => "editmyeducation.php",
    "LMS" => "apply_leave.php"
];

// Fetch current permissions
$permissions = [];
$query = "SELECT * FROM user_permissions";
$result = $con->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $permissions[$row['role']][$row['menu_item']] = $row['has_access'];
    }
} else {
    echo "<script>alert('Failed to fetch permissions from the database!');</script>";
}

// Handle form submission for updating permissions
if (isset($_POST['updatePermissions'])) {
    foreach ($_POST['permissions'] as $role => $menuAccess) {
        foreach ($menuItems as $menuItem => $url) {
            $hasAccess = isset($menuAccess[$menuItem]) ? 1 : 0;

            // Check if permission record exists
            $checkQuery = "SELECT id FROM user_permissions WHERE role = ? AND menu_item = ?";
            $stmt = $con->prepare($checkQuery);
            $stmt->bind_param("ss", $role, $menuItem);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Update existing permission
                $updateQuery = "UPDATE user_permissions SET has_access = ? WHERE role = ? AND menu_item = ?";
                $updateStmt = $con->prepare($updateQuery);
                $updateStmt->bind_param("iss", $hasAccess, $role, $menuItem);
                $updateStmt->execute();
            } else {
                // Insert new permission
                $insertQuery = "INSERT INTO user_permissions (role, menu_item, has_access) VALUES (?, ?, ?)";
                $insertStmt = $con->prepare($insertQuery);
                $insertStmt->bind_param("ssi", $role, $menuItem, $hasAccess);
                $insertStmt->execute();
            }
        }
    }

    echo "<script>alert('Permissions updated successfully!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Manage Permissions</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
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
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table.table {
            border: 1px solid #dee2e6;
        }

        table.table thead {
            background-color: #4e73df;
            color: white;
        }

        table.table th, 
        table.table td {
            text-align: center;
            vertical-align: middle;
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
        .modal-header {
            background-color: #4e73df;
            color: white;
        }
        .btn-primary:hover, .btn-info:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary, .btn-info {
            border-radius: 20px;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <?php include_once('includes/header.php'); ?>

                <!-- Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 custom-heading">Manage Permissions</h1>
                    <form method="POST" action="">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Role</th>
                                    <?php foreach ($menuItems as $menuItem => $url) { ?>
                                        <th><?php echo htmlspecialchars($menuItem); ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Define roles
                                $roles = ["Agent", "Collection Executive"];
                                foreach ($roles as $role) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($role); ?></td>
                                        <?php foreach ($menuItems as $menuItem => $url) { ?>
                                            <td>
                                                <input 
                                                    type="checkbox" 
                                                    name="permissions[<?php echo htmlspecialchars($role); ?>][<?php echo htmlspecialchars($menuItem); ?>]" 
                                                    <?php echo isset($permissions[$role][$menuItem]) && $permissions[$role][$menuItem] ? 'checked' : ''; ?>
                                                />
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <button type="submit" name="updatePermissions" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
