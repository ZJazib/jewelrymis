<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ZMIS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Admin Panel" name="keywords">
    <meta content="Admin Panel" name="description">

    <!-- Favicon -->
    <link href="favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Sidebar Start -->
    <?php
    require_once "../config/Dbconn.php";

    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if (isset($_POST['insert'])) {
        $name = $_POST['name'];
        $ref = $_POST['ref'];
        $price = $_POST['price'];
        $gold = $_POST['gold'];
        if($gold == 0){

        $crea = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id', '$ref', '0', '$price', '0', 'Customer', 'Cash', 'Credit', '0')";
        mysqli_query($conn, $crea);

        $stah = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id', '$ref', '0', '0', '$price', 'Credit', 'Cash', 'Receipt <br> ($price$ BY Reference NO. $ref)')";
        mysqli_query($conn, $stah);

        $msg = '
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i> Supplier Credit Money Added!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ';
        }
        else {
            $crea = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id', '$ref', '0', '0', '$gold', 'Customer', 'Cash', 'Credit', '0')";
        mysqli_query($conn, $crea);

        $stah = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id', '$ref', '0', '$gold', '0', 'Credit', 'Cash', 'JEWELLERY ISSUED <br> ($gold GMS BY Reference NO. $ref)')";
        mysqli_query($conn, $stah);

        $msg = '
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i> Supplier Credit Money Added!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ';
        }
    }

    $sqlk = "SELECT * FROM `customer` WHERE id = '$id'";
    $resk = mysqli_query($conn, $sqlk);
    $empp = mysqli_fetch_assoc($resk);

    $sqli = "SELECT SUM(amount) as total FROM `account` WHERE cus_id = '$id'";
    $resi = mysqli_query($conn, $sqli);
    $cusi = mysqli_fetch_assoc($resi);

    $sql = "SELECT SUM(amo_cre) as totalc FROM `account` WHERE cus_id = '$id'";
    $res = mysqli_query($conn, $sql);
    $cus = mysqli_fetch_assoc($res);
    ?>
    <div class="container-fluid pt-4 px-5">
        <div class="rounded align-items-center bg-light justify-content-center mx-0">
            <div class="container py-2">
                <?php if (isset($msg)) echo $msg; ?>
                
                <div class="row">
                    <div class="col-md-3 bg-dark rounded m-3">
                        <h5 class="bg-white p-2 text-primary text-center">Personnel Information</h5>
                        <h6 class="text-white px-4">Role: <span><?= htmlspecialchars($empp['role']) ?></span></h6>
                        <h6 class="text-white px-4">Name: <span><?= htmlspecialchars($empp['name']) ?></span></h6>
                        <h6 class="text-white px-4">Location: <span><?= htmlspecialchars($empp['loc']) ?></span></h6>
                    </div>

                    <div class="col-md-4 bg-dark rounded m-3">
                        <h5 class="bg-white p-2 text-primary text-center">Total Money In Account</h5>
                        <h5 class="text-white px-4">Debit Money: <span><?= number_format($cusi['total'], 3) ?></span></h5>
                        <h5 class="text-white px-4">Credit Money: <span><?= number_format($cus['totalc'], 3) ?></span></h5>
                    </div>
                </div>

                <div class="col-md-12">
                    <a class="text-center btn btn-success my-3 w-25" href="../monthly_journal/mjournal.php">
                        <i class="fa fa-circle-arrow-left w-100"></i><b> Monthly Journal</b>
                    </a>
                    <a class="text-center btn btn-outline-dark m-3 w-25" href="../index.php">
                        <i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b>
                    </a>

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header text-center bg-primary">
                                <h3 class="text-white mt-2">Add Customer Discount Credit Money and gold </h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 my-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="name">Name</label>
                                            <input type="text" class="form-control" id="name" value="<?= htmlspecialchars($empp['name']) ?>" name="name" placeholder="Write Name Here">
                                        </div>
                                    </div>
                                    <div class="col-md-4 my-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="ref">Reference Number</label>
                                            <input type="text" class="form-control" id="ref" name="ref" placeholder="Write Reference Here">
                                        </div>
                                    </div>
                                    <div class="col-md-4 my-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="price">Amount</label>
                                            <input type="text" class="form-control" id="price" name="price" placeholder="Enter Amount Here">
                                        </div>
                                    </div>
                                    <div class="col-md-4 my-2">
                                        <div class="form-group">
                                            <label class="text-primary" for="price">Gold GMS</label>
                                            <input type="text" class="form-control" id="gold" name="gold" placeholder="Enter Gold Here">
                                        </div>
                                    </div>
                                    <div class="form-group text-center my-2">
                                        <button type="submit" name="insert" class="btn btn-primary">
                                            <i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i>Insert
                                        </button>
                                        <button type="reset" name="clear" class="btn btn-danger">
                                            <i class="fa fa-xmark rounded-circle bg-white p-1 text-primary mx-1"></i>Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
>
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