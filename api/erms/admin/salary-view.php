<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if salary ID is provided
if (!isset($_GET['salaryId']) || empty($_GET['salaryId'])) {
    echo "<script>alert('No salary ID provided!'); window.location.href='salary.php';</script>";
    exit;
}

$salaryId = intval($_GET['salaryId']);

// Fetch salary and employee details
$query = mysqli_query(
    $con,
    "SELECT sp.*, ed.EmpCode AS employee_id, ed.EmpJoingdate AS joining_date 
     FROM salary_payments sp 
     LEFT JOIN employeedetail ed ON sp.employee_name = CONCAT(ed.EmpFname, ' ', ed.EmpLName) 
     WHERE sp.id = '$salaryId'"
);
$salary = mysqli_fetch_assoc($query);

if (!$salary) {
    echo "<script>alert('Salary details not found!'); window.location.href='salary.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Salary Slip</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .sidebar { background: #4e73df; }
        .sidebar .nav-item .nav-link { color: white; }
        .sidebar .nav-item .nav-link.active { background-color: #2e59d9; }
        .content-wrapper { padding: 20px; }
        .payslip-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .payslip-title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #ddd !important;
        }
        .net-salary {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
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
                <div class="container-fluid mt-4 content-wrapper">
                    <div class="payslip-container">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="front/images/company_logo.jpg" alt="Company Logo" style="max-width: 150px;">
                                <ul class="list-unstyled mt-2">
                                    <li>Fairdebt Solutions Private Limited</li>
                                    <li>HSR Layout, Bangalore</li>
                                    <li>Email: contact@mycompany.com</li>
                                </ul>
                            </div>
                            <div class="col-md-6 text-right">
                                <h3 class="text-uppercase">Payslip #<?php echo htmlentities($salary['id']); ?></h3>
                                <p>Salary Month: <?php echo date("F, Y", strtotime($salary['payment_date'])); ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="payslip-title">Salary Slip for <?php echo htmlentities($salary['employee_name']); ?></div>
                        
                        <!-- Employee Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>Name:</strong> <?php echo htmlentities($salary['employee_name']); ?></li>
                                    <li><strong>Employee ID:</strong> <?php echo htmlentities($salary['employee_id']); ?></li>
                                    <li><strong>Joining Date:</strong> <?php echo htmlentities($salary['joining_date']); ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>Payment Method:</strong> <?php echo htmlentities($salary['payment_method']); ?></li>
                                    <li><strong>Status:</strong> <?php echo htmlentities($salary['salary_status']); ?></li>
                                </ul>
                            </div>
                        </div>

                        <!-- Earnings and Deductions -->
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Earnings</h5>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Basic Salary</td>
                                            <td class="text-right"><?php echo htmlentities($salary['salary_amount']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>HRA</td>
                                            <td class="text-right"><?php echo htmlentities($salary['hra']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Conveyance</td>
                                            <td class="text-right"><?php echo htmlentities($salary['conveyance']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Other Allowances</td>
                                            <td class="text-right"><?php echo htmlentities($salary['other_allowances']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Earnings</strong></td>
                                            <td class="text-right"><strong><?php echo htmlentities($salary['total_earnings']); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Deductions</h5>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>TDS</td>
                                            <td class="text-right"><?php echo htmlentities($salary['tds']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Provident Fund</td>
                                            <td class="text-right"><?php echo htmlentities($salary['provident_fund']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>ESI</td>
                                            <td class="text-right"><?php echo htmlentities($salary['esi']); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Loan</td>
                                            <td class="text-right"><?php echo htmlentities($salary['loan']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Deductions</strong></td>
                                            <td class="text-right"><strong><?php echo htmlentities($salary['total_deductions']); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Net Salary -->
                        <div class="row">
                            <div class="col-12 text-right">
                                <p class="net-salary">Net Salary: ₹<?php echo htmlentities($salary['net_salary']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Scroll to Top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
</body>
</html>
