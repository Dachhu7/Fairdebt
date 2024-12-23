<?php
// markAsRead.php

include 'db_connection.php'; // Include your database connection

if (isset($_GET['Notified'])) {
    $notifId = $_GET['Notified'];

    // Update the notification status to 'read' (Status = 1)
    $updateQuery = "UPDATE notifications SET Status = 1 WHERE ID = '$Notified'";

    if (mysqli_query($con, $updateQuery)) {
        // Redirect back to the dashboard or any other page
        header("Location: dashboard.php"); // Change this to your desired page
        exit();
    } else {
        // Handle error
        echo "Error updating notification: " . mysqli_error($con);
    }
}
?>
