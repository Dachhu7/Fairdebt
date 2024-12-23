<?php
include('includes/dbconnection.php');

// Check if invoice ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('No invoice ID provided!'); window.close();</script>";
    exit;
}

$invoiceId = intval($_GET['id']);

// Prepare and execute the query
$stmt = $con->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->bind_param("i", $invoiceId);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

// Check if invoice exists
if (!$invoice) {
    echo "<script>alert('Invoice not found!'); window.close();</script>";
    exit;
}

// Fallback values for undefined keys
$invoiceNumber = !empty($invoice['Invoice_Number']) ? htmlentities($invoice['Invoice_Number']) : 'N/A';
$invoiceDate = !empty($invoice['Invoice_Date']) ? htmlentities(date("d-m-Y", strtotime($invoice['Invoice_Date']))) : 'N/A';
$customerName = !empty($invoice['Customer_Name']) ? htmlentities($invoice['Customer_Name']) : 'N/A';
$customerContact = !empty($invoice['Customer_Contact']) ? htmlentities($invoice['Customer_Contact']) : 'N/A';
$gstNo = !empty($invoice['GST_No']) ? htmlentities($invoice['GST_No']) : 'N/A';
$vendorName = !empty($invoice['Vendor_Name']) ? htmlentities($invoice['Vendor_Name']) : 'N/A';
$hsnCode = !empty($invoice['HSN_Code']) ? htmlentities($invoice['HSN_Code']) : 'N/A';
$items = !empty($invoice['Invoice_Items']) ? json_decode($invoice['Invoice_Items'], true) : [];
$subtotal = !empty($invoice['Amount']) ? floatval($invoice['Amount']) : 0.00;

// Calculate taxes and totals
$cgst = $subtotal * 0.09; // 9% CGST
$sgst = $subtotal * 0.09; // 9% SGST
$grandTotal = $subtotal + $cgst + $sgst;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Print Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .invoice-header, .invoice-footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-header img {
            max-width: 150px;
        }
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .invoice-details, .invoice-items {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .invoice-details th, .invoice-details td,
        .invoice-items th, .invoice-items td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .invoice-details th {
            background-color: #f4f4f4;
        }
        .invoice-items th {
            background-color: #007bff;
            color: #fff;
        }
        .text-right {
            text-align: right;
        }
        .btn-print {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <img src="front/images/company_logo.jpg" alt="Company Logo">
            <div>Fairdebt Solutions Private Limited</div>
            <div>HSR Layout, Bangalore</div>
            <div>Email: contact@mycompany.com</div>
        </div>

        <div class="invoice-title text-center">Invoice</div>

        <table class="invoice-details">
            <tr>
                <th>Invoice Number</th>
                <td><?php echo $invoiceNumber; ?></td>
                <th>Invoice Date</th>
                <td><?php echo $invoiceDate; ?></td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td colspan="3"><?php echo $customerName; ?></td>
            </tr>
            <tr>
                <th>Customer Contact</th>
                <td colspan="3"><?php echo $customerContact; ?></td>
            </tr>
            <tr>
                <th>GST No</th>
                <td colspan="3"><?php echo $gstNo; ?></td>
            </tr>
            <tr>
                <th>Vendor Name</th>
                <td colspan="3"><?php echo $vendorName; ?></td>
            </tr>
            <tr>
                <th>HSN Code</th>
                <td colspan="3"><?php echo $hsnCode; ?></td>
            </tr>
        </table>

        <table class="invoice-items">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)) {
                    $i = 1;
                    foreach ($items as $item) {
                        echo "<tr>
                            <td>{$i}</td>
                            <td>" . htmlentities($item['description'] ?? 'N/A') . "</td>
                            <td class='text-center'>" . htmlentities($item['quantity'] ?? '0') . "</td>
                            <td class='text-right'>" . htmlentities($item['unit_price'] ?? '0.00') . "</td>
                            <td class='text-right'>" . htmlentities($item['total'] ?? '0.00') . "</td>
                        </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No items found.</td></tr>";
                } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right">Subtotal</td>
                    <td class="text-right"><?php echo number_format($subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">CGST (9%)</td>
                    <td class="text-right"><?php echo number_format($cgst, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">SGST (9%)</td>
                    <td class="text-right"><?php echo number_format($sgst, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right">Grand Total</td>
                    <td class="text-right"><?php echo number_format($grandTotal, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="invoice-footer">
            <p>Thank you for your business!</p>
        </div>

        <button class="btn-print" onclick="window.print()">Print Invoice</button>
    </div>
</body>
</html>
