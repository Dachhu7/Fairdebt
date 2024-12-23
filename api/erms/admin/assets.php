<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (empty($_SESSION['aid'])) {
    header('location:logout.php');
    exit;
}

// Handle adding asset
if (isset($_POST['addAsset'])) {
    $assetName = mysqli_real_escape_string($con, $_POST['asset_name']);
    $employee = mysqli_real_escape_string($con, $_POST['employee']);
    $purchaseDate = mysqli_real_escape_string($con, $_POST['purchase_date']);
    $assetCondition = mysqli_real_escape_string($con, $_POST['asset_condition']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    $query = "INSERT INTO assets (asset_name, employee, purchase_date, asset_condition, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $assetName, $employee, $purchaseDate, $assetCondition, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Asset added successfully');</script>";
        echo "<script>window.location.href='assets.php'</script>";
    } else {
        echo "<script>alert('Failed to add asset');</script>";
    }
}

// Handle updating asset status
if (isset($_POST['updateStatus'])) {
    $assetId = intval($_POST['assetId']);
    $assetStatus = filter_var($_POST['assetStatus'], FILTER_SANITIZE_STRING);

    if (!empty($assetStatus)) {
        $query = "UPDATE assets SET status = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("si", $assetStatus, $assetId);
        if ($stmt->execute()) {
            echo "<script>alert('Asset status updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update asset status');</script>";
        }
    }
}

// Handle uploading attachments
if (isset($_POST['uploadAttachment'])) {
    $assetId = intval($_POST['assetId']);
    if (!empty($_FILES['attachment']['name'])) {
        $attachmentName = $_FILES['attachment']['name'];
        $attachmentTemp = $_FILES['attachment']['tmp_name'];
        $attachmentPath = "uploads/" . basename($attachmentName);

        // Ensure the uploads directory exists
        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        if (move_uploaded_file($attachmentTemp, $attachmentPath)) {
            $query = "INSERT INTO asset_attachments (asset_id, file_path) VALUES (?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("is", $assetId, $attachmentPath);
            if ($stmt->execute()) {
                echo "<script>alert('Attachment uploaded successfully');</script>";
            } else {
                echo "<script>alert('Failed to save attachment in database');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload attachment');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Asset Management</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Arial', sans-serif;
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
        .btn-group .btn {
            margin: 2px;
        }

        .btn-info {
            background-color: #36b9cc;
            border: none;
        }

        .btn-danger {
            background-color: #e74a3b;
            border: none;
        }

        .btn-info:hover,
        .btn-danger:hover {
            opacity: 0.9;
        }

        .scroll-to-top {
            background-color: #4e73df;
        }

        .scroll-to-top:hover {
            background-color: #224abe;
        }

        .alert-message {
            text-align: center;
            font-size: 16px;
            color: red;
            font-weight: bold;
        }
        .modal-header {
            background-color: #4e73df;
            color: white;
        }
        .btn-primary:hover, .btn-info:hover, .btn-danger:hover, .btn-secondary:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary, .btn-info, .btn-danger, .btn-secondary {
            border-radius: 20px;
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

                    <!-- Add Asset Button -->
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addAssetModal">Add Asset</button>

                    <!-- Asset Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>S No.</th>
                                <th>Asset Name</th>
                                <th>Employee</th>
                                <th>Purchase Date</th>
                                <th>Condition</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ret = $con->query("SELECT * FROM assets ORDER BY purchase_date DESC");
                            $cnt = 1;
                            while ($row = $ret->fetch_assoc()) {
                                $statusClass = '';
                                switch ($row['status']) {
                                    case 'Active':
                                        $statusClass = 'badge badge-success';
                                        break;
                                    case 'Inactive':
                                        $statusClass = 'badge badge-warning';
                                        break;
                                    case 'Damaged':
                                        $statusClass = 'badge badge-danger';
                                        break;
                                    default:
                                        $statusClass = 'badge badge-secondary';
                                        break;
                                }
                            ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td><?php echo htmlentities($row['asset_name']); ?></td>
                                    <td><?php echo htmlentities($row['employee']); ?></td>
                                    <td><?php echo htmlentities($row['purchase_date']); ?></td>
                                    <td><?php echo htmlentities($row['asset_condition']); ?></td>
                                    <td>
                                        <span class="<?php echo $statusClass; ?>">
                                            <?php echo htmlentities($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="asset-view.php?assetId=<?php echo $row['id']; ?>">View Details</a>
                                        <button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#uploadAttachmentModal" data-id="<?php echo $row['id']; ?>">Upload Attachments</button>
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
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Add Asset Modal -->
    <div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Asset</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Asset Name</label>
                            <input type="text" name="asset_name" class="form-control" placeholder="Enter asset name" required>
                        </div>
                        <div class="form-group">
                            <label>Employee</label>
                            <input type="text" name="employee" class="form-control" placeholder="Enter employee name" required>
                        </div>
                        <div class="form-group">
                            <label>Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Condition</label>
                            <select name="asset_condition" class="form-control" required>
                                <option value="New">New</option>
                                <option value="Used">Used</option>
                                <option value="Refurbished">Refurbished</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Damaged">Damaged</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addAsset" class="btn btn-primary">Add Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload Attachment Modal -->
    <div class="modal fade" id="uploadAttachmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Upload Attachment</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="assetId" id="uploadAssetId">
                        <div class="form-group">
                            <label>Attachment</label>
                            <input type="file" name="attachment" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="uploadAttachment" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script>
        $('#uploadAttachmentModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const assetId = button.data('id');
            $('#uploadAssetId').val(assetId);
        });
    </script>
</body>
</html>
