<?php
session_start();
error_reporting(E_ALL);  // Enable error reporting to capture all errors for debugging.
include('includes/dbconnection.php');

$msg = '';  // Initialize the $msg variable to avoid undefined variable warning.

if (strlen($_SESSION['uid'] == 0)) {
    header('location:logout.php');
    exit();
} else {
    $eid = $_SESSION['uid'];

    // Fetch employee's full name and manager ID
    $ret = mysqli_query($con, "SELECT EmpFname, EmpLname, ManagerID FROM employeedetail WHERE ID='$eid'");
    if (!$ret) {
        die('Error fetching employee details: ' . mysqli_error($con));  // Display query error if query fails
    }

    if ($row = mysqli_fetch_array($ret)) {
        $employeeName = $row['EmpFname'] . " " . $row['EmpLname'];
        $managerID = $row['ManagerID']; // Get manager ID
    } else {
        header('location:logout.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $leaveType = $_POST['leaveType'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $reason = $_POST['reason'];

        // Calculate number of days
        $date1 = new DateTime($startDate);
        $date2 = new DateTime($endDate);
        $days = $date2->diff($date1)->days + 1;

        // Insert leave request into the 'leaves' table (sent to manager)
        $query = mysqli_query($con, "INSERT INTO leaves(Employee, LeaveType, Starting_At, Ending_On, Days, Reason, Status, Notified, EmpID) 
                                     VALUES('$employeeName', '$leaveType', '$startDate', '$endDate', '$days', '$reason', 'Pending', 0, '$eid')");

        if ($query) {
            // Send notification to the manager of the logged-in user
            $notification = "Leave request submitted by $employeeName from $startDate to $endDate for $days days.";
            $notifQuery = mysqli_query($con, "INSERT INTO notifications(Description, Type, Status, EmpID) 
                                             VALUES('$notification', 'Leave Request', 0, '$managerID')");  // Notify manager

            // Update the employee's leave balance
            $leaveBalanceQuery = mysqli_query($con, "SELECT * FROM employee_leave_balance WHERE EmpID='$eid'");
            if (!$leaveBalanceQuery) {
                die('Error fetching leave balance: ' . mysqli_error($con));  // Display query error if query fails
            }

            if (mysqli_num_rows($leaveBalanceQuery) > 0) {
                $leaveBalanceData = mysqli_fetch_array($leaveBalanceQuery);
                $totalLeaves = $leaveBalanceData['TotalLeaves'];
                $usedLeaves = $leaveBalanceData['UsedLeaves'];

                if (($usedLeaves + $days) <= $totalLeaves) {
                    // Update used leaves after applying the leave
                    mysqli_query($con, "UPDATE employee_leave_balance SET UsedLeaves = UsedLeaves + $days WHERE EmpID='$eid'");
                    $msg = "Your leave has been applied successfully.";
                } else {
                    $msg = "You don't have sufficient leaves.";
                }
            } else {
                // Create a new leave balance record if it doesn't exist for the employee
                mysqli_query($con, "INSERT INTO employee_leave_balance (EmpID, TotalLeaves, UsedLeaves) VALUES ('$eid', 30, $days)");
                $msg = "Your leave has been applied successfully.";
            }
        } else {
            $msg = "Something went wrong. Please try again.";
        }
    }

    // Fetch the employee's leave balance (Total Leaves and Used Leaves)
    $leaveBalanceQuery = mysqli_query($con, "SELECT TotalLeaves, UsedLeaves FROM employee_leave_balance WHERE EmpID='$eid'");
    if (!$leaveBalanceQuery) {
        die('Error fetching leave balance: ' . mysqli_error($con));  // Display query error if query fails
    }

    if ($leaveBalanceData = mysqli_fetch_array($leaveBalanceQuery)) {
        $totalLeaves = $leaveBalanceData['TotalLeaves'];
        $usedLeaves = $leaveBalanceData['UsedLeaves'];
        $pendingLeaves = $totalLeaves - $usedLeaves;
    } else {
        $totalLeaves = 30;
        $usedLeaves = 0;
        $pendingLeaves = 30;
    }

    // Fetch holidays from the holidays table
    $holidaysQuery = mysqli_query($con, "SELECT Holiday_Name, Holiday_Date FROM holidays");
    if (!$holidaysQuery) {
        die('Error fetching holidays: ' . mysqli_error($con));  // Display query error if query fails
    }
    $holidays = [];
    while ($holiday = mysqli_fetch_array($holidaysQuery)) {
        $holidays[] = $holiday;
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

    <title>Apply for Leave</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom Styles for this Page -->
    <style>
        .custom-heading {
            font-size: 2rem;
            font-weight: 700;
            color: #4e73df;
            text-align: center;
            text-transform: uppercase;
            margin: 20px 0;
        }

        .content-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .leave-form-container {
            background-color: #f4f6f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        .cards-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            justify-content: center;
            width: 100%;
            max-width: 900px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .card-body {
            text-align: center;
            padding: 20px;
        }

        .chart-container {
            width: 100%;
            height: 100%;
        }
                

        ul {
            padding: 0;
            list-style-type: none;
        }

        ul li {
            margin: 5px 0;
        }

        /* Optional: Add responsiveness for smaller screens */
        @media (max-width: 768px) {
            .cards-container {
                flex-direction: column;
                align-items: center;
            }

            .chart-container {
                height: 200px; /* Reduce height for mobile view */
            }
        }
    </style>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

                <div class="container-fluid">
                    <div class="content-container">
                        <!-- Apply for Leave Form -->
                        <h1 class="h3 mb-4 text-gray-800 custom-heading">Apply for Leave</h1>
                        <p style="font-size:16px; color:green" align="center"><?php if ($msg) { echo $msg; } ?></p>

                        <div class="leave-form-container">
                            <form class="user" method="post" action="">
                                <div class="row">
                                    <div class="col-4 mb-3">Leave Type</div>
                                    <div class="col-8 mb-3">
                                        <select name="leaveType" class="form-control" required>
                                            <option value="">--Select Leave Type--</option>
                                            <option value="Sick Leave">Sick Leave</option>
                                            <option value="Casual Leave">Casual Leave</option>
                                            <option value="Earned Leave">Earned Leave</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 mb-3">Start Date</div>
                                    <div class="col-8 mb-3">
                                        <input type="date" class="form-control" name="startDate" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 mb-3">End Date</div>
                                    <div class="col-8 mb-3">
                                        <input type="date" class="form-control" name="endDate" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 mb-3">Reason</div>
                                    <div class="col-8 mb-3">
                                        <textarea name="reason" class="form-control" rows="4" required></textarea>
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-outline-primary btn-block">Apply Leave</button>
                            </form>
                        </div>

                        <!-- Cards Section -->
                        <div class="cards-container">
                            <!-- Leave Balance Card -->
                            <div class="card">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-white">Leave Balance</h6>
                                </div>
                                <div class="card-body">
                                    <p>Total Leaves: <?php echo $totalLeaves; ?></p>
                                    <p>Used Leaves: <?php echo $usedLeaves; ?></p>
                                    <p>Pending Leaves: <?php echo $pendingLeaves; ?></p>
                                    <div class="chart-container">
                                        <canvas id="leaveBalanceChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Upcoming Holidays Card -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-white">Upcoming Holidays</h6>
                                </div>
                                <div class="card-body">
                                    <ul>
                                        <?php foreach ($holidays as $holiday): ?>
                                            <li>
                                                <?php echo $holiday['Holiday_Name'] . " on " . $holiday['Holiday_Date']; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        // Chart.js code for leave balance chart
        var ctx = document.getElementById('leaveBalanceChart').getContext('2d');
        var leaveBalanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Used Leaves', 'Pending Leaves'],
                datasets: [{
                    data: [<?php echo $usedLeaves; ?>, <?php echo $pendingLeaves; ?>],
                    backgroundColor: ['#ff6347', '#28a745'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' days';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
<?php }  ?>