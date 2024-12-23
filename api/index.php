<?php
// Set headers for API response
header('Content-Type: application/json');

// Include your database connection
include_once('erms/includes/dbconnection.php');

// Fetch data or perform operations
$data = ['status' => 'success', 'message' => 'API is working'];

// Return JSON response
echo json_encode($data);
?>
