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


        <!-- Blank Start -->
        <?php
        if (isset($_POST["submit"])) {
            @$site_name = $_POST['site'];
            @$ph = $_POST['ph'];
            @$email = $_POST['email'];
            @$ad = $_POST['ad'];
            @$mad = $_POST['mad'];
            @$fb = $_POST['fb'];
            @$ins = $_POST['ins'];
            @$tw = $_POST['tw'];
            $sql = "UPDATE `set` SET `site_nam`='$site_name',`phone`='$ph',`of_email`='$email',`fu_ad`='$ad',`mp_ad`='$mad',`fb`='$fb',`insta`='$ins',`twit`='$tw'";
            mysqli_query($conn, $sql);
        ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i><?php echo "Upadted" ?>!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php

        }
        ?>


        <form action="" method="post" enctype="multipart/form-data">
            <div class="container-fluid pt-4 px-5">
                <div class="vh-100 bg-light rounded align-items-center justify-content-center mx-0">
                    <div class="row g-4 p-4">
                        <div class="col-sm-12 col-xl-6">
                            <div class="bg-light rounded h-100 p-4">
                                <h3 class="mb-4 text-primary">Setting</h3>
                                <h5 class="text-primary m-2">Site Name</h5>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="site" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Title Here</label>
                                </div>
                                <h5 class="text-primary m-2">Phone</h5>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="ph" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Title Here</label>
                                </div>
                                <h5 class="text-primary m-2">Organization Email</h5>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="email" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Title Here</label>
                                </div>
                                <h5 class="text-primary m-2">Full Address</h5>
                                <div id="emailHelp" class="form-text" style="color:red;"> <b>*</b> Please Include Province And Country.
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="address" class="form-control" name="ad" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Title Here</label>
                                </div>
                                <h5 class="text-primary m-2">Map Address</h5>
                                <div class="form-floating mb-3">
                                    <input type="map" class="form-control" name="mad" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Title Here</label>
                                </div>

                            </div>
                        </div>
                        <div class="row col-sm-12 col-xl-6 g-5 ">
                            <div class="bg-light rounded h-100 p-4">

                                <h5 class="text-primary m-2">Facebook</h5>
                                <div class="form-floating mb-3">
                                    <input type="link" class="form-control" name="fb" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Title Here</label>
                                </div>
                                <h5 class="text-primary m-2">Instigram</h5>
                                <div class="form-floating mb-3">
                                    <input type="link" class="form-control" name="ins" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Title Here</label>
                                </div>
                                <h5 class="text-primary m-2">Twitter</h5>
                                <div class="form-floating mb-3">
                                    <input type="link" class="form-control" name="tw" id="floatingPassword" placeholder="Write Title Here">
                                    <label for="floatingPassword">Write Here</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating m-2">
                            <input type="submit" name="submit" value="Update" class="btn btn-outline-danger w-100 m-2" style="font-size: 20px;">
                        </div>
                    </div>
                </div>
            </div>
        </form>


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