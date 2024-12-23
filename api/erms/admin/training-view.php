<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Redirect to logout if no session is active
if (empty($_SESSION['aid'])) {
    header('location:logout.php');
    exit;
}

// Fetch training details for the current trainingId from the URL
if (isset($_GET['trainingId'])) {
    $trainingId = intval($_GET['trainingId']);
} else {
    echo "<script>alert('No training ID provided.'); window.location.href='training.php';</script>";
    exit;
}

// Fetch training details from the `training` table
$trainingQuery = "SELECT * FROM training WHERE id = ?";
$stmt = $con->prepare($trainingQuery);
$stmt->bind_param("i", $trainingId);
$stmt->execute();
$trainingResult = $stmt->get_result();
$trainingDetails = $trainingResult->fetch_assoc();

// Fetch employees associated with this training
$employeeQuery = "SELECT * FROM view_employee WHERE training_id = ? ORDER BY employee_name ASC";
$employeeStmt = $con->prepare($employeeQuery);
$employeeStmt->bind_param("i", $trainingId);
$employeeStmt->execute();
$employeeResult = $employeeStmt->get_result();

// Update employee name if the form is submitted
if (isset($_POST['editEmployee'])) {
    $employeeId = intval($_POST['employee_id']);
    $employeeName = $_POST['employee_name'];
    $updateQuery = "UPDATE view_employee SET employee_name = ? WHERE id = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("si", $employeeName, $employeeId);
    $updateStmt->execute();
    echo "<script>alert('Employee name updated successfully.'); window.location.href='view_employees.php?trainingId={$trainingId}';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>View Employees - Training Management</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .badge-active { background-color: #28a745; color: white; }
        .badge-inactive { background-color: #dc3545; color: white; }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include_once('includes/header.php'); ?>

                <!-- Training Employee Details Section -->
                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 text-gray-800">Employees for Training: <?php echo htmlentities($trainingDetails['training_type']); ?> by <?php echo htmlentities($trainingDetails['trainer']); ?></h1>

                    <!-- Back to Training List Button -->
                    <a href="training.php" class="btn btn-secondary mb-4">Back to Training List</a>

                    <!-- Employees Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>Employee Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cnt = 1;
                            if ($employeeResult->num_rows > 0) {
                                while ($employee = $employeeResult->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($employee['employee_name']); ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editEmployeeModal" data-id="<?php echo $employee['id']; ?>" data-name="<?php echo htmlentities($employee['employee_name']); ?>">Edit</button>
                                        </td>
                                    </tr>
                                    <?php
                                    $cnt++;
                                }
                            } else {
                                echo "<tr><td colspan='3'>No employees found for this training.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="employee_id" id="employee_id" />
                        
                        <!-- Employee Name Input -->
                        <div class="form-group">
                            <label>Employee Name</label>
                            <input type="text" name="employee_name" id="employee_name" class="form-control" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="editEmployee" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Populate the Edit Employee Modal with the employee data
        $('#editEmployeeModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var employeeId = button.data('id'); // Extract info from data-* attributes
            var employeeName = button.data('name'); // Extract employee name

            // Populate the modal fields
            $('#employee_id').val(employeeId);
            $('#employee_name').val(employeeName);
        });
    </script>
</body>
</html>
