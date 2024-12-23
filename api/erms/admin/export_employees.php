<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
require_once '../vendor/autoload.php'; // Include PhpSpreadsheet library

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;
}

if (isset($_GET['client_id'])) {
    $clientId = $_GET['client_id'];

    // Fetch employee data
    $query = mysqli_query($con, "SELECT * FROM employees WHERE client_id = '$clientId'");
    $employees = mysqli_fetch_all($query, MYSQLI_ASSOC);

    if (count($employees) > 0) {
        // Create a new spreadsheet
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Employee ID');
        $sheet->setCellValue('B1', 'First Name');
        $sheet->setCellValue('C1', 'Last Name');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Phone');
        $sheet->setCellValue('F1', 'Company');
        $sheet->setCellValue('G1', 'Address');
        $sheet->setCellValue('H1', 'Status');

        // Fill data rows
        $rowNum = 2;
        foreach ($employees as $employee) {
            $sheet->setCellValue('A' . $rowNum, $employee['id']);
            $sheet->setCellValue('B' . $rowNum, $employee['first_name']);
            $sheet->setCellValue('C' . $rowNum, $employee['last_name']);
            $sheet->setCellValue('D' . $rowNum, $employee['email']);
            $sheet->setCellValue('E' . $rowNum, $employee['phone']);
            $sheet->setCellValue('F' . $rowNum, $employee['company']);
            $sheet->setCellValue('G' . $rowNum, $employee['address']);
            $sheet->setCellValue('H' . $rowNum, $employee['status']);
            $rowNum++;
        }

        // Output file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="employees.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    } else {
        echo "<script>alert('No employees found for this client');</script>";
    }
} else {
    echo "<script>alert('Client ID not provided');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Employees</title>
</head>
<body>
    <!-- The content of this file will not display, as the page will trigger an Excel file download -->
</body>
</html>
