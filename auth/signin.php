<?php
require_once "../config/Dbconn.php";

// Initialize variables with default values
$email = $password = "";
$emailErr = $passwordErr = "";
$loginError = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate email
  if (empty($_POST["email"])) {
    $emailErr = "<h5 class='alert text-center bg-danger fade show text-white'>Please Write Email!</h5>";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "<h5 class='alert text-center bg-danger fade show text-white'>Invalid email Format!</h5>";
    }
  }

  // Validate password
  if (empty($_POST["password"])) {
    $passwordErr = "<h5 class='alert text-center bg-danger fade show text-white'>Please write Password!</h5>";
  } else {
    $password = test_input($_POST["password"]);
  }

  // If there are no validation errors, try to log in
  if (empty($emailErr) && empty($passwordErr)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name=? AND user_role='Admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      if (password_verify($password, $row['password'])) {
        // If login is successful, redirect to dashboard
        session_start();
        $_SESSION["username"] = $email;
        header("Location: ./index.php");
        exit();
      } else {
        $loginError = "<h5 class='alert text-center bg-danger fade show text-white'>Incorrect Email Or Password!</h5>";
      }
    } else {
      $loginError = "<h5 class='alert text-center bg-danger fade show text-white'>Incorrect Email Or Password!</h5>";
    }
    $stmt->close();
  }
}

function test_input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Reset Password</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="PYECSO" name="keywords">
  <meta content="PYECSO" name="description">

  <!-- Favicon -->
  <link href="../favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Stylesheet -->
  <link href="../css/style.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

</head>
<style>
  .bg {
    background-image: url(img/Gostaresh\ \(15\).jpg);
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
  }

  .bgc {
    background-color: #c0800072;
  }
</style>

<body>

  <div class="container-fluid position-relative d-flex p-0">
    <!-- Spinner Start 
        <div id="spinner" class="show bg-primary position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>Spinner End -->


    <!-- Sign In Start -->

    <div class="container-fluid bg">
      <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh; border:2px #015198 solid;">
        <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 ">

          <div class="mb-3 text-center">
            <img src="img/Capture.png" alt="" class="w-25">
            <h2 class="text-light text-center">MIS</h2>
          </div>
          <div class="bgc p-4 p-sm-5 my-4 mx-3 border rounded">
            <?php echo $loginError; ?>
            <span class="text-danger"><?php echo $emailErr; ?></span>
            <span class="text-danger"><?php echo $passwordErr; ?></span>
            <h3 class="text-light text-center mb-4">Login</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="myForm">
              <div class="input-group mb-4">
                <i class="fa fa-envelope p-3 bg-primary text-white"></i><input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php echo $email; ?>">
              </div>
              <div class="input-group mb-4">
                <i class="fa fa-key p-3 bg-primary text-white"></i><input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <a href="verify.php" class="text-white">Forgot Password?</a>
                <input type="submit" name="submit" class="btn btn-light px-5 py-3 mb-4" value="Login">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Sign In End -->
  </div>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../lib/chart/chart.min.js"></script>
  <script src="../lib/easing/easing.min.js"></script>
  <script src="../lib/waypoints/waypoints.min.js"></script>
  <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="../lib/tempusdominus/js/moment.min.js"></script>
  <script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
  <script src="../ib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

  <!-- Template Javascript -->
  <script src="../js/main.js"></script>
</body>

</html>