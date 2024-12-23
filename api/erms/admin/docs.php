<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['aid']) == 0) {
    header('location:logout.php');
    exit;
}

$adminid = $_SESSION['aid'];
$query = mysqli_query($con, "SELECT Role FROM tbladmin WHERE ID='$adminid'");
$result = mysqli_fetch_array($query);
$role = $result['Role'];

// Add a new document
if (isset($_POST['addDoc'])) {
    $docTitle = mysqli_real_escape_string($con, $_POST['docTitle']);
    $docDescription = mysqli_real_escape_string($con, $_POST['docDescription']);
    $docDate = mysqli_real_escape_string($con, $_POST['docDate']);
    $docType = mysqli_real_escape_string($con, $_POST['docType']);
    $tags = mysqli_real_escape_string($con, $_POST['tags']);
    $createdBy = $_SESSION['aid']; // Admin ID from session

    if (!empty($docTitle) && !empty($docDescription) && !empty($docType)) {
        // Handle file upload
        $filePath = '';
        if (isset($_FILES['docFile']['name']) && $_FILES['docFile']['name'] != '') {
            $fileName = basename($_FILES['docFile']['name']);
            $targetDir = "uploads/"; // Directory for file uploads
            $targetFile = $targetDir . time() . "_" . $fileName;

            // Check and move the uploaded file
            if (move_uploaded_file($_FILES['docFile']['tmp_name'], $targetFile)) {
                $filePath = $targetFile;
            } else {
                echo "<script>alert('Failed to upload file.');</script>";
                exit;
            }
        }

        $query = mysqli_query($con, "INSERT INTO docs (Title, Description, DocDate, Type, Tags, CreatedBy, FilePath) 
                                     VALUES ('$docTitle', '$docDescription', '$docDate', '$docType', '$tags', '$createdBy', '$filePath')");
        if ($query) {
            echo "<script>alert('Document added successfully');</script>";
            echo "<script>window.location.href='docs.php'</script>";
        } else {
            echo "<script>alert('Failed to add document');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all the required fields');</script>";
    }
}

// Delete a document
if (isset($_GET['delid'])) {
    $docid = intval($_GET['delid']);
    $query = mysqli_query($con, "DELETE FROM docs WHERE ID='$docid'");
    if ($query) {
        echo "<script>alert('Document deleted successfully');</script>";
        echo "<script>window.location.href='docs.php'</script>";
    } else {
        echo "<script>alert('Failed to delete document');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Docs</title>
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
        .doc-table th, .doc-table td {
            text-align: center;
            vertical-align: middle;
        }
        .action-buttons {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 10px;
        }
        .dropdown {
            margin-left: 10px;
        }
        .dropdown-menu {
            min-width: 200px;
        }
        .dropdown-item {
            font-size: 14px;
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
                <div class="container-fluid">
                    <h1 class="h3 mb-4 custom-heading">Documents</h1>
                    <div class="action-buttons">
                        <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#addDocModal">Add Document</button>
                        <div class="dropdown mb-4">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Open Google Product</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="https://docs.google.com/document" target="_blank">Google Docs</a>
                                <a class="dropdown-item" href="https://docs.google.com/spreadsheets" target="_blank">Google Sheets</a>
                                <a class="dropdown-item" href="https://docs.google.com/presentation" target="_blank">Google Slides</a>
                                <a class="dropdown-item" href="https://forms.google.com" target="_blank">Google Forms</a>
                                <a class="dropdown-item" href="https://drive.google.com" target="_blank">Google Drive</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered doc-table">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Tags</th>
                                    <th>Date</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM docs ORDER BY DocDate DESC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_assoc($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlentities($row['Title']); ?></td>
                                        <td><?php echo htmlentities($row['Description']); ?></td>
                                        <td><?php echo htmlentities($row['Type']); ?></td>
                                        <td><?php echo htmlentities($row['Tags']); ?></td>
                                        <td><?php echo htmlentities($row['DocDate']); ?></td>
                                        <td>
                                            <?php if ($row['FilePath']) { ?>
                                                <a href="<?php echo htmlentities($row['FilePath']); ?>" target="_blank">View File</a>
                                            <?php } else { ?>
                                                N/A
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="docs.php?delid=<?php echo $row['ID']; ?>"
                                                class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this document?');">Delete</a>
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

    <!-- Add Document Modal -->
    <div class="modal fade" id="addDocModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Document</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="docTitle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="docDescription" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select name="docType" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="Document">Document</option>
                                <option value="Spreadsheet">Spreadsheet</option>
                                <option value="Slide">Slide</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tags (comma-separated)</label>
                            <input type="text" name="tags" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Document Date</label>
                            <input type="date" name="docDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Upload File</label>
                            <input type="file" name="docFile" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="addDoc">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
</body>

</html>
