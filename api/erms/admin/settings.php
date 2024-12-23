<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

// Ensure the user is logged in
if (empty($_SESSION['aid']) && empty($_SESSION['uid'])) {
    header('location:logout.php');
    exit;
}

// Ensure the user is an admin
if (!isset($_SESSION['aid'])) {
    header('location:profile.php');
    exit;
}

// Fetch current settings for display
$settingsQuery = "SELECT * FROM tblsettings WHERE id = 1"; // Assuming you have a settings table
$settingsResult = $con->query($settingsQuery);
$currentSettings = $settingsResult->fetch_assoc();

// Handle settings update
if (isset($_POST['updateSettings'])) {
    $siteTitle = mysqli_real_escape_string($con, $_POST['siteTitle']);
    $siteDescription = mysqli_real_escape_string($con, $_POST['siteDescription']);
    $siteLogo = $_FILES['siteLogo']['name'];
    $registrationEnabled = isset($_POST['registrationEnabled']) ? 1 : 0;
    $maintenanceMode = isset($_POST['maintenanceMode']) ? 1 : 0;

    // Handle file upload for logo
    if ($siteLogo) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["siteLogo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if file is a valid image
        if (getimagesize($_FILES["siteLogo"]["tmp_name"]) === false) {
            $uploadOk = 0;
            echo "<script>alert('File is not an image.');</script>";
        }

        // Check file size (max 2MB)
        if ($_FILES["siteLogo"]["size"] > 2000000) {
            $uploadOk = 0;
            echo "<script>alert('Sorry, your file is too large.');</script>";
        }

        // Allow certain file formats (PNG, JPG, JPEG, GIF)
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $uploadOk = 0;
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<script>alert('Sorry, your file was not uploaded.');</script>";
        } else {
            // Move the uploaded file and update logo if successful
            if (move_uploaded_file($_FILES["siteLogo"]["tmp_name"], $targetFile)) {
                // If logo is successfully uploaded, update in the settings
                $currentSettings['siteLogo'] = $siteLogo;
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }
    }

    // Update settings in the database
    $updateSettingsQuery = "UPDATE tblsettings SET siteTitle = ?, siteDescription = ?, siteLogo = ?, registrationEnabled = ?, maintenanceMode = ? WHERE id = 1";
    $stmt = $con->prepare($updateSettingsQuery);
    $stmt->bind_param("sssss", $siteTitle, $siteDescription, $siteLogo, $registrationEnabled, $maintenanceMode);

    if ($stmt->execute()) {
        echo "<script>alert('Settings updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update settings: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Settings</title>
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
        <?php include_once('includes/sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include_once('includes/header.php'); ?>

                <div class="container-fluid mt-4">
                    <h1 class="h3 mb-4 custom-heading">Settings</h1>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h4>Site Settings</h4>
                            <form method="POST" action="settings.php" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="siteTitle">Site Title</label>
                                    <input type="text" name="siteTitle" class="form-control" value="<?php echo htmlspecialchars($currentSettings['siteTitle']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="siteDescription">Site Description</label>
                                    <input type="text" name="siteDescription" class="form-control" value="<?php echo htmlspecialchars($currentSettings['siteDescription']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="siteLogo">Site Logo</label>
                                    <input type="file" name="siteLogo" class="form-control">
                                    <small>Current logo: <img src="uploads/<?php echo htmlspecialchars($currentSettings['siteLogo']); ?>" alt="Logo" width="100"></small>
                                </div>
                                <div class="form-group">
                                    <label for="registrationEnabled">Enable User Registration</label>
                                    <input type="checkbox" name="registrationEnabled" <?php echo $currentSettings['registrationEnabled'] ? 'checked' : ''; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="maintenanceMode">Maintenance Mode</label>
                                    <input type="checkbox" name="maintenanceMode" <?php echo $currentSettings['maintenanceMode'] ? 'checked' : ''; ?>>
                                </div>

                                <button type="submit" name="updateSettings" class="btn btn-primary">Update Settings</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
