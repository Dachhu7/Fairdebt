<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (empty($_SESSION['aid'])) {
    header('location:logout.php');
    exit;
}

// Fetch the asset details based on the assetId
if (isset($_GET['assetId']) && is_numeric($_GET['assetId'])) {
    $assetId = $_GET['assetId'];

    // Query to get the details of the specific asset
    $query = "SELECT * FROM assets WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $assetId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $assetDetails = $result->fetch_assoc();
    } else {
        echo "<script>alert('Asset not found');</script>";
        echo "<script>window.location.href='assets.php'</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid Asset ID');</script>";
    echo "<script>window.location.href='assets.php'</script>";
    exit;
}

// Handle the update of asset details
if (isset($_POST['updateAsset'])) {
    $assetName = mysqli_real_escape_string($con, $_POST['asset_name']);
    $employee = mysqli_real_escape_string($con, $_POST['employee']);
    $purchaseDate = mysqli_real_escape_string($con, $_POST['purchase_date']);
    $assetCondition = mysqli_real_escape_string($con, $_POST['asset_condition']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    // Update query to save the edited details
    $updateQuery = "UPDATE assets SET asset_name = ?, employee = ?, purchase_date = ?, asset_condition = ?, status = ? WHERE id = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("sssssi", $assetName, $employee, $purchaseDate, $assetCondition, $status, $assetId);

    if ($stmt->execute()) {
        echo "<script>alert('Asset updated successfully');</script>";
        echo "<script>window.location.href='asset-view.php?assetId=$assetId'</script>";
    } else {
        echo "<script>alert('Failed to update asset');</script>";
    }
}

// Handle the delete of asset details
if (isset($_POST['deleteAsset'])) {
    $deleteQuery = "DELETE FROM assets WHERE id = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("i", $assetId);

    if ($stmt->execute()) {
        echo "<script>alert('Asset deleted successfully');</script>";
        echo "<script>window.location.href='assets.php'</script>";
    } else {
        echo "<script>alert('Failed to delete asset');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Asset Details</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .custom-heading {
            font-size: 2rem;
            font-weight: 700;
            color: #4e73df;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include_once('includes/header.php'); ?>
                
                <!-- Asset Details Section -->
                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 custom-heading">Asset Details</h1>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"><?php echo htmlentities($assetDetails['asset_name']); ?> - Asset Details</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>Employee:</strong> <?php echo htmlentities($assetDetails['employee']); ?></p>
                            <p><strong>Purchase Date:</strong> <?php echo htmlentities($assetDetails['purchase_date']); ?></p>
                            <p><strong>Condition:</strong> <?php echo htmlentities($assetDetails['asset_condition']); ?></p>
                            <p><strong>Status:</strong> <?php 
                                $status = htmlentities($assetDetails['status']);
                                if ($status == "Active") {
                                    echo "<span class='badge badge-success'>$status</span>";
                                } elseif ($status == "Inactive") {
                                    echo "<span class='badge badge-warning'>$status</span>";
                                } else {
                                    echo "<span class='badge badge-danger'>$status</span>";
                                }
                            ?></p>
                        </div>
                    </div>

                    <!-- Edit and Delete Buttons -->
                    <button class="btn btn-warning mt-4" data-toggle="modal" data-target="#editAssetModal">Edit Asset</button>
                    <button class="btn btn-danger mt-4" data-toggle="modal" data-target="#deleteAssetModal">Delete Asset</button>

                    <!-- Modal for Editing Asset -->
                    <div class="modal fade" id="editAssetModal" tabindex="-1" role="dialog" aria-labelledby="editAssetModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editAssetModalLabel">Edit Asset</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Asset Name</label>
                                            <input type="text" name="asset_name" class="form-control" value="<?php echo htmlentities($assetDetails['asset_name']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Employee</label>
                                            <input type="text" name="employee" class="form-control" value="<?php echo htmlentities($assetDetails['employee']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Purchase Date</label>
                                            <input type="date" name="purchase_date" class="form-control" value="<?php echo htmlentities($assetDetails['purchase_date']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Condition</label>
                                            <select name="asset_condition" class="form-control" required>
                                                <option value="New" <?php echo $assetDetails['asset_condition'] == 'New' ? 'selected' : ''; ?>>New</option>
                                                <option value="Used" <?php echo $assetDetails['asset_condition'] == 'Used' ? 'selected' : ''; ?>>Used</option>
                                                <option value="Refurbished" <?php echo $assetDetails['asset_condition'] == 'Refurbished' ? 'selected' : ''; ?>>Refurbished</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="Active" <?php echo $assetDetails['status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="Inactive" <?php echo $assetDetails['status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                                <option value="Damaged" <?php echo $assetDetails['status'] == 'Damaged' ? 'selected' : ''; ?>>Damaged</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="updateAsset" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Deleting Asset -->
                    <div class="modal fade" id="deleteAssetModal" tabindex="-1" role="dialog" aria-labelledby="deleteAssetModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAssetModalLabel">Delete Asset</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this asset?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" name="deleteAsset" class="btn btn-danger">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <a href="assets.php" class="btn btn-primary mt-4">Back to Asset List</a>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
