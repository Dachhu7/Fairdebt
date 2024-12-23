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

// Add a new invoice
if (isset($_POST['addInvoice'])) {
    $invoiceNumber = mysqli_real_escape_string($con, $_POST['invoiceNumber']);
    $invoiceDate = mysqli_real_escape_string($con, $_POST['invoiceDate']);
    $customerName = mysqli_real_escape_string($con, $_POST['customerName']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $gstNo = mysqli_real_escape_string($con, $_POST['gstNo']);
    $vendorName = mysqli_real_escape_string($con, $_POST['vendorName']);
    $hsnCode = mysqli_real_escape_string($con, $_POST['hsnCode']);
    $dateTime = date('Y-m-d H:i:s');

    if (!empty($invoiceNumber) && !empty($invoiceDate) && !empty($customerName) && !empty($amount) && !empty($gstNo) && !empty($vendorName) && !empty($hsnCode)) {
        $query = mysqli_query($con, "INSERT INTO invoices (Invoice_Number, Invoice_Date, Customer_Name, Amount, GST_No, Vendor_Name, HSN_Code, DateTime) 
            VALUES ('$invoiceNumber', '$invoiceDate', '$customerName', '$amount', '$gstNo', '$vendorName', '$hsnCode', '$dateTime')");
        if ($query) {
            echo "<script>alert('Invoice added successfully');</script>";
            echo "<script>window.location.href='invoices.php'</script>";
        } else {
            echo "<script>alert('Failed to add invoice');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the required fields');</script>";
    }
}

// Delete an invoice
if (isset($_GET['delid'])) {
    $invoiceId = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM invoices WHERE id='$invoiceId'");
    if ($query) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location.href='invoices.php'</script>";
    } else {
        echo "<script>alert('Failed to delete record');</script>";
    }
}

// Update an existing invoice
if (isset($_POST['updateInvoice'])) {
    $invoiceId = intval($_POST['invoiceId']);
    $invoiceNumber = mysqli_real_escape_string($con, $_POST['editInvoiceNumber']);
    $invoiceDate = mysqli_real_escape_string($con, $_POST['editInvoiceDate']);
    $customerName = mysqli_real_escape_string($con, $_POST['editCustomerName']);
    $amount = mysqli_real_escape_string($con, $_POST['editAmount']);
    $gstNo = mysqli_real_escape_string($con, $_POST['editGSTNo']);
    $vendorName = mysqli_real_escape_string($con, $_POST['editVendorName']);
    $hsnCode = mysqli_real_escape_string($con, $_POST['editHSNCode']);

    if (!empty($invoiceNumber) && !empty($invoiceDate) && !empty($customerName) && !empty($amount) && !empty($gstNo) && !empty($vendorName) && !empty($hsnCode)) {
        $query = mysqli_query($con, "UPDATE invoices SET Invoice_Number='$invoiceNumber', Invoice_Date='$invoiceDate', Customer_Name='$customerName', Amount='$amount', GST_No='$gstNo', Vendor_Name='$vendorName', HSN_Code='$hsnCode' WHERE id='$invoiceId'");
        if ($query) {
            echo "<script>alert('Invoice updated successfully');</script>";
            echo "<script>window.location.href='invoices.php'</script>";
        } else {
            echo "<script>alert('Failed to update invoice');</script>";
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
    <title>Invoice Details</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .printable {
            margin: 0 auto;
            width: 80%;
            padding: 20px;
            border: 1px solid #000;
        }
        .action-buttons {
            display: flex;
            justify-content: space-around;
        }
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
                    <h1 class="h3 mb-4 custom-heading">Invoice Details</h1>
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addInvoiceModal">Add Invoice</button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Invoice Number</th>
                                    <th>Invoice Date</th>
                                    <th>Customer Name</th>
                                    <th>Amount</th>
                                    <th>GST No</th>
                                    <th>Vendor Name</th>
                                    <th>HSN Code</th>
                                    <th>DateTime</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM invoices ORDER BY Invoice_Date ASC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['Invoice_Number']); ?></td>
                                        <td><?php echo htmlentities($row['Invoice_Date']); ?></td>
                                        <td><?php echo htmlentities($row['Customer_Name']); ?></td>
                                        <td><?php echo htmlentities($row['Amount']); ?></td>
                                        <td><?php echo htmlentities($row['GST_No']); ?></td>
                                        <td><?php echo htmlentities($row['Vendor_Name']); ?></td>
                                        <td><?php echo htmlentities($row['HSN_Code']); ?></td>
                                        <td><?php echo htmlentities($row['DateTime']); ?></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-sm btn-info edit-btn" 
                                                    data-id="<?php echo $row['id']; ?>" 
                                                    data-number="<?php echo htmlentities($row['Invoice_Number']); ?>" 
                                                    data-date="<?php echo htmlentities($row['Invoice_Date']); ?>"
                                                    data-customer="<?php echo htmlentities($row['Customer_Name']); ?>"
                                                    data-amount="<?php echo htmlentities($row['Amount']); ?>"
                                                    data-gst="<?php echo htmlentities($row['GST_No']); ?>"
                                                    data-vendor="<?php echo htmlentities($row['Vendor_Name']); ?>"
                                                    data-hsn="<?php echo htmlentities($row['HSN_Code']); ?>"
                                                    data-toggle="modal" data-target="#editInvoiceModal">Edit</button>
                                                  
                                                <a href="invoices.php?delid=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this record?');">Delete</a>
                                                
                                                <a href="print_invoice.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Print</a>
                                            </div>
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

    <!-- Add Invoice Modal -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="invoiceNumber">Invoice Number:</label>
                            <input type="text" class="form-control" id="invoiceNumber" name="invoiceNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="invoiceDate">Invoice Date:</label>
                            <input type="date" class="form-control" id="invoiceDate" name="invoiceDate" required>
                        </div>
                        <div class="form-group">
                            <label for="customerName">Customer Name:</label>
                            <input type="text" class="form-control" id="customerName" name="customerName" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="text" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="gstNo">GST No:</label>
                            <input type="text" class="form-control" id="gstNo" name="gstNo" required>
                        </div>
                        <div class="form-group">
                            <label for="vendorName">Vendor Name:</label>
                            <input type="text" class="form-control" id="vendorName" name="vendorName" required>
                        </div>
                        <div class="form-group">
                            <label for="hsnCode">HSN Code:</label>
                            <input type="text" class="form-control" id="hsnCode" name="hsnCode" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="addInvoice" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Invoice Modal -->
    <div class="modal fade" id="editInvoiceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <input type="hidden" id="invoiceId" name="invoiceId">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="editInvoiceNumber">Invoice Number:</label>
                            <input type="text" class="form-control" id="editInvoiceNumber" name="editInvoiceNumber" required>
                        </div>
                        <div class="form-group">
                            <label for="editInvoiceDate">Invoice Date:</label>
                            <input type="date" class="form-control" id="editInvoiceDate" name="editInvoiceDate" required>
                        </div>
                        <div class="form-group">
                            <label for="editCustomerName">Customer Name:</label>
                            <input type="text" class="form-control" id="editCustomerName" name="editCustomerName" required>
                        </div>
                        <div class="form-group">
                            <label for="editAmount">Amount:</label>
                            <input type="text" class="form-control" id="editAmount" name="editAmount" required>
                        </div>
                        <div class="form-group">
                            <label for="editGSTNo">GST No:</label>
                            <input type="text" class="form-control" id="editGSTNo" name="editGSTNo" required>
                        </div>
                        <div class="form-group">
                            <label for="editVendorName">Vendor Name:</label>
                            <input type="text" class="form-control" id="editVendorName" name="editVendorName" required>
                        </div>
                        <div class="form-group">
                            <label for="editHSNCode">HSN Code:</label>
                            <input type="text" class="form-control" id="editHSNCode" name="editHSNCode" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="updateInvoice" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script>
        // Edit Button Script
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var number = $(this).data('number');
            var date = $(this).data('date');
            var customer = $(this).data('customer');
            var amount = $(this).data('amount');
            var gst = $(this).data('gst');
            var vendor = $(this).data('vendor');
            var hsn = $(this).data('hsn');
            
            $('#invoiceId').val(id);
            $('#editInvoiceNumber').val(number);
            $('#editInvoiceDate').val(date);
            $('#editCustomerName').val(customer);
            $('#editAmount').val(amount);
            $('#editGSTNo').val(gst);
            $('#editVendorName').val(vendor);
            $('#editHSNCode').val(hsn);
        });
    </script>
</body>
</html>
