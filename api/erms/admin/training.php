<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (empty($_SESSION['aid'])) {
    header('location:logout.php');
    exit;
}

// Fetch user role from the database
$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

// Handle adding training
if (isset($_POST['addTraining'])) {
    $trainingType = mysqli_real_escape_string($con, $_POST['training_type']);
    $trainer = mysqli_real_escape_string($con, $_POST['trainer']);
    $timeDuration = mysqli_real_escape_string($con, $_POST['time_duration']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $description = mysqli_real_escape_string($con, $_POST['description']);

    $query = "INSERT INTO training (training_type, trainer, time_duration, status, description) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $trainingType, $trainer, $timeDuration, $status, $description);

    if ($stmt->execute()) {
        echo "<script>alert('Training added successfully');</script>";
        echo "<script>window.location.href='training.php'</script>";
    } else {
        echo "<script>alert('Failed to add training');</script>";
    }
}

// Handle adding employee
if (isset($_POST['addEmployee'])) {
    $trainingId = intval($_POST['training_id']);
    $employeeName = mysqli_real_escape_string($con, $_POST['employee_name']);

    // Check if employee name is empty
    if (empty($employeeName)) {
        echo "<script>alert('Please enter a valid employee name');</script>";
        exit;
    }

    // Insert employee details into view_employee table
    $query = "INSERT INTO view_employee (training_id, employee_name) 
              VALUES (?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("is", $trainingId, $employeeName);

    if ($stmt->execute()) {
        echo "<script>alert('Employee added successfully');</script>";
        echo "<script>window.location.href='training.php'</script>";
    } else {
        echo "<script>alert('Failed to add employee');</script>";
    }
}

// Handle updating employee details
if (isset($_POST['editEmployee'])) {
    $employeeId = intval($_POST['employee_id']);
    $employeeName = mysqli_real_escape_string($con, $_POST['employee_name']);
    $trainingId = intval($_POST['training_id']);

    $query = "UPDATE view_employee SET employee_name = ?, training_id = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sii", $employeeName, $trainingId, $employeeId);

    if ($stmt->execute()) {
        echo "<script>alert('Employee details updated successfully');</script>";
        echo "<script>window.location.href='training.php'</script>";
    } else {
        echo "<script>alert('Failed to update employee details');</script>";
    }
}

// Handle updating training status
if (isset($_POST['updateStatus'])) {
    $trainingId = intval($_POST['trainingId']);
    $trainingStatus = filter_var($_POST['trainingStatus'], FILTER_SANITIZE_STRING);

    if (!empty($trainingStatus)) {
        $query = "UPDATE training SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $trainingStatus, $trainingId);
        if ($stmt->execute()) {
            echo "<script>alert('Training status updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update training status');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Training Management</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .badge-active { background-color: #28a745; color: white; }
        .badge-inactive { background-color: #dc3545; color: white; }
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
        .btn-danger:hover, {
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
        .btn-primary:hover, .btn-info:hover, .btn-danger:hover, .btn-success:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary, .btn-info, .btn-danger, .btn-success {
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
                
                <!-- Training Details Section -->
                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 custom-heading">Training Program Details</h1>
                    
                    <!-- Add Training Button -->
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addTrainingModal">Add Training</button>

                    <!-- Add Employee Button -->
                    <button class="btn btn-success mb-4" data-toggle="modal" data-target="#addEmployeeModal">Add Employee</button>

                    <!-- Training Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>Training Type</th>
                                <th>Trainer</th>
                                <th>Time Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ret = $con->query("SELECT * FROM training ORDER BY time_duration DESC");
                            $cnt = 1;
                            while ($row = $ret->fetch_assoc()) {
                                $statusClass = ($row['status'] == 'Active') ? 'badge-active' : 'badge-inactive';
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlentities($row['training_type']); ?></td>
                                    <td><?php echo htmlentities($row['trainer']); ?></td>
                                    <td><?php echo htmlentities($row['time_duration']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo htmlentities($row['status']); ?></span>
                                    </td>
                                    <td>
                                        <!-- View Employees Button -->
                                        <a class="btn btn-sm btn-primary" href="view_employees.php?trainingId=<?php echo $row['id']; ?>">View Employees</a>
                                        <!-- Edit Employee Button -->
                                        <!-- <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editEmployeeModal" data-id="<?php echo $row['id']; ?>">Edit Employee</button> -->
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
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Add Training Modal -->
    <div class="modal fade" id="addTrainingModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Training</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- Training Type Dropdown -->
                        <div class="form-group">
                            <label>Training Type</label>
                            <select name="training_type" class="form-control" required>
                                <option value="PayRupik">PayRupik</option>
                                <option value="Hathway">Hathway</option>
                                <option value="Stashfin">Stashfin</option>
                                <option value="Navi">Navi</option>
                                <option value="Rupee Redee">Rupee Redee</option>
                            </select>
                        </div>

                        <!-- Trainer Dropdown -->
                        <div class="form-group">
                            <label>Trainer</label>
                            <select name="trainer" class="form-control" required>
                                <option value="Buddhha">Buddhha</option>
                                <option value="Ranubala">Ranubala</option>
                            </select>
                        </div>

                        <!-- Time Duration Input -->
                        <div class="form-group">
                            <label>Time Duration</label>
                            <input type="text" name="time_duration" class="form-control" placeholder="Enter the duration of the training (e.g., 3 days, 4 hours)" required>
                        </div>

                        <!-- Description Input -->
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" placeholder="Enter the description of the training" required></textarea>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="addTraining" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Employee</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- Employee Name Input -->
                        <div class="form-group">
                            <label>Employee Name</label>
                            <input type="text" name="employee_name" class="form-control" required>
                        </div>

                        <!-- Training Dropdown -->
                        <div class="form-group">
                            <label>Training</label>
                            <select name="training_id" class="form-control" required>
                                <?php
                                $trainingQuery = $con->query("SELECT id, training_type FROM training ORDER BY time_duration DESC");
                                while ($training = $trainingQuery->fetch_assoc()) {
                                    echo "<option value='{$training['id']}'>{$training['training_type']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="addEmployee" class="btn btn-primary">Add Employee</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <!-- <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Employee</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Employee Name</label>
                            <input type="text" name="employee_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Training</label>
                            <select name="training_id" class="form-control" required>
                                <?php
                                $trainingQuery = $con->query("SELECT id, training_type FROM training ORDER BY time_duration DESC");
                                while ($training = $trainingQuery->fetch_assoc()) {
                                    echo "<option value='{$training['id']}'>{$training['training_type']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <input type="hidden" name="employee_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="editEmployee" class="btn btn-primary">Update Employee</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div> -->
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        // Populate the Edit Employee modal with employee data
        $('#editEmployeeModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var employeeId = button.data('id');
            var modal = $(this);

            $.ajax({
                url: 'fetch_employees.php',  // Use an AJAX file to fetch employee details
                method: 'GET',
                data: { id: employeeId },
                success: function(response) {
                    var employee = JSON.parse(response);
                    modal.find('[name="employee_name"]').val(employee.employee_name);
                    modal.find('[name="training_id"]').val(employee.training_id);
                    modal.find('[name="employee_id"]').val(employee.id);
                }
            });
        });
    </script>
</body>
</html>
