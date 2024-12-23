<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Ensure user is logged in
if (empty($_SESSION['aid'])) {
    header('location:logout.php');
    exit;
}

$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

// Fetch all users from employeedetail and their managers
$userQuery = "SELECT e.*, a.AdminName AS ManagerName FROM employeedetail e 
              LEFT JOIN tbladmin a ON e.ManagerID = a.ID";
$userResult = mysqli_query($con, $userQuery);
if (!$userResult) {
    die("Error fetching users: " . mysqli_error($con));
}

// Fetch all managers
$managersQuery = "SELECT ID, AdminName FROM tbladmin WHERE Role='Manager'";
$managersResult = mysqli_query($con, $managersQuery);
$managers = [];
while ($row = mysqli_fetch_assoc($managersResult)) {
    $managers[$row['ID']] = $row['AdminName'];
}

// Handle adding a new user
if (isset($_POST['addUser'])) {
    $fullName = mysqli_real_escape_string($con, $_POST['fullName']);
    $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $birthday = mysqli_real_escape_string($con, $_POST['birthday']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $role = mysqli_real_escape_string($con, $_POST['role']);
    $dept = mysqli_real_escape_string($con, $_POST['dept']);
    $designation = mysqli_real_escape_string($con, $_POST['designation']);
    $joiningDate = mysqli_real_escape_string($con, $_POST['joiningDate']);
    $managerID = $_POST['managerID'] ? intval($_POST['managerID']) : NULL;
    $empCode = mysqli_real_escape_string($con, $_POST['empCode']);

    // Insert into the appropriate table based on the role
    if ($role == "Super Admin" || $role == "HR" || $role == "Manager") {
        // Insert into tbladmin for Super Admin and HR roles
        $insertQuery = "INSERT INTO tbladmin (AdminName, AdminuserName, Password, role, Phone, Email, Birthday, Address, Gender, AdminRegdate, ManagerID) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $adminUsername = strtolower(str_replace(" ", "", $fullName)); // Generate a username
        $stmt->bind_param("ssssssssssi", $fullName, $adminUsername, $password, $role, $phone, $email, $birthday, $address, $gender, $joiningDate, $managerID);
    } else if ($role == "Agent" || $role == "Collection Executive") {
        $insertQuery = "INSERT INTO employeedetail 
                    (EmpFname, EmpLName, EmpCode, EmpDept, EmpDesignation, EmpContactNo, EmpGender, EmpEmail, EmpPassword, role, EmpJoingdate, EmpBirthday, EmpAddress, ManagerID) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($insertQuery);
        $stmt->bind_param("sssssssssssssi", $fullName, $lastName, $empCode, $dept, $designation, $phone, $gender, $email, $password, $role, $joiningDate, $birthday, $address, $managerID);
    } else {
        echo "<script>alert('Invalid role selected');</script>";
        echo "<script>window.location.href='users.php'</script>";
        exit;
    }

    // Execute the query and check if successful
    if ($stmt->execute()) {
        echo "<script>alert('User added successfully');</script>";
        echo "<script>window.location.href='users.php'</script>";
    } else {
        echo "<script>alert('Failed to add user');</script>";
    }
}

// Fetch all departments from the database
$deptQuery = "SELECT id, Department FROM departments";
$deptResult = mysqli_query($con, $deptQuery);

// Fetch all designations from the database
$designationQuery = "SELECT id, Designation FROM designations";
$designationResult = mysqli_query($con, $designationQuery);

// Handle updating user details
if (isset($_POST['updateUser'])) {
    $userId = $_POST['userId'];
    $fullName = mysqli_real_escape_string($con, $_POST['fullName']);
    $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $birthday = mysqli_real_escape_string($con, $_POST['birthday']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    // Set managerID to NULL if no manager is selected
    $managerID = ($_POST['managerID'] == '') ? NULL : intval($_POST['managerID']);

    // Update user query for employeedetail
    $updateQuery = "UPDATE employeedetail SET EmpFname = ?, EmpLName = ?, EmpEmail = ?, EmpContactNo = ?, EmpGender = ?, EmpBirthday = ?, EmpAddress = ?, ManagerID = ? WHERE ID = ?";
    $stmt = $con->prepare($updateQuery);
    $stmt->bind_param("ssssssssi", $fullName, $lastName, $email, $phone, $gender, $birthday, $address, $managerID, $userId);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully');</script>";
        echo "<script>window.location.href='users.php'</script>";
    } else {
        echo "<script>alert('Failed to update user');</script>";
    }
}

// Handle deleting user
if (isset($_POST['deleteUser'])) {
    $userId = $_POST['userId'];

    // Delete user query from employeedetail
    $deleteQuery = "DELETE FROM employeedetail WHERE ID = ?";
    $stmt = $con->prepare($deleteQuery);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully');</script>";
        echo "<script>window.location.href='users.php'</script>";
    } else {
        echo "<script>alert('Failed to delete user');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manage Users</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
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
        .btn-primary:hover, .btn-info:hover, .btn-danger:hover, .btn-warning:hover {
            transform: scale(1.05);
            transition: all 0.2s ease-in-out;
        }
        .btn-primary, .btn-info, .btn-danger, .btn-warning {
            border-radius: 20px;
        }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <?php
            if ($role == 'Super Admin') {
                include_once('includes/sidebar.php');
            } elseif ($role == 'HR') {
                include_once('includes/hr_sidebar.php');
            } elseif ($role == 'Manager') {
                include_once('includes/m_sidebar.php');
            } else {
                echo "<p style='color: red;'>Access Denied: Role not recognized.</p>";
                exit();
            }
        ?>
        <!-- End of Sidebar -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <?php
                    if ($role == 'Super Admin') {
                        include_once('includes/header.php');
                    } elseif ($role == 'HR') {
                        include_once('includes/hr_header.php');
                    } elseif ($role == 'Manager') {
                        include_once('includes/m_header.php');
                    } else {
                        echo "<p style='color: red;'>Access Denied: Role not recognized.</p>";
                        exit();
                    }
                ?>
                <!-- End of Topbar -->

                <!-- User Management Section -->
                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 custom-heading">Manage Users</h1>
                    <div>
                        <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#addUserModal">Add New User</button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Last Name</th>
                                    <th>Emp Code</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($user = $userResult->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $user['ID']; ?></td>
                                        <td><?php echo $user['EmpFname']; ?></td>
                                        <td><?php echo $user['EmpLName']; ?></td>
                                        <td><?php echo $user['EmpCode']; ?></td>
                                        <td><?php echo $user['EmpEmail']; ?></td>
                                        <td><?php echo $user['EmpContactNo']; ?></td>
                                        <td><?php echo $user['EmpGender']; ?></td>
                                        <td>
                                            <!-- Update Button -->
                                            <button class="btn btn-warning" data-toggle="modal" 
                                                    data-target="#updateUserModal<?php echo $user['ID']; ?>">
                                                Update
                                            </button>
                                            <!-- Delete Button -->
                                            <button class="btn btn-danger" data-toggle="modal" 
                                                    data-target="#deleteUserModal<?php echo $user['ID']; ?>">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Update User Modal -->
                                    <div class="modal fade" id="updateUserModal<?php echo $user['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateUserModalLabel<?php echo $user['ID']; ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateUserModalLabel<?php echo $user['ID']; ?>">Update User</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="userId" value="<?php echo $user['ID']; ?>" />
                                                        <!-- Form Fields for User Update -->
                                                        <div class="form-group">
                                                            <label for="fullName">Full Name</label>
                                                            <input type="text" name="fullName" class="form-control" value="<?php echo $user['EmpFname']; ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="lastName">Last Name</label>
                                                            <input type="text" name="lastName" class="form-control" value="<?php echo $user['EmpLName']; ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="empCode">Emp Code</label>
                                                            <input type="text" name="empCode" class="form-control" value="<?php echo $user['EmpCode']; ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" name="email" class="form-control" value="<?php echo $user['EmpEmail']; ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="phone">Phone</label>
                                                            <input type="text" name="phone" class="form-control" value="<?php echo $user['EmpContactNo']; ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="birthday">Birthday</label>
                                                            <input type="date" name="birthday" class="form-control" value="<?php echo $user['EmpBirthday']; ?>" required />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="address">Address</label>
                                                            <textarea name="address" class="form-control" required><?php echo $user['EmpAddress']; ?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="gender">Gender</label>
                                                            <select name="gender" class="form-control" required>
                                                                <option value="Male" <?php echo ($user['EmpGender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                                <option value="Female" <?php echo ($user['EmpGender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                                <option value="Other" <?php echo ($user['EmpGender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="managerID">Manager</label>
                                                            <select name="managerID" class="form-control">
                                                                <option value="">Select Manager</option>
                                                                <?php foreach ($managers as $id => $managerName) { ?>
                                                                    <option value="<?php echo $id; ?>" <?php echo ($user['ManagerID'] == $id) ? 'selected' : ''; ?>><?php echo $managerName; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="updateUser" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Delete User Modal -->
                                    <div class="modal fade" id="deleteUserModal<?php echo $user['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel<?php echo $user['ID']; ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteUserModalLabel<?php echo $user['ID']; ?>">Delete User</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="userId" value="<?php echo $user['ID']; ?>" />
                                                        <p>Are you sure you want to delete this user?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="deleteUser" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add User Modal -->
                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <!-- Form Fields for User Add -->
                                    <div class="form-group">
                                        <label for="fullName">Full Name</label>
                                        <input type="text" name="fullName" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Last Name</label>
                                        <input type="text" name="lastName" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="empCode">Emp Code</label>
                                        <input type="text" name="empCode" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="joiningDate">Joining Date</label>
                                        <input type="date" name="joiningDate" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select name="role" class="form-control" required>
                                            <option value="Super Admin">Super Admin</option>
                                            <option value="Manager">Manager</option>
                                            <option value="HR">HR</option>
                                            <option value="Agent">Agent</option>
                                            <option value="Collection Executive">Collection Executive</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dept">Department</label>
                                        <select name="dept" class="form-control" required>
                                            <?php while ($dept = $deptResult->fetch_assoc()) { ?>
                                                <option value="<?php echo $dept['Department']; ?>"><?php echo $dept['Department']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <select name="designation" class="form-control" required>
                                            <?php while ($designation = $designationResult->fetch_assoc()) { ?>
                                                <option value="<?php echo $designation['Designation']; ?>"><?php echo $designation['Designation']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="managerID">Manager</label>
                                        <select name="managerID" class="form-control">
                                            <option value="">Select Manager</option>
                                            <?php foreach ($managers as $id => $managerName) { ?>
                                                <option value="<?php echo $id; ?>"><?php echo $managerName; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" name="addUser" class="btn btn-primary">Add User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End of User Management Section -->
            </div>
        </div>
    </div>
</body>
</html>
