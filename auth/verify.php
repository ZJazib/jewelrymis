<?php
require_once "../config/Dbconn.php";


// Check if the form has been submitted
if (isset($_POST['submit'])) {

    // Get the email entered by the user
    $email = mysqli_real_escape_string($conn, Test_input($_POST['email']));

    // Query the database to check if the email exists
    $query = "SELECT * FROM users WHERE user_name='$email'";
    $result = mysqli_query($conn, $query);
    $error = "";
    $sucess = "";
    if (mysqli_num_rows($result) > 0) {

        // Generate a random verification code
        $verification_code = substr(md5(rand()), 0, 8);

        // Update the user's verification code in the database
        $update_query = "UPDATE users SET verification_code='$verification_code' WHERE user_name='$email' AND user_role='Web Admin'";
        mysqli_query($conn, $update_query);

        // Send an email to the user with a link to reset their password
        $to = $email;
        $subject = "Reset Password";
        $message = "Please click the following link to reset your password: http://www.pyecso.org.af/Admin/reset_password.php?email=$email&code=$verification_code";
        $headers = "From: ziarahmanabid14@gmail.com" . "\r\n" .
            "Reply-To: ziarahmanabid14@gmail.com" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        @mail($to, $subject, $message, $headers);

        // Display a message to the user
        $sucess = "<h5 class=' alert text-center bg-info fade show text-dark'>A messege was send to your Email With instruction!
            </h5>";
    } else {
        // Display an error message if the email does not exist
        $error = "<h5 class='alert text-center bg-danger fade show text-white'>Invalid email!
            </h5>";
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

<body>
    <div class="container-fluid position-relative d-flex p-0 bg-white">
        <!-- Spinner Start  -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sign In Start -->
        <div class="container-fluid ">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="bg-light col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 border rounded">
                    <div class="rounded p-4 p-sm-5 my-4 mx-3 well">
                        <?php
                        if (isset($error)) {
                            echo $error;
                        }
                        if (isset($sucess)) {
                            echo $sucess;
                        }
                        ?>
                        <h3 class="text-primary mb-3">Verify Your Email</h3>
                        <form action="" method="post" name="myForm">
                            <div class="form-floating mb-3">
                                <div class="input-group mb-4">
                                    <i class="fa fa-envelope p-3 bg-primary text-white"></i><input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="submit" class="btn btn-primary text-white px-5 py-3 mb-4">Verify</button>
                            </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>