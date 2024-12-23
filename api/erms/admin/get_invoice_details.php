<?php
include('includes/dbconnection.php');

if (isset($_GET['id'])) {
    $invoiceId = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM invoices WHERE id = '$invoiceId'");
    $invoice = mysqli_fetch_assoc($query);
    
    if ($invoice) {
        echo "<h2>Invoice #{$invoice['Invoice_Number']}</h2>";
        echo "<table><tr><th>Invoice Date</th><td>{$invoice['Invoice_Date']}</td></tr>";
        echo "<tr><th>Customer Name</th><td>{$invoice['Customer_Name']}</td></tr>";
        echo "<tr><th>Amount</th><td>{$invoice['Amount']}</td></tr></table>";
    }
}
?>
