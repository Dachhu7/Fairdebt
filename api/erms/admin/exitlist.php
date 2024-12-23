<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (empty($_SESSION['aid'])) {
    header('location:logout.php');
    exit;
}

$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

// Handle adding exit record
if (isset($_POST['addExit'])) {
    $employee = mysqli_real_escape_string($con, $_POST['employee']);
    $exitDate = mysqli_real_escape_string($con, $_POST['exit_date']);
    $reason = mysqli_real_escape_string($con, $_POST['reason']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $query = "INSERT INTO exit_list (employee, exit_date, reason, status) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssss", $employee, $exitDate, $reason, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Exit record added successfully');</script>";
        echo "<script>window.location.href='exitlist.php'</script>";
    } else {
        echo "<script>alert('Failed to add exit record');</script>";
    }
}

// Handle updating exit status
if (isset($_POST['updateStatus'])) {
    $exitId = intval($_POST['exitId']);
    $exitStatus = filter_var($_POST['exitStatus'], FILTER_SANITIZE_STRING);

    if (!empty($exitStatus)) {
        $query = "UPDATE exit_list SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $exitStatus, $exitId);
        if ($stmt->execute()) {
            echo "<script>alert('Exit status updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update exit status');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Exit List Management</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Arial', sans-serif;
        }

        .custom-heading {
            font-size: 2rem;
            font-weight: bold;
            color: #4e73df;
            text-align: center;
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

        .btn-primary, .btn-info, .btn-danger {
            border-radius: 5px;
            padding: 5px 10px;
        }

        .btn-group .btn {
            margin: 2px;
        }

        .badge-approved {
            background-color: #28a745;
            color: white;
        }

        .badge-pending {
            background-color: #ffc107;
            color: white;
        }

        .badge-rejected {
            background-color: #dc3545;
            color: white;
        }

        .modal-header {
            background-color: #4e73df;
            color: white;
        }

        .form-control {
            border-radius: 10px;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
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
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
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
                <div class="container-fluid mt-4">
                    <h1 class="custom-heading">Employee Exit List</h1>
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addExitModal">Add Exit Record</button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Employee</th>
                                    <th>Exit Date</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = $con->query("SELECT * FROM exit_list ORDER BY exit_date DESC");
                                $cnt = 1;
                                while ($row = $ret->fetch_assoc()) {
                                    $statusClass = $row['status'] == 'Approved' ? 'badge-approved' : ($row['status'] == 'Pending' ? 'badge-pending' : 'badge-rejected');
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['employee']); ?></td>
                                        <td><?php echo htmlentities($row['exit_date']); ?></td>
                                        <td><?php echo htmlentities($row['reason']); ?></td>
                                        <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlentities($row['status']); ?></span></td>
                                        <td>
                                            <form method="POST" action="">
                                                <input type="hidden" name="exitId" value="<?php echo $row['id']; ?>">
                                                <select name="exitStatus" class="form-control" onchange="this.form.submit()">
                                                    <option value="Approved" <?php echo $row['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                                    <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Rejected" <?php echo $row['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                                </select>
                                                <input type="hidden" name="updateStatus" value="1">
                                            </form>
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
        <div class="modal fade" id="addExitModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Exit Record</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Employee</label>
                                <input type="text" name="employee" class="form-control" placeholder="Enter the name of the employee" required>
                            </div>
                            <div class="form-group">
                                <label>Exit Date</label>
                                <input type="date" name="exit_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Reason</label>
                                <textarea name="reason" class="form-control" placeholder="Enter the reason for exit" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="Approved">Approved</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="addExit" class="btn btn-primary">Add Exit Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
