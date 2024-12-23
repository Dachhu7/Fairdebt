<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;
}

// Add a new client
if (isset($_POST['addClient'])) {
    $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $company = mysqli_real_escape_string($con, $_POST['company']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $picture = ''; // Add code for handling picture upload if needed

    if (!empty($firstName) && !empty($lastName) && !empty($email)) {
        $query = mysqli_query($con, "INSERT INTO clients (FirstName, LastName, UserName, Email, Password, Phone, Company, Address, Status, Picture, date) 
                                     VALUES ('$firstName', '$lastName', '$username', '$email', '$password', '$phone', '$company', '$address', '$status', '$picture', NOW())");
        if ($query) {
            echo "<script>alert('Client added successfully');</script>";
            echo "<script>window.location.href='clients.php'</script>";
        } else {
            echo "<script>alert('Failed to add client');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the required fields');</script>";
    }
}

// Delete a client
if (isset($_GET['delid'])) {
    $cid = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM clients WHERE id='$cid'");
    if ($query) {
        echo "<script>alert('Client deleted successfully');</script>";
        echo "<script>window.location.href='clients.php'</script>";
    } else {
        echo "<script>alert('Failed to delete client');</script>";
    }
}

// Update an existing client
if (isset($_POST['updateClient'])) {
    $clientId = intval($_POST['clientId']);
    $firstName = mysqli_real_escape_string($con, $_POST['editFirstName']);
    $lastName = mysqli_real_escape_string($con, $_POST['editLastName']);
    $email = mysqli_real_escape_string($con, $_POST['editEmail']);
    $phone = mysqli_real_escape_string($con, $_POST['editPhone']);
    $company = mysqli_real_escape_string($con, $_POST['editCompany']);
    $address = mysqli_real_escape_string($con, $_POST['editAddress']);
    $status = mysqli_real_escape_string($con, $_POST['editStatus']);

    if (!empty($firstName) && !empty($lastName) && !empty($email)) {
        $query = mysqli_query($con, "UPDATE clients SET FirstName='$firstName', LastName='$lastName', Email='$email', Phone='$phone', 
                                      Company='$company', Address='$address', Status='$status' WHERE id='$clientId'");
        if ($query) {
            echo "<script>alert('Client updated successfully');</script>";
            echo "<script>window.location.href='clients.php'</script>";
        } else {
            echo "<script>alert('Failed to update client');</script>";
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
    <title>Client Details</title>
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
                    <h1 class="h3 mb-4 custom-heading">Client Details</h1>
                    <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addClientModal">Add Client</button>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Company</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $ret = mysqli_query($con, "SELECT * FROM clients ORDER BY FirstName ASC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($ret)) {
                                    // Determine the button color and label based on the status
                                    $statusClass = ''; // Default class for status cell
                                    $statusLabel = ''; // Default label for status cell
                                    switch ($row['Status']) {
                                        case 'Active':
                                            $statusClass = 'badge badge-success';
                                            $statusLabel = 'Active';
                                            break;
                                        case 'Inactive':
                                            $statusClass = 'badge badge-danger';
                                            $statusLabel = 'Inactive';
                                            break;
                                        default:
                                            $statusClass = 'badge badge-secondary';
                                            $statusLabel = 'Unknown';
                                            break;
                                    }
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['FirstName']); ?></td>
                                        <td><?php echo htmlentities($row['LastName']); ?></td>
                                        <td><?php echo htmlentities($row['Email']); ?></td>
                                        <td><?php echo htmlentities($row['Phone']); ?></td>
                                        <td><?php echo htmlentities($row['Company']); ?></td>
                                        <td>
                                            <!-- Display status with color-coded button -->
                                            <button class="btn btn-sm <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info edit-btn" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-firstname="<?php echo htmlentities($row['FirstName']); ?>"
                                                data-lastname="<?php echo htmlentities($row['LastName']); ?>"
                                                data-email="<?php echo htmlentities($row['Email']); ?>"
                                                data-phone="<?php echo htmlentities($row['Phone']); ?>"
                                                data-company="<?php echo htmlentities($row['Company']); ?>"
                                                data-address="<?php echo htmlentities($row['Address']); ?>"
                                                data-status="<?php echo htmlentities($row['Status']); ?>"
                                                data-toggle="modal" data-target="#editClientModal">Edit</button>
                                            <a href="clients.php?delid=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this record?');">Delete</a>
                                            <!-- View Employee Button -->
                                            <a href="https://docs.google.com/spreadsheets/d/1jxDnRFXUtbyMEkravkP8qW76KsOna00OU_kJGeHDlQQ/edit?gid=446900079#gid=446900079" class="btn btn-sm btn-warning" target="_blank">View Employee</a>
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

    <!-- Add Client Modal -->
    <div class="modal fade" id="addClientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Client</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="firstName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="lastName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" name="company" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="addClient" class="btn btn-primary">Save Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div class="modal fade" id="editClientModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Client</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="clientId" id="clientId">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="editFirstName" id="editFirstName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="editLastName" id="editLastName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="editEmail" id="editEmail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="editPhone" id="editPhone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <input type="text" name="editCompany" id="editCompany" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="editAddress" id="editAddress" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="editStatus" id="editStatus" class="form-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="updateClient" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>

    <script>
        // Set values for editing a client in the modal
        $('.edit-btn').on('click', function () {
            var id = $(this).data('id');
            var firstName = $(this).data('firstname');
            var lastName = $(this).data('lastname');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var company = $(this).data('company');
            var address = $(this).data('address');
            var status = $(this).data('status');
            
            $('#clientId').val(id);
            $('#editFirstName').val(firstName);
            $('#editLastName').val(lastName);
            $('#editEmail').val(email);
            $('#editPhone').val(phone);
            $('#editCompany').val(company);
            $('#editAddress').val(address);
            $('#editStatus').val(status);
        });
    </script>
</body>
</html>
