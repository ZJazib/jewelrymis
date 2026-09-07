<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ZMIS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Admin Penal" name="keywords">
    <meta content="Admin Penal" name="description">

    <!-- Favicon -->
    <link href="favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

    <!-- Template Stylesheet -->
    <script src="../ckeditor/ckeditor.js"></script>
    <link href="../css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <!-- Sidebar Start -->
    <?php
    require_once "../config/Dbconn.php";
    ?>

    <!-- Navbar End -->
    <?php
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Check if required fields are not empty
        if (empty($_POST['name']) && empty($_POST['Username']) && empty($_FILES['fileToUpload']['name'])) {
            echo $error = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i>Fields required!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        }
        if (empty($error)) {


            if (isset($_POST["Update"])) {
                include "upload.php";
                if ($uploadOk == 0) {
    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>Sorry, your file was not Uploaded!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

                    ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i><?php echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded."; ?>!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                        @$x = $_POST['name'];
                        @$y = $_POST['Username'];
                        @$s = $_POST['select'];
                        $sql = "UPDATE `users` SET `user_name1`='$x',`user_name`='$y',`user_img`='$target_file' WHERE users . user_role='Web Admin'";
                        mysqli_query($conn, $sql);
                        ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i><?php echo " Updated" ?>!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>Sorry, your file was not Updated!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
    <?php
                    }
                }
            }
        }
    }
    $sql = "SELECT * FROM users where user_role='Admin'";
    $res = mysqli_query($conn, $sql);
    $users = mysqli_fetch_assoc($res);
    ?>
    <!-- Update Form -->
    <div class="container-fluid pt-4 px-5">
        <div class="rounded align-items-center justify-content-center mx-0">
            <div class="bg-light rounded h-100 p-4">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="bg-light rounded h-100 p-4">
                        <h3 class="mb-4 text-primary">Edit Profile</h3>
                        <h5 class="text-primary m-2">Name</h5>
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="floatingPassword" value="<?= $users['user_name1'] ?>" placeholder="Write name Here" required>
                            <label for="floatingPassword">Write name Here</label>
                        </div>
                        <h5 class="text-primary m-2">Email</h5>
                        <div class="form-floating mb-3">
                            <input type="Email" name="Username" value="<?= $users['user_name'] ?>" class="form-control" id="floatingPassword" placeholder="name@example.com" required>
                            <label for="floatingPassword">Write Email Here</label>
                        </div>
                        <h5 class="text-primary m-2"><i class="fa-solid fa-upload text-primary"></i> Image</h5>
                        <div id="emailHelp" class="form-text text-dark">You Can <b>Only</b> Upload <b style="color:red;">PNG JPG JPEG GIF</b> Image Files.
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="file" id="fileToUpload" name="fileToUpload" required>
                        </div>
                        <div class="form-floating m-2">
                            <input type="submit" name="Update" value="Update" class="btn btn-outline-success w-100 m-2" style="font-size: 20px;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>