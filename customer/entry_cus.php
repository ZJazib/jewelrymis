<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ZMIS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Admin Penal" name="keywords">
    <meta content="Admin Penal" name="description">

    <!-- Favicon -->
    <link href="../favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Libraries Stylesheet -->
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

    <!-- Template Stylesheet -->

    <link href="../css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <!-- Sidebar Start -->
    <?php
    require "../config/Dbconn.php";
    if (isset($_POST['insert'])) {
        $id =  $_GET['id'];
        $sqlcus = "SELECT * FROM `customer` where id ='$id'";
        $rescus = mysqli_query($conn, $sqlcus);
        $empp = mysqli_fetch_assoc($rescus);
        $role = $empp['role'];
        $ref = $_POST['ref'];
        $bill = $_POST['bill'];
        $type = $_POST['type'];
        $totalg = $_POST['totalg'];

        $amoc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$totalg','$role','Cash','Credit','0')";
        mysqli_query($conn, $amoc);
        if ($type == "Debit") {
            $amock = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','0','$role','Cash','Debit','0')";
            mysqli_query($conn, $amock);
            $stadd = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$bill','Debit','$Paid', 'Data Entry of $name ')";
            mysqli_query($conn, $stadd);
        }
        if ($type == "Credit") {
            $amock = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$bill','0','$role','Cash','Credit','0')";
            mysqli_query($conn, $amock);

            $staddk = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$bill','Credit','$Paid', 'Data Entry of $name')";
            mysqli_query($conn, $staddk);
        }

        $sta = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','$totalg','$totalpure','0','Credit','$Paid', '$totalpure GOLD Paid TO $ref FOR $name GROSS WT- $totalg GMS')";
        mysqli_query($conn, $sta);
    }
    $id = $_GET['id'];
    $sqlcus = "SELECT * FROM `customer` where id =" . $_GET['id'];
    $rescus = mysqli_query($conn, $sqlcus);
    $empp = mysqli_fetch_assoc($rescus);

    $sqli = "SELECT SUM(amount) as total FROM `account` where cus_id =" . $_GET['id'];
    $resi = mysqli_query($conn, $sqli);
    $cusi = mysqli_fetch_assoc($resi);
    $sql = "SELECT SUM(amo_cre) as totalc FROM `account` where cus_id =" . $_GET['id'];
    $res = mysqli_query($conn, $sql);
    $cus = mysqli_fetch_assoc($res);
    $sqlcc = "SELECT SUM(cre_tpurity) as totalcc FROM `credit` where cre_cus=" . $_GET['id'];
    $resc = mysqli_query($conn, $sqlcc);
    $cusc = mysqli_fetch_assoc($resc);

    $sqlg = "SELECT SUM(dep_tpurity) as totalg FROM `debit` where dep_cus=" . $_GET['id'];
    $resg = mysqli_query($conn, $sqlg);
    $cusg = mysqli_fetch_assoc($resg);

    $sqlkg = "SELECT SUM(gold) as totalkg FROM `account` where `type`='Debit' and cus_id=" . $_GET['id'];
    $reskg = mysqli_query($conn, $sqlkg);
    $cuskg = mysqli_fetch_assoc($reskg);

    ?>

    <div class="container-fluid ">
        <div class="rounded align-items-center bg-light justify-content-center mx-0">
            <div class="container py-5 ">
                <div class="row">
                    <?php if (isset($ero)) {
                        @$ero;
                    }
                    if (isset($er)) {
                        @$er;
                    }
                    ?>
                    <div class="col-md-3 bg-dark rounded m-3 ">
                        <h5 class="bg-white p-2 text-primary text-center">Personnel Information</h5>
                        <h6 class="text-white px-4 ">Role: <span> <?= $empp['role']; ?></span> </h6>
                        <h6 class="text-white px-4 ">Name: <span> <?= $empp['name']; ?></span> </h6>
                        <h6 class="text-white px-4 ">Location: <span> <?= $empp['loc']; ?></span> </h6>
                    </div>
                    <div class="col-md-4 bg-dark rounded m-3">
                        <h5 class="bg-white p-2 text-primary text-center">Total Money In Account</h5>
                        <h5 class="text-white px-4 ">Debit Money: <span> <?= number_format(@$cusi['total'], 3) ?></span> </h5>
                        <h5 class="text-white px-4 ">Credit Money: <span> <?= number_format(@$cus['totalc'], 3) ?></span></h5>
                        <h5 class="text-white ">
                            <?php

                            if ($cusi['total'] > $cus['totalc']) {
                                echo "<h5 class='text-white px-4 '>Total Debit: <span>", number_format(@$cusi['total'] - $cus['totalc'], 3);
                                # code...
                            } else {
                                echo "<h5 class='text-white px-4 '>Total Credit: <span>",  number_format(@$cus['totalc'] - $cusi['total'], 3);
                                # code...
                            }
                            ?></h5>
                    </div>
                    <div class="col-md-4 bg-dark rounded m-3">
                        <h5 class="bg-white p-2 text-primary text-center">Total Gold In Account</h5>
                        <?php
                        $totalcc = floatval($cusc['totalcc']);
                        $totalg = floatval($cusg['totalg']);
                        $totalkg = floatval($cuskg['totalkg']);
                        ?>
                        <h5 class="text-white px-4">Debit Gold: <span> <?= number_format($totalf = $totalg + $totalkg, 3) ?> </span> </h5>
                        <h5 class="text-white px-4">Credit Gold: <span> <?= number_format($totalcc, 3) ?></span></h5>
                        <h5 class="text-white ">
                            <?php

                            if ($totalf > $totalcc) {
                                echo "<span class='text-white mx-4'>Total Debit: ", number_format($totalf - $totalcc, 3), "</span>";
                                # code...
                            } else {
                                echo "<span class='px-4 text-white p-2 text-center w-100'>Total Credit: ", number_format($totalcc - $totalf, 3), "</span>";
                                # code...
                            }
                            ?></h5>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="card">
                            <div class="card-header text-center bg-primary">
                                <h3 class="text-white mt-2">Add Customer Transiction</h3>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Reference">Reference</label>
                                                <input type="text" class="form-control" id="Reference" value="" name="ref" placeholder="Write Reference Here">
                                            </div>
                                        </div>
                                        <h5 class="bg-primary text-white rounded-pill text-center">Gold Information</h5>
                                        <!-- Submit Button -->
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="totalValue" class="text-primary">Total Pure Weight:</label>
                                                <input type="text" name="totalg" class="form-control" id="totalGrossWeight">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="" class="text-primary">Money $:</label>
                                                <input type="text" name="bill" class="form-control" id="totalGrossWeight">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="type" class="text-primary">Money Transiction Type</label>
                                                <select id="type" name="type" class="form-select">
                                                    <option value="------">------</option>
                                                    <option value="Debit">Debit (نـــــــــــــام)</option>
                                                    <option value="Credit">Credit (جــــــــــــمع) </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 my-2">
                                            <div class="form-group text-center my-2">
                                                <button type="submit" name="insert" class="btn btn-primary"><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i> Credit (جــــــــــــمع)</button>
                                                <button type="reset" name="clear" class="btn btn-danger"><i class="fa fa-times rounded-circle bg-white p-1 text-primary mx-1"></i> Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <!-- <script src="../js/main.js"></script> -->
</body>

</html>