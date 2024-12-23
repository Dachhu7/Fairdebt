<?php
session_start();
include('includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['uid'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$empid = $_SESSION['uid'];  // Get the logged-in user's ID

// Check if there are any unread notifications before updating
$checkQuery = "SELECT * FROM notifications WHERE EmpID = '$empid' AND Status = 0";
$checkResult = mysqli_query($con, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // If there are unread notifications, proceed with update
    $updateQuery = "UPDATE notifications SET Status = 1 WHERE EmpID = '$empid' AND Status = 0";
    $result = mysqli_query($con, $updateQuery);
    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        // Show any error from MySQL
        echo json_encode(['status' => 'error', 'message' => mysqli_error($con)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No unread notifications found']);
}
?>
