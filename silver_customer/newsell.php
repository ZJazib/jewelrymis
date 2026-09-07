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
    ?>

    <?php
    $id = $_GET['id'];
    $sqlcus = "SELECT * FROM `customer` where id =" . $_GET['id'];
    $rescus = mysqli_query($conn, $sqlcus);
    $empp = mysqli_fetch_assoc($rescus);

    $sqli = "SELECT SUM(se_tbill) as total FROM `statement` where `se_type`='Credit' and  se_cus =" . $_GET['id'];
    $resi = mysqli_query($conn, $sqli);
    $cusi = mysqli_fetch_assoc($resi);
    $sql = "SELECT SUM(se_tbill) as totalc FROM `statement` where `se_type`='Debit' and se_cus =" . $_GET['id'];
    $res = mysqli_query($conn, $sql);
    $cus = mysqli_fetch_assoc($res);
    $sqlcc = "SELECT SUM(se_tpurity) as totalcc FROM `statement` where `se_type`='Credit' and  se_cus=" . $_GET['id'];
    $resc = mysqli_query($conn, $sqlcc);
    $cusc = mysqli_fetch_assoc($resc);


    $sqlkg = "SELECT SUM(se_tpurity) as totalkg FROM `statement` where `se_type`='Debit' and `se_cus`=" . $_GET['id'];
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
                        <h5 class="text-white px-4 ">Debit Money: <span> <?= number_format(@$cusi['total'], 3) ?> </span> USD ($)</h5>
                        <h5 class="text-white px-4 ">Credit Money: <span> <?= number_format(@$cus['totalc'], 3) ?> </span> USD ($)</h5>
                        <h5 class="text-white ">
                            <?php

                            if ($cusi['total'] > $cus['totalc']) {
                                echo "<h5 class='text-white px-4 '>Total Debit: <span>", number_format(@$cusi['total'] - $cus['totalc'], 3);
                                # code...
                            } else {
                                echo "<h5 class='text-white px-4 '>Total Credit: <span>",  number_format(@$cus['totalc'] - $cusi['total'], 3);
                                # code...
                            }
                            ?> USD ($)</h5>
                    </div>
                    <div class="col-md-4 bg-dark rounded m-3">
                        <h5 class="bg-white p-2 text-primary text-center">Total Silver In Account</h5>
                        <?php
                        $totalcc = floatval($cusc['totalcc']);
                        $totalkg = floatval($cuskg['totalkg']);
                        ?>
                        <h5 class="text-white px-4">Debit Silver: <span> <?= number_format($totalkg, 3) ?> </span> (KGS)</h5>
                        <h5 class="text-white px-4">Credit Silver: <span> <?= number_format($totalcc, 3) ?> </span> (KGS)</h5>
                        <h5 class="text-white ">
                            <?php

                            if ($totalkg > $totalcc) {
                                echo "<span class='text-white mx-4'>Total Debit: ", number_format($totalkg - $totalcc, 3), "</span>";
                                # code...
                            } else {
                                echo "<span class='px-4 text-white p-2 text-center w-100'>Total Credit: ", number_format($totalcc - $totalkg, 3), "</span>";
                                # code...
                            } ?>(KGS)</h5>
                    </div>

                    <form action="billnew.php?t=<?= $id ?>" method="post" enctype="multipart/form-data">

                        <div class="card">
                            <div class="card-header text-center bg-primary">
                                <h3 class="text-white mt-2">Add New Sell To Customer</h3>
                            </div>

                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="text-primary" for="Reference">Reference</label>
                                        <input type="text" class="form-control" id="Reference" name="ref" placeholder="Write Reference Here">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h5 class="bg-primary text-white rounded-pill text-center py-1">Silver Information</h5>
                                    </div>
                                </div>

                                <div id="goldItemsContainer">
                                    <div class="row gold-item mb-3">
                                        <div class="col-md-6">
                                            <label class="text-primary">Description</label>
                                            <input type="text" class="form-control discription" name="dis" placeholder="Description">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-primary">Weight (KGS)</label>
                                            <input type="number" class="form-control goldWeight" name="goldg" step="any" oninput="calculateTotals()">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-primary">Purity</label>
                                            <select class="form-select purity" name="purity" oninput="calculateTotals()">
                                                <option value="0">-------------</option>
                                                <option value="0.995">23.88 Karat (0.995)</option>
                                                <option value="0.999">23.97 Karat (0.999)</option>
                                                <option value="0.9999">23.99 Karat (0.9999)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-primary">Price per KG (USD)</label>
                                            <input type="number" class="form-control pricePerGram" name="priceg" step="any" oninput="calculateTotals()">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="text-primary">Total Amount ($)</label>
                                        <input type="text" name="bill" class="form-control" id="bill">
                                    </div>
                                </div>

                                <div class="row text-center">
                                    <div class="col-12 my-2">
                                        <input type="submit" name="submit" class="btn btn-primary">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function calculateTotals() {
                             

                                document.querySelectorAll(".gold-item").forEach(function(row) {
                                    let weight = parseFloat(row.querySelector(".goldWeight").value) || 0;
                                    let price = parseFloat(row.querySelector(".pricePerGram").value) || 0;

                                    let rowTotal = weight * price;
                                    totalAmount = rowTotal;
                                });

                                document.getElementById("bill").value = totalAmount.toFixed(2);
                            }
                        </script>


                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <!-- <script src="../js/main.js"></script> -->
</body>

</html>