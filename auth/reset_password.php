<?php

require_once "../config/Dbconn.php";


session_start(); // start a new session

// check if the user is logged in
if (!isset($_SESSION['username'])) {
  header('Location: signin.php');
  exit();
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
  <style>
    #message {
      display: none;
      background: #f1f1f1;
      position: relative;
      padding: 5px;
      margin-top: 10px;
    }

    /* Add a green text color and a checkmark when the requirements are right */
    .valid {
      color: #015198;
    }

    #msg {
      color: red;
    }

    #message1 {
      display: none;
      background: #f1f1f1;
      position: relative;
      padding: 5px;
      margin-top: 10px;
    }

    /* Add a red text color and an "x" when the requirements are wrong */

    input:focus:invalid {
      border: 1px solid red;
      box-shadow: 0 0 4px red;
    }

    input:focus:valid {
      border: 1px solid green;
      box-shadow: 0 0 4px green;
    }
  </style>
</head>

<body>
  <?php
  // Check if the reset password form has been submitted

  // Get the email and verification code from the URL
  @$email = mysqli_real_escape_string($conn, $_GET['email']);
  @$code = mysqli_real_escape_string($conn, $_GET['code']);

  // Get the new password entered by the user
  @$new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

  // Query the database to check if the email and verification code match
  $query = "SELECT * FROM users WHERE user_name='$email' AND verification_code='$code' AND user_role='Web Admin'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {

  ?>

    <div class="container-fluid position-relative d-flex p-0 bg-dark">
      <!-- Spinner Start 
              <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
                  <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                      <span class="sr-only">Loading...</span>
                  </div>
              </div>Spinner End -->


      <!-- Sign In Start -->
      <div class="container-fluid">
        <?php
        if (isset($_POST["update"])) {
          @$x = $_POST['password'];
          @$c = $_POST['cpassword'];
          if (strlen($x) == 0 && strlen($c) == 0) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="fa fa-exclamation-circle me-2"></i>Please Provide Password!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
          } else {
            if ($x == $c) {


              $sql = "UPDATE `users` SET `password` = '$x' WHERE `users`.`user_role` = 'Admin'";
              mysqli_query($conn, $sql);
              header("Location: signin.php");
            } else {
            ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>Sorry, Your Password are not same!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
        <?php
            }
          }
        }
        ?>
        <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
          <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 border rounded">
            <div class="transparent rounded p-4 p-sm-5 my-4 mx-3 well">
              <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="text-white">Forget Password</h3>
              </div>
              <form action="" method="post" name="myform">
                <div class="form-floating mb-4">
                  <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                  <label for="floatingPassword">New Password</label>

                </div>
                <div class="form-floating mb-4">
                  <input type="password" name="cpassword" class="form-control" id="cpassword" placeholder="Password">
                  <label for="floatingPassword">Confirm Password</label>
                </div>
                <div id="message1" class="mb-2 ">
                  <span id="notsame" class="invalid">Not Same</span>
                </div>
                <div id="message" class="mb-2">
                  <h6 class="text-primary">Password must contain the following:</h6>
                  <span id="capital" class="invalid">A <b>capital (uppercase)</b> letter</span><br>
                  <span id="letter" class="invalid">A <b>lowercase</b> letter</span><br>
                  <span id="number" class="invalid">A <b>number</b></span><br>
                  <span id="length" class="invalid">Minimum <b>8 characters</b> Maximum <b>12 characters</b></span>
                  <div id="msg">your password is too long</div>
                </div>
                <button name="update" class="btn btn-primary py-3 w-100 mb-4 text-white">Reset Password</button>

            </div>
          </div>
        </div>
        </form>
      </div>
      <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->

    <script>
      var myInput = document.getElementById("password");
      var myInput1 = document.getElementById("cpassword");
      var letter = document.getElementById("letter");
      var capital = document.getElementById("capital");
      var number = document.getElementById("number");
      var length = document.getElementById("length");

      // When the user clicks on the password field, show the message box
      myInput.onfocus = function() {
        document.getElementById("message").style.display = "block";
        document.getElementById("msg").style.display = "none";
      }

      // When the user clicks outside of the password field, hide the message box
      myInput.onblur = function() {
        document.getElementById("message").style.display = "none";
        document.getElementById("msg").style.display = "none";
      }

      // When the user starts to type something inside the password field
      myInput.onkeyup = function() {
        // Validate lowercase letters
        var lowerCaseLetters = /[a-z]/g;
        if (myInput.value.match(lowerCaseLetters)) {
          letter.classList.remove("invalid");
          letter.classList.add("valid");
        } else {
          letter.classList.remove("valid");
          letter.classList.add("invalid");
        }

        // Validate capital letters
        var upperCaseLetters = /[A-Z]/g;
        if (myInput.value.match(upperCaseLetters)) {
          capital.classList.remove("invalid");
          capital.classList.add("valid");
        } else {
          capital.classList.remove("valid");
          capital.classList.add("invalid");
        }

        // Validate numbers
        var numbers = /[0-9]/g;
        if (myInput.value.match(numbers)) {
          number.classList.remove("invalid");
          number.classList.add("valid");
        } else {
          number.classList.remove("valid");
          number.classList.add("invalid");
        }

        // Validate length
        if (myInput.value.length >= 8) {
          length.classList.remove("invalid");
          length.classList.add("valid");
        }
        if (myInput.value.length >= 12) {
          length.classList.remove("valid");
          length.classList.add("invalid");
          document.getElementById("msg").style.display = "block";
        }
      }

      myInput1.onkeyup = function() {
        if (myInput1.value.match(myInput)) {
          notsame.classList.remove("invalid");
          notsame.classList.add("valid");
        } else {
          notsame.classList.remove("valid");
          notsame.classList.add("invalid");
          document.getElementById("message1").style.display = "block";
        }
      }
    </script>
  <?php

  } else {
    // Display an error message if the email and verification code do not match

  ?>
    <div class="container-fluid">
      <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh; border:2px #015198 solid;">
        <div class="col-lg-12 col-xl-6 text-center">
          <h1> Invalid email address or verification code. </h1>
          <a href="verify.php" class="btn btn-outline-primary px-3">
            <div class="d-inline-flex btn-sm-square bg-primary text-white rounded-circle ">
              <i class="fa fa-arrow-left"> </i>
            </div>
            Try Agin
          </a>
        </div>
      </div>
    </div>
  <?php


  }
  ?>

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