<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;
}

// Add a new lead
if (isset($_POST['addLead'])) {
    $leadName = mysqli_real_escape_string($con, $_POST['leadName']);
    $leadEmail = mysqli_real_escape_string($con, $_POST['leadEmail']);
    $leadPhone = mysqli_real_escape_string($con, $_POST['leadPhone']);
    $leadSource = mysqli_real_escape_string($con, $_POST['leadSource']);
    $leadStatus = mysqli_real_escape_string($con, $_POST['leadStatus']);
    $dateTime = date('Y-m-d H:i:s');

    if (!empty($leadName) && !empty($leadEmail)) {
        $query = mysqli_query($con, "INSERT INTO leads (Lead_Name, Lead_Email, Lead_Phone, Lead_Source, Lead_Status, DateTime) 
                                     VALUES ('$leadName', '$leadEmail', '$leadPhone', '$leadSource', '$leadStatus', '$dateTime')");
        if ($query) {
            echo "<script>alert('Lead added successfully');</script>";
            echo "<script>window.location.href='leads.php'</script>";
        } else {
            echo "<script>alert('Failed to add lead');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the required fields');</script>";
    }
}

// Delete a lead
if (isset($_GET['delid'])) {
    $lid = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM leads WHERE id='$lid'");
    if ($query) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location.href='leads.php'</script>";
    } else {
        echo "<script>alert('Failed to delete record');</script>";
    }
}

// Update an existing lead
if (isset($_POST['updateLead'])) {
    $leadId = intval($_POST['leadId']);
    $leadName = mysqli_real_escape_string($con, $_POST['editLeadName']);
    $leadEmail = mysqli_real_escape_string($con, $_POST['editLeadEmail']);
    $leadPhone = mysqli_real_escape_string($con, $_POST['editLeadPhone']);
    $leadSource = mysqli_real_escape_string($con, $_POST['editLeadSource']);
    $leadStatus = mysqli_real_escape_string($con, $_POST['editLeadStatus']);

    if (!empty($leadName) && !empty($leadEmail)) {
        $query = mysqli_query($con, "UPDATE leads SET Lead_Name='$leadName', Lead_Email='$leadEmail', Lead_Phone='$leadPhone', 
                                     Lead_Source='$leadSource', Lead_Status='$leadStatus' WHERE id='$leadId'");
        if ($query) {
            echo "<script>alert('Lead updated successfully');</script>";
            echo "<script>window.location.href='leads.php'</script>";
        } else {
            echo "<script>alert('Failed to update lead');</script>";
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
    <title>Lead Details</title>
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
        .btn-primary:hover, .btn-info:hover, .btn-danger:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary, .btn-info, .btn-danger {
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
                <div class="container-fluid">
                    <h1 class="h3 mb-4 custom-heading">Lead Details</h1>
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addLeadModal">Add Lead</button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Lead Name</th>
                                    <th>Lead Email</th>
                                    <th>Lead Phone</th>
                                    <th>Lead Source</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM leads ORDER BY Lead_Name ASC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($ret)) {
                                    // Determine the badge class based on the status
                                    $statusClass = ''; // Default class for status cell
                                    $statusLabel = ''; // Default label for status cell
                                    switch ($row['Lead_Status']) {
                                        case 'New':
                                            $statusClass = 'badge badge-primary';
                                            $statusLabel = 'New';
                                            break;
                                        case 'Contacted':
                                            $statusClass = 'badge badge-info';
                                            $statusLabel = 'Contacted';
                                            break;
                                        case 'Converted':
                                            $statusClass = 'badge badge-success';
                                            $statusLabel = 'Converted';
                                            break;
                                        case 'Not Interested':
                                            $statusClass = 'badge badge-danger';
                                            $statusLabel = 'Not Interested';
                                            break;
                                        default:
                                            $statusClass = 'badge badge-secondary';
                                            $statusLabel = 'Unknown';
                                            break;
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['Lead_Name']); ?></td>
                                        <td><?php echo htmlentities($row['Lead_Email']); ?></td>
                                        <td><?php echo htmlentities($row['Lead_Phone']); ?></td>
                                        <td><?php echo htmlentities($row['Lead_Source']); ?></td>
                                        <td><span class="<?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-btn" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-name="<?php echo htmlentities($row['Lead_Name']); ?>" 
                                                data-email="<?php echo htmlentities($row['Lead_Email']); ?>" 
                                                data-phone="<?php echo htmlentities($row['Lead_Phone']); ?>" 
                                                data-source="<?php echo htmlentities($row['Lead_Source']); ?>"
                                                data-status="<?php echo htmlentities($row['Lead_Status']); ?>"
                                                data-toggle="modal" data-target="#editLeadModal">Edit</button>
                                            <a href="leads.php?delid=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this record?');">Delete</a>
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

    <!-- Add Lead Modal -->
    <div class="modal fade" id="addLeadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Lead</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Lead Name</label>
                            <input type="text" name="leadName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lead Email</label>
                            <input type="email" name="leadEmail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lead Phone</label>
                            <input type="text" name="leadPhone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Lead Source</label>
                            <input type="text" name="leadSource" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="leadStatus" class="form-control">
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Converted">Converted</option>
                                <option value="Not Interested">Not Interested</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addLead" class="btn btn-primary">Add Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Lead Modal -->
    <div class="modal fade" id="editLeadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Lead</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="leadId" id="editLeadId">
                        <div class="form-group">
                            <label>Lead Name</label>
                            <input type="text" name="editLeadName" id="editLeadName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lead Email</label>
                            <input type="email" name="editLeadEmail" id="editLeadEmail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Lead Phone</label>
                            <input type="text" name="editLeadPhone" id="editLeadPhone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Lead Source</label>
                            <input type="text" name="editLeadSource" id="editLeadSource" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="editLeadStatus" id="editLeadStatus" class="form-control">
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Converted">Converted</option>
                                <option value="Not Interested">Not Interested</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="updateLead" class="btn btn-primary">Update Lead</button>
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
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var source = $(this).data('source');
            var status = $(this).data('status');

            $('#editLeadId').val(id);
            $('#editLeadName').val(name);
            $('#editLeadEmail').val(email);
            $('#editLeadPhone').val(phone);
            $('#editLeadSource').val(source);
            $('#editLeadStatus').val(status);
        });
    </script>
</body>
</html>
