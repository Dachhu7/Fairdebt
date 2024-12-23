<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;
}

$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

// Add a new designation
if (isset($_POST['addDesignation'])) {
    $designationName = mysqli_real_escape_string($con, $_POST['designationName']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $dateTime = date('Y-m-d');

    if (!empty($designationName) && !empty($department)) {
        $query = mysqli_query($con, "INSERT INTO designations (Designation, Department, Date) VALUES ('$designationName', '$department', '$dateTime')");
        if ($query) {
            echo "<script>alert('Designation added successfully');</script>";
            echo "<script>window.location.href='designations.php'</script>";
        } else {
            echo "<script>alert('Failed to add designation');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the required fields');</script>";
    }
}

// Delete a designation
if (isset($_GET['delid'])) {
    $did = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM designations WHERE id='$did'");
    if ($query) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location.href='designations.php'</script>";
    } else {
        echo "<script>alert('Failed to delete record');</script>";
    }
}

// Update an existing designation
if (isset($_POST['updateDesignation'])) {
    $designationId = intval($_POST['designationId']);
    $designationName = mysqli_real_escape_string($con, $_POST['editDesignationName']);
    $department = mysqli_real_escape_string($con, $_POST['editDepartment']);

    if (!empty($designationName) && !empty($department)) {
        $query = mysqli_query($con, "UPDATE designations SET Designation='$designationName', Department='$department' WHERE id='$designationId'");
        if ($query) {
            echo "<script>alert('Designation updated successfully');</script>";
            echo "<script>window.location.href='designations.php'</script>";
        } else {
            echo "<script>alert('Failed to update designation');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the required fields');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Designation Details</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
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
        .modal-header {
            background-color: #4e73df;
            color: white;
        }
        .btn-primary:hover, .btn-info:hover, .btn-danger:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary, .btn-info, .btn-danger {
            border-radius: 20px;
        }
    </style>
</head>
<body id="page-top">
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
        <div id="content-wrapper" class="d-flex flex-column">
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
                <div class="container-fluid">
                    <h1 class="h3 mb-4 custom-heading">Designation Details</h1>
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addDesignationModal">Add Designation</button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM designations ORDER BY Date ASC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['Designation']); ?></td>
                                        <td><?php echo htmlentities($row['Department']); ?></td>
                                        <td><?php echo htmlentities($row['Date']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-btn" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-name="<?php echo htmlentities($row['Designation']); ?>" 
                                                data-department="<?php echo htmlentities($row['Department']); ?>" 
                                                data-toggle="modal" data-target="#editDesignationModal">Edit</button>
                                            <a href="designations.php?delid=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this record?');">Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                    $cnt++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Add Designation Modal -->
    <div class="modal fade" id="addDesignationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Designation</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Designation Name</label>
                            <input type="text" name="designationName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php
                                $deptQuery = mysqli_query($con, "SELECT * FROM departments");
                                while ($dept = mysqli_fetch_assoc($deptQuery)) {
                                    echo "<option value='" . $dept['Department'] . "'>" . $dept['Department'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addDesignation" class="btn btn-primary">Add Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Designation Modal -->
    <div class="modal fade" id="editDesignationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Designation</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="designationId" id="editDesignationId">
                        <div class="form-group">
                            <label>Designation Name</label>
                            <input type="text" name="editDesignationName" id="editDesignationName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="editDepartment" id="editDepartment" class="form-control" required>
                                <option value="">Select Department</option>
                                <?php
                                // Fetching departments for the edit modal
                                $deptQuery = mysqli_query($con, "SELECT * FROM departments");
                                while ($dept = mysqli_fetch_assoc($deptQuery)) {
                                    echo "<option value='" . $dept['Department'] . "'>" . $dept['Department'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="updateDesignation" class="btn btn-primary">Update Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JS, jQuery, Bootstrap -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        $(document).on('click', '.edit-btn', function () {
            var designationId = $(this).data('id');
            var designationName = $(this).data('name');
            var department = $(this).data('department');

            $('#editDesignationId').val(designationId);
            $('#editDesignationName').val(designationName);
            $('#editDepartment').val(department);
        });
    </script>
</body>
</html>
