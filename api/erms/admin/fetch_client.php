<?php
include('includes/dbconnection.php');

if (isset($_GET['clientId'])) {
    $clientId = $_GET['clientId'];
    
    $query = mysqli_query($con, "SELECT * FROM clients WHERE id = '$clientId'");
    
    if ($query) {
        $client = mysqli_fetch_assoc($query);
        echo json_encode($client);
    } else {
        echo json_encode(['error' => 'Client not found']);
    }
}
?>
