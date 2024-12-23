<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;
}

$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

// Add a new holiday
if (isset($_POST['addHoliday'])) {
    $holidayName = mysqli_real_escape_string($con, $_POST['holidayName']);
    $holidayDate = mysqli_real_escape_string($con, $_POST['holidayDate']);
    $dateTime = date('Y-m-d H:i:s');

    if (!empty($holidayName) && !empty($holidayDate)) {
        $query = mysqli_query($con, "INSERT INTO holidays (Holiday_Name, Holiday_Date, DateTime) VALUES ('$holidayName', '$holidayDate', '$dateTime')");
        if ($query) {
            echo "<script>alert('Holiday added successfully');</script>";
            echo "<script>window.location.href='holidays.php'</script>";
        } else {
            echo "<script>alert('Failed to add holiday');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the required fields');</script>";
    }
}

// Delete a holiday
if (isset($_GET['delid'])) {
    $hid = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM holidays WHERE id='$hid'");
    if ($query) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location.href='holidays.php'</script>";
    } else {
        echo "<script>alert('Failed to delete record');</script>";
    }
}

// Update an existing holiday
if (isset($_POST['updateHoliday'])) {
    $holidayId = intval($_POST['holidayId']);
    $holidayName = mysqli_real_escape_string($con, $_POST['editHolidayName']);
    $holidayDate = mysqli_real_escape_string($con, $_POST['editHolidayDate']);

    if (!empty($holidayName) && !empty($holidayDate)) {
        $query = mysqli_query($con, "UPDATE holidays SET Holiday_Name='$holidayName', Holiday_Date='$holidayDate' WHERE id='$holidayId'");
        if ($query) {
            echo "<script>alert('Holiday updated successfully');</script>";
            echo "<script>window.location.href='holidays.php'</script>";
        } else {
            echo "<script>alert('Failed to update holiday');</script>";
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
    <title>Holiday Details</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
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

        .btn-primary, .btn-info, .btn-danger {
            border-radius: 20px;
        }

        .modal-header {
            background-color: #4e73df;
            color: white;
        }

        .modal-title {
            font-weight: bold;
        }

        .form-control {
            border-radius: 15px;
        }

        .btn-primary:hover, .btn-info:hover, .btn-danger:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
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
                    <h1 class="h3 mb-4 custom-heading">Holiday Details</h1>
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addHolidayModal">Add Holiday</button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Holiday Name</th>
                                    <th>Holiday Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM holidays ORDER BY Holiday_Date ASC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['Holiday_Name']); ?></td>
                                        <td><?php echo htmlentities($row['Holiday_Date']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-btn" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-name="<?php echo htmlentities($row['Holiday_Name']); ?>" 
                                                data-date="<?php echo htmlentities($row['Holiday_Date']); ?>" 
                                                data-toggle="modal" data-target="#editHolidayModal">Edit</button>
                                            <a href="holidays.php?delid=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this record?');">Delete</a>
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

    <!-- Add Holiday Modal -->
    <div class="modal fade" id="addHolidayModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Holiday</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Holiday Name</label>
                            <input type="text" name="holidayName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Holiday Date</label>
                            <input type="date" name="holidayDate" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addHoliday" class="btn btn-primary">Add Holiday</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Holiday Modal -->
    <div class="modal fade" id="editHolidayModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Holiday</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="holidayId" id="editHolidayId">
                        <div class="form-group">
                            <label>Holiday Name</label>
                            <input type="text" name="editHolidayName" id="editHolidayName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Holiday Date</label>
                            <input type="date" name="editHolidayDate" id="editHolidayDate" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="updateHoliday" class="btn btn-primary">Update Holiday</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var date = $(this).data('date');

            $('#editHolidayId').val(id);
            $('#editHolidayName').val(name);
            $('#editHolidayDate').val(date);
        });
    </script>
</body>
</html>