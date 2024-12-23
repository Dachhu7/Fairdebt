<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Fairdebt Solutions">
  <meta name="author" content="">

  <title>ERMS | Home Page</title>

  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <!-- Custom styles for modifications -->
  <link href="css/custom.css" rel="stylesheet">
  <style>
    /* custom.css */
    body {
      background-color: #f8f9fc;
    }

    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .text-center {
      margin-top: 2rem;
    }

    a.stretched-link {
      text-decoration: none;
      color: inherit;
    }

    a.stretched-link:hover {
      color: #0056b3;
      text-decoration: underline;
    }
    
    h1 {
      padding-top: 6%; 
    }

  </style>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="text-center my-5">
            <h1 class="h3 text-gray-800 font-weight-bold">Fairdebt Solutions Employee Circle</h1>
          </div>

          <!-- Cards Section -->
          <div class="row justify-content-center">

            <!-- User Signin Card -->
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-3">
                <div class="card-body text-center">
                  <a href="loginerms.php" class="stretched-link">
                    <div class="text-primary font-weight-bold text-uppercase mb-2">User Signin</div>
                    <i class="fas fa-user fa-3x text-gray-300"></i>
                  </a>
                </div>
              </div>
            </div>

            <!-- Admin Login Card -->
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-3">
                <div class="card-body text-center">
                  <a href="admin/" class="stretched-link">
                    <div class="text-info font-weight-bold text-uppercase mb-2">Admin Login</div>
                    <i class="fas fa-user-cog fa-3x text-gray-300"></i>
                  </a>
                </div>
              </div>
            </div>

          </div>
          <!-- End of Cards Section -->

        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include_once('includes/footer.php'); ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript -->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages -->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
