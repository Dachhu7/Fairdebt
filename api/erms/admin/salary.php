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

// Fetch employee names from the employeedetail table
$query = "SELECT ID, CONCAT(EmpFname, ' ', EmpLName) AS employee_name FROM employeedetail";
$result = mysqli_query($con, $query);

// Handle adding salary
if (isset($_POST['addSalary'])) {
    $employeeName = mysqli_real_escape_string($con, $_POST['employee_name']);
    $salaryAmount = filter_var($_POST['salary_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $hra = filter_var($_POST['hra'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $conveyance = filter_var($_POST['conveyance'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $otherAllowances = filter_var($_POST['other_allowances'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $tds = filter_var($_POST['tds'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $providentFund = filter_var($_POST['provident_fund'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $esi = filter_var($_POST['esi'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $loan = filter_var($_POST['loan'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $paymentMethod = mysqli_real_escape_string($con, $_POST['payment_method']);
    $notes = !empty($_POST['notes']) ? mysqli_real_escape_string($con, $_POST['notes']) : NULL; // Ensure empty notes is set to NULL
    $paymentDate = mysqli_real_escape_string($con, $_POST['payment_date']); // Capture payment date

    // Calculate earnings and deductions
    $totalEarnings = $salaryAmount + $hra + $conveyance + $otherAllowances;
    $totalDeductions = $tds + $providentFund + $esi + $loan;
    $netSalary = $totalEarnings - $totalDeductions;

    // Insert into salary_payments table
    $query = "INSERT INTO salary_payments (employee_name, salary_amount, hra, conveyance, other_allowances, tds, provident_fund, esi, loan, payment_date, payment_method, notes, total_earnings, total_deductions, net_salary) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sddddddddsssddd", $employeeName, $salaryAmount, $hra, $conveyance, $otherAllowances, $tds, $providentFund, $esi, $loan, $paymentDate, $paymentMethod, $notes, $totalEarnings, $totalDeductions, $netSalary);

    if ($stmt->execute()) {
        echo "<script>alert('Salary added successfully');</script>";
        echo "<script>window.location.href='salary.php'</script>";
    } else {
        echo "<script>alert('Failed to add salary');</script>";
    }
}

// Handle updating salary status
if (isset($_POST['updateStatus'])) {
    $salaryId = intval($_POST['salaryId']);
    $salaryStatus = filter_var($_POST['salaryStatus'], FILTER_SANITIZE_STRING);

    if (!empty($salaryStatus)) {
        $query = "UPDATE salary_payments SET salary_status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $salaryStatus, $salaryId);
        if ($stmt->execute()) {
            echo "<script>alert('Salary status updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update salary status');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Salary Management</title>
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
                
                <!-- Salary Payment Details Section -->
                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 custom-heading">Salary Payment Details</h1>
                    
                    <!-- Add Salary Button -->
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addSalaryModal">Add Salary</button>

                    <!-- Salary Payment Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>Employee Name</th>
                                <th>Salary Amount</th>
                                <th>Payment Date</th>
                                <th>Payment Method</th>
                                <th>Notes</th>
                                <th>Salary Status</th>
                                <th>Actions</th>
                                <th>Generate Slip</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ret = $con->query("SELECT * FROM salary_payments ORDER BY payment_date DESC");
                            $cnt = 1;
                            while ($row = $ret->fetch_assoc()) {
                                // Determine badge class based on salary status
                                $badgeClass = '';
                                if ($row['salary_status'] == 'Approved') {
                                    $badgeClass = 'badge-success'; // Green for approved
                                } elseif ($row['salary_status'] == 'Pending') {
                                    $badgeClass = 'badge-warning'; // Yellow for pending
                                }
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlentities($row['employee_name']); ?></td>
                                    <td><?php echo htmlentities($row['salary_amount']); ?></td>
                                    <td><?php echo htmlentities($row['payment_date']); ?></td>
                                    <td><?php echo htmlentities($row['payment_method']); ?></td>
                                    <td><?php echo htmlentities($row['notes']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $badgeClass; ?>">
                                            <?php echo htmlentities($row['salary_status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" action="">
                                            <input type="hidden" name="salaryId" value="<?php echo $row['id']; ?>">
                                            <select name="salaryStatus" class="form-control" onchange="this.form.submit()">
                                                <option value="Pending" <?php echo $row['salary_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Approved" <?php echo $row['salary_status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                            </select>
                                            <input type="hidden" name="updateStatus" value="1">
                                        </form>
                                    </td>
                                    <td><a class="btn btn-sm btn-primary" href="salary-view.php?salaryId=<?php echo $row['id']; ?>">Generate Slip</a></td>
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

    <!-- Add Salary Modal -->
    <div class="modal fade" id="addSalaryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Salary</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Employee Name</label>
                            <select name="employee_name" class="form-control" required>
                                <option value="" disabled selected>Select Employee</option>
                                <?php
                                // Fetch employees again for the dropdown in the modal
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['employee_name'] . "'>" . htmlentities($row['employee_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Salary Amount</label>
                            <input type="number" name="salary_amount" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>HRA</label>
                            <input type="number" name="hra" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Conveyance</label>
                            <input type="number" name="conveyance" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Other Allowances</label>
                            <input type="number" name="other_allowances" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>TDS</label>
                            <input type="number" name="tds" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Provident Fund</label>
                            <input type="number" name="provident_fund" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>ESI</label>
                            <input type="number" name="esi" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Loan</label>
                            <input type="number" name="loan" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Cash">Cash</option>
                                <option value="Cash">Cheque</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addSalary" class="btn btn-primary">Add Salary</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>
</html>