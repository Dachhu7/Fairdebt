<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the employee ID is set in the GET request
if (isset($_GET['id'])) {
    $employeeId = intval($_GET['id']);

    // Prepare the query to fetch the employee details from the database
    $query = "SELECT * FROM view_employee WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $employeeId);
    
    // Execute the query
    if ($stmt->execute()) {
        // Get the result of the query
        $result = $stmt->get_result();
        
        // Check if the employee exists
        if ($result->num_rows > 0) {
            $employee = $result->fetch_assoc();
            
            // Return the employee data as a JSON response
            echo json_encode($employee);
        } else {
            // If employee not found, return an error message
            echo json_encode(["error" => "Employee not found"]);
        }
    } else {
        // If query execution fails, return an error message
        echo json_encode(["error" => "Failed to fetch employee data"]);
    }
} else {
    // If no employee ID is provided, return an error message
    echo json_encode(["error" => "No employee ID provided"]);
}

?>
