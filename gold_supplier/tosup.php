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

    <?php
    if (isset($_POST['insertn'])) {

        // invoice inserteration

        $id = $_GET['id'];
        $ref = $_POST['dis'];
        $sql = "SELECT * FROM `customer` where id = '$ref'";
        $res = mysqli_query($conn, $sql);
        $cus = mysqli_fetch_assoc($res);
        $reff = $cus['name'];


        
        $goldg = $_POST['goldg'];
        $purity = $_POST['pur'];
        @$gold = $_POST['gold'];
        $goldnam = $_POST['goldnam'];
        $mc = $_POST['mc']  / 3.67;
        $name = $_POST['name'];
        $Paid = $_POST['Paid'];
        $put= $gold/$purity;

        // Supplier one method
        $debc = "INSERT INTO `credit`(`ref`, `cre_cus`, `cre_tgold`, `cre_tbill`, `cre_tpurity`, `ujorat`, `com`, `sate`, `fix`, `role`, `typec`) VALUES ('$reff','$id','$goldg','$mc','$purity','0','0','Credit','not','Supplier','$Paid')";
        $resck = mysqli_query($conn, $debc);

        $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$reff','0','$mc','$purity','Supplier','$Paid','Credit','0')";
        mysqli_query($conn, $amo);

        $staf = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','$goldg','$purity','$mc','Credit','$Paid', 'PAYMENT IN GOLD ($goldg GMS 995 BY Reference NO. $ref)')";
        mysqli_query($conn, $staf);

        // Supplier Two Methods
        $deb = "INSERT INTO `debit`(`dep_cus`, `ref`, `dep_tgold`, `dep_tbill`, `dep_tpurity`, `sate`, `com`, `typec`) VALUES ('$goldnam','$ref','$goldg','$mc','$purity','Debit','0','$Paid')";
        $resd = mysqli_query($conn, $deb);

        $sta = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$goldnam','$ref','$goldg','$purity','$mc','Debit','$Paid', 'PAYMENT IN GOLD ($goldg GMS 995 BY Reference NO. $ref)')";
        mysqli_query($conn, $sta);

        $amoc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$goldnam','$ref','$mc','0','$purity','Supplier','$Paid','Debit','0')";
        mysqli_query($conn, $amoc);
    }
    ?>
    <?php
    if (isset($_GET['id'])) {
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
                        <a class="text-center btn btn-success my-3 w-25" href="../monthly_journal/mjournal.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Monthly Journal</b></a>
                        <a class="text-center btn btn-outline-dark m-3 w-25" href="../index.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b></a>

                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-header text-center bg-primary">
                                    <h3 class="text-white mt-2">Add New trasiction of one Supplier other Supplier </h3>
                                </div>
                                <div class="card-body">
                                    <!-- Personal Information Fields... -->
                                    <div class="col-md-12">
                                        <div class="row">
                                            <h5 class="bg-primary text-white rounded-pill text-center">Personal Information</h5>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="Name">Name</label>
                                                    <input type="text" class="form-control" id="cus_name" value="<?= $empp['name'] ?>" name="name" placeholder="Write Name Here">
                                                </div>
                                            </div>
                                            <h5 class="bg-primary text-white rounded-pill text-center">From ( <?= $empp['name'] ?> ) Gold Information</h5>

                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="dis">Reference Number</label>
                                                    <input type="text" value="" class="form-control" id="dis" name="dis">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="goldWeight" class="text-primary">Gold Weight (grams):</label>
                                                    <input type="number" name="goldg" class="form-control" id="goldWeight" step="any">
                                                </div>
                                            </div>
                                            <script>
                                                function calculateTotals() {
                                                    var weight = parseFloat(document.getElementById("goldWeight").value);
                                                    var purity = parseFloat(document.getElementById("purity").value);
                                                    var totalValue = weight * purity;
                                                    document.getElementById("pur").value = totalValue.toFixed(3);
                                                }
                                                window.onload = function() {
                                                    calculateTotals();
                                                };
                                            </script>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="purity" class="text-primary">Purity:</label>
                                                    <select id="purity" name="purity[]" onchange="calculateTotals()" class="form-select">
                                                        <option value="------">-------------</option>
                                                        <option value="0.585">14 Karat (0.585)</option>
                                                        <option value="0.750">18 Karat (0.750)</option>
                                                        <option value="0.875">21 Karat (0.875)</option>
                                                        <option value="0.880">21.12 Karat (0.880)</option>
                                                        <option value="0.920">22 Karat (0.920)</option>
                                                        <option value="0.995">23.88 Karat (0.995)</option>
                                                        <option value="0.999">23.97 Karat (0.999)</option>
                                                        <option value="0.9999">23.99 Karat (0.9999)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="totalValue" class="text-primary">Pure weight: </label>
                                                    <input type="text" name="pur" oninput="calculateTotals()" class="form-control" id="totalValue">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="totalPrice" class="text-primary">Total Wage اجرت AED</label>
                                                    <input type="text" name="mc" class="form-control" id="totalPrice">
                                                </div>
                                            </div>
                                            <h5 class="bg-primary text-white rounded-pill text-center">TO Gold Information</h5>

                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="goldnam" class="text-primary">Name:</label>
                                                    <select id="goldnam" name="goldnam" class="form-select">
                                                        <option value="------">------</option>
                                                        <?php $sql = "SELECT * FROM `customer` where `role` = 'Supplier'";
                                                        $res = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($res) > 0) {
                                                            while ($cus = mysqli_fetch_assoc($res)) {
                                                        ?>
                                                                <option value="<?= $cus['id'] ?>"><?= $cus['name'] ?></option>
                                                        <?php
                                                            }
                                                        }

                                                        ?>


                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="Paid" class="text-primary">Paid Method</label>
                                                    <select id="Paid" name="Paid" class="form-select">
                                                        <option value="------">------</option>
                                                        <option value="Cash">Cash</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 my-2">
                                                <div class="form-group text-center my-2">
                                                    <button type="submit" name="insertn" class="btn btn-primary"><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i> Insert</button>
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
        </div>

    <?php
    } else {
    ?>
        <div class="container-fluid pt-4 px-5">
            <div class=" rounded align-items-center bg-light justify-content-center mx-0">
                <div class="container py-2">

                    <h4 class="text-center my-4 bg-primary rounded p-2 text-white">Suppliers List</h4>
                    <form action="search.php" method="get">
                        <div class="input-group my-4">
                            <i class="fa fa-search bg-primary text-white p-3"></i><input type="text" class="form-control" name="jd" id="search-input" placeholder="Search for Customer Name, NIC and Location" aria-label="Search for Customer Name, NIC and Location" aria-describedby="search-btn">
                        </div>
                    </form>

                    <!-- JavaScript code -->
                    <script>
                        $(document).ready(function() {
                            // Attach event listener to search input field
                            $('#search-input').on('input', function() {
                                // Get search query from input field
                                var query = $(this).val();

                                // Send AJAX request to search.php with search query
                                $.ajax({
                                    url: 'search.php',
                                    method: 'get',
                                    data: {
                                        jb: query
                                    },
                                    success: function(response) {
                                        // Update search results container with response from search.php
                                        $('#search-results').html(response);
                                    }
                                });
                            });
                        });
                    </script>
                    <!-- Search results container -->
                    <div id="search-results">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-white bg-primary text-center">
                                    <th scope="col">Name</th>
                                    <th scope="col">NIC</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM `customer` where `role`='Supplier'";
                                $res = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($res) > 0) {
                                    while ($emp = mysqli_fetch_assoc($res)) {

                                ?>
                                        <tr class="text-white bg-dark text-center">
                                            <td><?= $emp['name'] ?></td>
                                            <td><?= @$emp['nic'] ?></td>
                                            <td><?= $emp['phone'] ?></td>
                                            <td><?= $emp['email'] ?></td>
                                            <td><?= $emp['loc'] ?></td>
                                            <td class="text-center"><a class=" btn btn-sm btn-success" href="?id=<?= $emp['id']; ?>"><i class="far fa-plus"></i></a></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <script>
        function calculateTotals() {
            var weight = parseFloat(document.getElementById("goldWeight").value);
            var purity = parseFloat(document.getElementById("purity").value);
            var totalValue = weight * purity;
            document.getElementById("totalValue").value = totalValue.toFixed(3);
        }

        window.onload = function() {
            calculateTotals();
        };
    </script>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>