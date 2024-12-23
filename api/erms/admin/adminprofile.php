<?php
session_start();
error_reporting(E_ALL); // Show all errors to assist with debugging
include('includes/dbconnection.php');

// Ensure the user is logged in
if (empty($_SESSION['aid']) && empty($_SESSION['uid'])) {
    header('location:logout.php');
    exit;
}

    $adminid = $_SESSION['aid'];
    $query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
    $result = mysqli_fetch_array($query);
    $role = $result['Role'];

// Check if the user is Admin or Regular User
if (isset($_SESSION['aid'])) {
    // Admin is logged in
    $userId = $_SESSION['aid'];
    $userType = 'admin';
    $query = "SELECT * FROM tbladmin WHERE ID = ?";
} elseif (isset($_SESSION['uid'])) {
    // Regular user is logged in
    $userId = $_SESSION['uid'];
    $userType = 'user';
    $query = "SELECT * FROM tblusers WHERE ID = ?";
}

// Fetch user details
$stmt = $con->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();

// Update user profile logic
if (isset($_POST['updateProfile'])) {
    $fullName = mysqli_real_escape_string($con, $_POST['fullName']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $birthday = mysqli_real_escape_string($con, $_POST['birthday']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);

    // Determine table and update query based on user type
    if ($userType === 'admin') {
        $updateQuery = "UPDATE tbladmin SET AdminName = ?, Phone = ?, Email = ?, Birthday = ?, Address = ?, Gender = ? WHERE ID = ?";
    } elseif ($userType === 'user') {
        $updateQuery = "UPDATE tblusers SET FullName = ?, Phone = ?, Email = ?, Birthday = ?, Address = ?, Gender = ? WHERE ID = ?";
    }

    // Prepare and execute the update query
    $stmt = $con->prepare($updateQuery);
    if ($stmt === false) {
        echo "<script>alert('Error preparing update query');</script>";
        exit;
    }
    $stmt->bind_param("ssssssi", $fullName, $phone, $email, $birthday, $address, $gender, $userId);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully');</script>";
        echo "<script>window.location.href='adminprofile.php'</script>";
    } else {
        echo "<script>alert('Failed to update profile: " . $stmt->error . "');</script>";
    }
}

// Reset password logic (current password check)
if (isset($_POST['resetPassword'])) {
    $currentPassword = mysqli_real_escape_string($con, $_POST['currentPassword']);
    $newPassword = mysqli_real_escape_string($con, $_POST['newPassword']);

    // Check if the current password matches the stored password for the user
    if (($userType === 'admin' && $currentPassword === $userDetails['Password']) ||
        ($userType === 'user' && $currentPassword === $userDetails['Password'])) {
        
        // Update password query
        if ($userType === 'admin') {
            $resetPasswordQuery = "UPDATE tbladmin SET Password = ? WHERE ID = ?";
        } else {
            $resetPasswordQuery = "UPDATE tblusers SET Password = ? WHERE ID = ?";
        }

        $stmt = $con->prepare($resetPasswordQuery);
        $stmt->bind_param("si", $newPassword, $userId);

        if ($stmt->execute()) {
            echo "<script>alert('Password reset successfully');</script>";
        } else {
            echo "<script>alert('Failed to reset password: " . $stmt->error . "');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Profile</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
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

                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 custom-heading">Profile</h1>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h4>Profile Details</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Full Name</th>
                                    <td><?php echo htmlspecialchars($userDetails[$userType === 'admin' ? 'AdminName' : 'FullName']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo htmlspecialchars($userDetails['Email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo htmlspecialchars($userDetails['Phone']); ?></td>
                                </tr>
                                <tr>
                                    <th>Birthday</th>
                                    <td><?php echo htmlspecialchars($userDetails['Birthday']); ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo htmlspecialchars($userDetails['Address']); ?></td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td><?php echo htmlspecialchars($userDetails['Gender']); ?></td>
                                </tr>
                            </table>

                            <!-- Update Profile Button -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#updateProfileModal">Update Profile</button>
                            <button class="btn btn-info" data-toggle="modal" data-target="#resetPasswordModal">Reset Password</button>
                        </div>
                    </div>

                    <!-- Update Profile Modal -->
                    <div class="modal fade" id="updateProfileModal" tabindex="-1" role="dialog" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateProfileModalLabel">Update Profile</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" name="fullName" class="form-control" value="<?php echo htmlspecialchars($userDetails[$userType === 'admin' ? 'AdminName' : 'FullName']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($userDetails['Email']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($userDetails['Phone']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Birthday</label>
                                            <input type="date" name="birthday" class="form-control" value="<?php echo htmlspecialchars($userDetails['Birthday']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($userDetails['Address']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control" required>
                                                <option value="Male" <?php echo $userDetails['Gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                                <option value="Female" <?php echo $userDetails['Gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                                <option value="Other" <?php echo $userDetails['Gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="updateProfile" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Reset Password Modal -->
                    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form method="POST" action="">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" name="currentPassword" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" name="newPassword" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="resetPassword" class="btn btn-info">Reset Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
