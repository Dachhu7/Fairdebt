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

// Handle Adding Payroll Item
if (isset($_POST['addPayrollItem'])) {
    $itemName = mysqli_real_escape_string($con, $_POST['item_name']);
    $itemType = mysqli_real_escape_string($con, $_POST['item_type']);
    $itemAmount = filter_var($_POST['item_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $itemDescription = mysqli_real_escape_string($con, $_POST['item_description']);

    $query = "INSERT INTO payroll_items (name, type, amount, description) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssds", $itemName, $itemType, $itemAmount, $itemDescription);

    if ($stmt->execute()) {
        echo "<script>alert('Payroll item added successfully');</script>";
        echo "<script>window.location.href='payroll-items.php'</script>";
    } else {
        echo "<script>alert('Failed to add payroll item');</script>";
    }
}

// Handle Deleting Payroll Item
if (isset($_POST['deletePayrollItem'])) {
    $itemId = intval($_POST['item_id']);

    $query = "DELETE FROM payroll_items WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $itemId);

    if ($stmt->execute()) {
        echo "<script>alert('Payroll item deleted successfully');</script>";
        echo "<script>window.location.href='payroll-items.php'</script>";
    } else {
        echo "<script>alert('Failed to delete payroll item');</script>";
    }
}

// Fetch Payroll Items
$items = [
    'Addition' => [],
    'Deduction' => [],
    'Overtime' => [],
];

$ret = $con->query("SELECT * FROM payroll_items ORDER BY id DESC");
while ($row = $ret->fetch_assoc()) {
    $items[$row['type']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payroll Items Management</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
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

                <!-- Payroll Items Section -->
                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 custom-heading">Payroll Items Management</h1>

                    <!-- Add Payroll Item Button -->
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addPayrollItemModal">Add Payroll Item</button>

                    <!-- Tabs for Payroll Categories -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#additions">Additions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#deductions">Deductions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#overtime">Overtime</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content mt-4">
                        <!-- Additions Tab -->
                        <div id="additions" class="tab-pane fade show active">
                            <h4>Additions</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Item Name</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($items['Addition'] as $row) {
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo htmlentities($row['name']); ?></td>
                                            <td><?php echo htmlentities($row['amount']); ?></td>
                                            <td><?php echo htmlentities($row['description']); ?></td>
                                            <td>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="deletePayrollItem" class="btn btn-danger btn-sm">Delete</button>
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

                        <!-- Deductions Tab -->
                        <div id="deductions" class="tab-pane fade">
                            <h4>Deductions</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Item Name</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($items['Deduction'] as $row) {
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo htmlentities($row['name']); ?></td>
                                            <td><?php echo htmlentities($row['amount']); ?></td>
                                            <td><?php echo htmlentities($row['description']); ?></td>
                                            <td>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="deletePayrollItem" class="btn btn-danger btn-sm">Delete</button>
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

                        <!-- Overtime Tab -->
                        <div id="overtime" class="tab-pane fade">
                            <h4>Overtime</h4>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Item Name</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $cnt = 1;
                                    foreach ($items['Overtime'] as $row) {
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo htmlentities($row['name']); ?></td>
                                            <td><?php echo htmlentities($row['amount']); ?></td>
                                            <td><?php echo htmlentities($row['description']); ?></td>
                                            <td>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="deletePayrollItem" class="btn btn-danger btn-sm">Delete</button>
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
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Add Payroll Item Modal -->
    <div class="modal fade" id="addPayrollItemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payroll Item</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" name="item_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Item Type</label>
                            <select name="item_type" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="Addition">Addition</option>
                                <option value="Deduction">Deduction</option>
                                <option value="Overtime">Overtime</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="item_amount" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="item_description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addPayrollItem" class="btn btn-primary">Add Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
