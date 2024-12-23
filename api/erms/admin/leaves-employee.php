<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid'] == 0)) {
    header('location:logout.php');
} else {
    $adminid = $_SESSION['aid'];
    $query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
    $result = mysqli_fetch_array($query);
    $role = $result['Role'];

    // Helper function to send notifications
    function sendNotification($con, $description, $type, $status, $empid, $recipientRole = null)
    {
        if ($recipientRole == 'Manager') {
            $managerQuery = mysqli_query($con, "SELECT ManagerID FROM employeedetail WHERE ID='$empid'");
            $managerResult = mysqli_fetch_array($managerQuery);
            $recipientID = $managerResult['ManagerID'];
        } elseif ($recipientRole == 'HR') {
            $hrQuery = mysqli_query($con, "SELECT ID FROM tbladmin WHERE Role='HR'");
            while ($hrResult = mysqli_fetch_array($hrQuery)) {
                $recipientID = $hrResult['ID'];
                $notifQuery = "INSERT INTO notifications (Description, Type, Status, EmpID) VALUES (?, ?, ?, ?)";
                $stmtNotif = $con->prepare($notifQuery);
                $stmtNotif->bind_param("ssii", $description, $type, $status, $recipientID);
                $stmtNotif->execute();
            }
            return;
        } else {
            $recipientID = $empid;
        }

        $notifQuery = "INSERT INTO notifications (Description, Type, Status, EmpID) VALUES (?, ?, ?, ?)";
        $stmtNotif = $con->prepare($notifQuery);
        $stmtNotif->bind_param("ssii", $description, $type, $status, $recipientID);
        $stmtNotif->execute();
    }

    // Approve leave record
    if (isset($_GET['approveid'])) {
        $eid = intval($_GET['approveid']);
        $query = "UPDATE leaves SET Status='Approved' WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $eid);
        if ($stmt->execute()) {
            $empid = $_GET['empid'];
            // Notify employee
            sendNotification($con, "Your leave request has been approved.", "Leave Approval", 0, $empid);
            // Notify HR
            sendNotification($con, "A leave request has been approved.", "Leave Approval", 0, $empid, 'HR');

            echo "<script>alert('Leave record approved successfully');</script>";
            echo "<script>window.location.href='leaves-employee.php'</script>";
        } else {
            echo "<script>alert('Error approving record');</script>";
        }
        $stmt->close();
    }

    // Reject leave record
    if (isset($_GET['rejectid'])) {
        $eid = intval($_GET['rejectid']);
        $query = "UPDATE leaves SET Status='Rejected' WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $eid);
        if ($stmt->execute()) {
            $empid = $_GET['empid'];
            // Notify employee
            sendNotification($con, "Your leave request has been rejected.", "Leave Rejection", 0, $empid);
            // Notify HR
            sendNotification($con, "A leave request has been rejected.", "Leave Rejection", 0, $empid, 'HR');

            echo "<script>alert('Leave record rejected successfully');</script>";
            echo "<script>window.location.href='leaves-employee.php'</script>";
        } else {
            echo "<script>alert('Error rejecting record');</script>";
        }
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Employee Leaves</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
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
        .btn {
            font-size: 0.875rem;
            border-radius: 0.35rem;
        }
        .btn-success:hover {
            background-color: #28a745;
        }
        .btn-warning:hover {
            background-color: #ffc107;
        }
        .header-title {
            color: #4e73df;
            font-weight: bold;
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
        .btn-primary:hover, .btn-success:hover, .btn-warning:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary, .btn-success, .btn-warning {
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
                    <h1 class="custom-heading">Employee Leave Details</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>S no.</th>
                                    <th>Employee</th>
                                    <th>Starting At</th>
                                    <th>Ending On</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch pending leave records first
                                $queryPending = "SELECT * FROM leaves WHERE Status='Pending' ";
                                if ($role == 'Super Admin') {
                                    $query = "SELECT * FROM leaves WHERE Status='Pending' UNION SELECT * FROM leaves WHERE Status!='Pending'";
                                } elseif ($role == 'HR') {
                                    $query = "SELECT * FROM leaves WHERE Status IN ('Approved', 'Rejected') UNION SELECT * FROM leaves WHERE Status='Pending'";
                                } elseif ($role == 'Manager') {
                                    $query = "SELECT l.* FROM leaves l 
                                              INNER JOIN employeedetail e ON l.EmpID = e.ID 
                                              WHERE e.ManagerID = '$adminid' AND l.Status = 'Pending' 
                                              UNION 
                                              SELECT l.* FROM leaves l 
                                              INNER JOIN employeedetail e ON l.EmpID = e.ID 
                                              WHERE e.ManagerID = '$adminid' AND l.Status != 'Pending'";
                                } else {
                                    echo "<tr><td colspan='8'>No access to records</td></tr>";
                                    exit();
                                }

                                $result = $con->query($query);
                                if ($result->num_rows > 0) {
                                    $cnt = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo htmlspecialchars($row['Employee']); ?></td>
                                            <td><?php echo htmlspecialchars($row['Starting_At']); ?></td>
                                            <td><?php echo htmlspecialchars($row['Ending_On']); ?></td>
                                            <td><?php echo htmlspecialchars($row['Days']); ?></td>
                                            <td><?php echo htmlspecialchars($row['Reason']); ?></td>
                                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                                            <td>
                                                <?php if ($row['Status'] == 'Pending') { ?>
                                                    <a href="leaves-employee.php?approveid=<?php echo $row['id']; ?>&empid=<?php echo $row['EmpID']; ?>" class="btn btn-success btn-sm">Approve</a>
                                                    <a href="leaves-employee.php?rejectid=<?php echo $row['id']; ?>&empid=<?php echo $row['EmpID']; ?>" class="btn btn-warning btn-sm">Reject</a>
                                                <?php } else { ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>No Actions</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt++;
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No records found</td></tr>";
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
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>

</html>

<?php } ?>
