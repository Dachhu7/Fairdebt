<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch user role from the database
$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

if (isset($_POST['addPayment'])) {
    // Sanitize input values
    $employeeName = mysqli_real_escape_string($con, $_POST['employeeName']);
    $month = mysqli_real_escape_string($con, $_POST['month']);
    $invoiceDate = mysqli_real_escape_string($con, $_POST['invoiceDate']);
    $vendorName = mysqli_real_escape_string($con, $_POST['vendorName']);
    $invoiceNumber = mysqli_real_escape_string($con, $_POST['invoiceNumber']);
    $taxableValues = mysqli_real_escape_string($con, $_POST['taxableValues']);
    $cgst = mysqli_real_escape_string($con, $_POST['cgst']);
    $sgst = mysqli_real_escape_string($con, $_POST['sgst']);
    $invoiceAmount = mysqli_real_escape_string($con, $_POST['invoiceAmount']);
    $tdsDeduct = mysqli_real_escape_string($con, $_POST['tdsDeduct']);
    $accountNumber = mysqli_real_escape_string($con, $_POST['accountNumber']);
    $ifscCode = mysqli_real_escape_string($con, $_POST['ifscCode']);
    $paymentBack = mysqli_real_escape_string($con, $_POST['paymentBack']);
    $paymentStatus = 'Pending'; // Default status
    $dateTime = date('Y-m-d H:i:s');
    $attachments = '';

    // File upload handling
    if (isset($_FILES['attachments']) && $_FILES['attachments']['error'] === 0) {
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
        $fileTmpPath = $_FILES['attachments']['tmp_name'];
        $fileName = $_FILES['attachments']['name'];
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        if (in_array(strtolower($fileType), $allowedFileTypes)) {
            $uploadDir = 'uploads/payments/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Use employee name in the file name to ensure uniqueness
            $newFileName = strtolower(str_replace(' ', '_', $employeeName)) . '_' . time() . '.' . $fileType;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $attachments = $uploadPath;
            } else {
                echo "<script>alert('File upload failed.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Only JPG, PNG, PDF, and DOCX are allowed.');</script>";
        }
    }

    // Insert data into the database
    $query = "INSERT INTO payments (
        Employee_Name, Month, Invoice_Date, Vendor_Name, Invoice_Number, 
        Taxable_Values, CGST, SGST, Invoice_Amount, TDS_Deduct, Attachments, 
        Account_Number, IFSC_Code, Payment_Status, Payment_Bank, DateTime
    ) VALUES (
        '$employeeName', '$month', '$invoiceDate', '$vendorName', '$invoiceNumber',
        '$taxableValues', '$cgst', '$sgst', '$invoiceAmount', '$tdsDeduct', 
        '$attachments', '$accountNumber', '$ifscCode', '$paymentStatus', '$paymentBack', '$dateTime'
    )";

    if (mysqli_query($con, $query)) {
        echo "<script>alert('Payment added successfully.');</script>";
        echo "<script>window.location.href='payments.php';</script>";
    } else {
        echo "<script>alert('Failed to add payment: " . mysqli_error($con) . "');</script>";
    }
}

// Update payment status
if (isset($_POST['updateStatus'])) {
    $paymentId = intval($_POST['paymentId']);
    $paymentStatus = mysqli_real_escape_string($con, $_POST['paymentStatus']);

    if (!empty($paymentStatus)) {
        $query = "UPDATE payments SET Payment_Status='$paymentStatus' WHERE id='$paymentId'";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Payment status updated successfully');</script>";
            echo "<script>window.location.href='payments.php';</script>";
        } else {
            echo "<script>alert('Failed to update payment status.');</script>";
        }
    } else {
        echo "<script>alert('Please select a valid status.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payment Details</title>
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
                    <h1 class="h3 mb-4 custom-heading">Payment Details</h1>                    <button class="btn btn btn-primary mb-4" data-toggle="modal" data-target="#addPaymentModal">Add Payment</button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Employee Name</th>
                                    <th>Month</th>
                                    <th>Invoice Date</th>
                                    <th>Vendor Name</th>
                                    <th>Invoice Number</th>
                                    <th>Taxable Values</th>
                                    <th>CGST</th>
                                    <th>SGST</th>
                                    <th>Invoice Amount</th>
                                    <th>TDS Deduct</th>
                                    <th>Attachments</th>
                                    <th>Account Number</th>
                                    <th>IFSC Code</th>
                                    <th>Payment Status</th>
                                    <th>Payment Bank</th>
                                    <th>DateTime</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM payments ORDER BY Payment_Date ASC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['Employee_Name']); ?></td>
                                        <td><?php echo htmlentities($row['Month']); ?></td>
                                        <td><?php echo htmlentities($row['Invoice_Date']); ?></td>
                                        <td><?php echo htmlentities($row['Vendor_Name']); ?></td>
                                        <td><?php echo htmlentities($row['Invoice_Number']); ?></td>
                                        <td><?php echo htmlentities($row['Taxable_Values']); ?></td>
                                        <td><?php echo htmlentities($row['CGST']); ?></td>
                                        <td><?php echo htmlentities($row['SGST']); ?></td>
                                        <td><?php echo htmlentities($row['Invoice_Amount']); ?></td>
                                        <td><?php echo htmlentities($row['TDS_Deduct']); ?></td>
                                        <td><?php echo htmlentities($row['Attachments']); ?></td>
                                        <td><?php echo htmlentities($row['Account_Number']); ?></td>
                                        <td><?php echo htmlentities($row['IFSC_Code']); ?></td>
                                        <td><?php echo htmlentities($row['Payment_Status']); ?></td>
                                        <td><?php echo htmlentities($row['Payment_Bank']); ?></td>
                                        <td><?php echo htmlentities($row['DateTime']); ?></td>
                                        <td>
                                            <!-- Change Payment Status Form -->
                                            <form method="POST" action="">
                                                <input type="hidden" name="paymentId" value="<?php echo $row['id']; ?>">
                                                <select name="paymentStatus" class="form-control" required>
                                                    <option value="Pending" <?php echo ($row['Payment_Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Approved" <?php echo ($row['Payment_Status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                                </select>
                                                <button type="submit" name="updateStatus" class="btn btn-info mt-2">Update</button>
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
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Payment</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <!-- All new form fields for the new data -->
                        <div class="form-group">
                            <label>Employee Name</label>
                            <input type="text" name="employeeName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Month</label>
                            <input type="text" name="month" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Invoice Date</label>
                            <input type="date" name="invoiceDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Vendor Name</label>
                            <input type="text" name="vendorName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Invoice Number</label>
                            <input type="text" name="invoiceNumber" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Taxable Values</label>
                            <input type="number" name="taxableValues" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>CGST</label>
                            <input type="number" name="cgst" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>SGST</label>
                            <input type="number" name="sgst" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Invoice Amount</label>
                            <input type="number" name="invoiceAmount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>TDS Deduct</label>
                            <input type="number" name="tdsDeduct" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Attachments</label>
                            <input type="file" name="attachments" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Account Number</label>
                            <input type="text" name="accountNumber" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>IFSC Code</label>
                            <input type="text" name="ifscCode" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Payment Bank</label>
                            <input type="text" name="paymentBack" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addPayment" class="btn btn-primary">Add Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
