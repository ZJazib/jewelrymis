<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ZMIS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Admin Panel" name="keywords">
    <meta content="Admin Panel" name="description">

    <!-- Favicon -->
    <link href="../favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
      <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Sidebar Start -->
    <?php
    require "config/Dbconn.php";
    ?>
    <div class="container-fluid my-5">
        <div class="rounded align-items-center bg-light justify-content-center mx-0">
            <div class="container py-2">
                <a class="text-center btn btn-dark m-3 w-25" href="index.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b></a>

                <h4 class="text-center my-4 bg-primary rounded p-2 text-white">Storage journal</h4>
                
             
 <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-white bg-primary text-center">
                                <th scope="col" rowspan="2">Date</th>
                                <th scope="col" rowspan="2">Name</th>
                                <th scope="col" rowspan="2">Description</th>
                                <th colspan="2">Base Currency Amount</th>
                                <th colspan="2">Gold Qty. In CT</th>
                            </tr>
                            <tr class="text-white bg-primary text-center">
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            // Query to retrieve today's data
                            $sql = "SELECT * FROM storage";
                            $res = mysqli_query($conn, $sql);
                            

        
                            $debitTotal = 0;
                            $creditTotal = 0;
                            $goldDebitTotal = 0;
                            $goldCreditTotal = 0;

                            while ($row = mysqli_fetch_assoc($res)) {
                                $type = mysqli_real_escape_string($conn, $row['method']);
                                $date = date("F j, Y", strtotime($row['st_date']));
 $sqli = "SELECT * FROM `customer` where id = ". $row['st_cus'];
        $ress = mysqli_query($conn, $sqli);
        $empp = mysqli_fetch_assoc($ress);
                                $se_tbill = floatval($row['st_price']);
                                $se_tpurity = floatval($row['st_gold']);

                                if ($type == "Debit") {
                                    $debitTotal += $se_tbill;
                                    $goldDebitTotal += $se_tpurity;
                                } elseif ($type == "Credit") {
                                    $creditTotal += $se_tbill;
                                    $goldCreditTotal += $se_tpurity;
                                }

                                // Calculate running balances
                                $balance = $debitTotal - $creditTotal;
                                $goldBalance = $goldDebitTotal - $goldCreditTotal;

                                // Display values, showing empty string if 0 or 0.000
                                $debitValue = ($type == "Debit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';
                                $creditValue = ($type == "Credit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';

                                $goldDebitValue = ($type == "Debit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';
                                $goldCreditValue = ($type == "Credit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';

                                // Format balances
                                $balanceValue = ($balance < 0) ? number_format(abs($balance), 3) : number_format($balance, 3);
                                $goldBalanceValue = ($goldBalance < 0) ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3);
                            ?>
                            <tr class="">
                                <td><?= htmlspecialchars($date) ?></td>
                                <td><?=htmlspecialchars($empp['name']) ?></td>
                                <td class=""><?= $row['name'] ?></td>
                                <td><?= $debitValue ? "($debitValue)" : '' ?></td>
                                <td><?= $creditValue ?></td>
                                <td><?= $goldDebitValue ? "($goldDebitValue)" : '' ?></td>
                                <td><?= $goldCreditValue ?></td>
                            </tr>
                            <?php } ?>

                            <!-- Final total row -->
                            <tr class="text-dark">
                                <td colspan="3">Total</td>
                                <td><?= ($debitTotal != 0) ? "($" . number_format($debitTotal, 3) . ")" : '' ?></td>
                                <td><?= ($creditTotal != 0) ? number_format($creditTotal, 3) : '' ?></td>
                                <td><?= ($goldDebitTotal != 0) ? "($" . number_format($goldDebitTotal, 3) . ")" : '' ?></td>
                                <td><?= ($goldCreditTotal != 0) ? number_format($goldCreditTotal, 3) : '' ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Summary of Total Credit/Debit -->
<div class="container-xxl my-2">
            <div class="rounded align-items-center bg-light justify-content-center mx-0">
                <div class="container py-2">
                    <div class="row">
                       
                            <div class="col-md-4 mx-3 bg-primary rounded services-items">
                                <h5 class="bg-white p-2 text-primary text-center">Total money In Stock</h5>
                                <?php $sqli = "SELECT SUM(st_price) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                                $resi = mysqli_query($conn, $sqli);
                                $cusi = mysqli_fetch_assoc($resi);

                                $sqlt = "SELECT SUM(st_price) as totalt FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                                $rest = mysqli_query($conn, $sqlt);
                                $cust = mysqli_fetch_assoc($rest);

                                $sql = "SELECT SUM(st_price) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                                $res = mysqli_query($conn, $sql);
                                $cus = mysqli_fetch_assoc($res);


                                $sqltt = "SELECT SUM(st_price) as totaltt FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                                $restt = mysqli_query($conn, $sqltt);
                                $custt = mysqli_fetch_assoc($restt);


                                $cuss = "SELECT SUM(of_money) as totalm FROM office where `state` = 'Cash' and DATE(`of_date`) = CURDATE()";
                                $ress = mysqli_query($conn, $cuss);
                                $userc = mysqli_fetch_assoc($ress);

                                $cusst = "SELECT SUM(of_money) as totalttt FROM office where `state` = 'Cash'";
                                $resst = mysqli_query($conn, $cusst);
                                $userct = mysqli_fetch_assoc($resst);

                                @$totaldebt = floatval($cust['totalt']);
                                @$totaldebtt = floatval($custt['totaltt']);
                                @$totaldebttt = floatval($custt['totalttt']);
                                ?>
                                <h5 class="text-white px-4 py-2">Credit Money: <span> <?= $totalcre = number_format($cus['totalc'], 3) ?></span></h5>
                                <h5 class="text-white px-4 py-2">Debit Money: <span> <?= $totaldeb = number_format($cusi['total'] + $userc['totalm'], 3) ?></span> </h5>
                                <h5 class="text-white ">
                                    <?php

                                    if ($totaldebt > $totaldebtt) {
                                        echo "<span class='text-white px-4 p-2 text-center rounded-pill'>Total Debit: </span>", number_format($totaldebt - $totaldebtt, 3);
                                        # code...
                                    } else  if ($totaldebt < $totaldebtt) {
                                        echo "<span class='text-white px-4 p-2 text-center rounded-pill'>Total Credit: </span>", number_format($totaldebtt - $totaldebt - $totaldebttt, 3);
                                        # code...
                                    } else {
                                        echo "<span class='bg-danger text-white px-4 p-2 text-center rounded-pill'>Total Exist: 0 </span>";
                                    }
                                    ?></h5>
                            </div>
                            <div class="col-md-4 bg-primary rounded">
                                <h5 class="bg-white p-2 text-primary text-center">Total Gold In Stock</h5>
                                <?php
                                $sqli = "SELECT SUM(st_gold) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                                $resi = mysqli_query($conn, $sqli);
                                $cusi = mysqli_fetch_assoc($resi);

                                $sqltg = "SELECT SUM(st_gold) as totalgt FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                                $resitg = mysqli_query($conn, $sqltg);
                                $custg = mysqli_fetch_assoc($resitg);

                                $sql = "SELECT SUM(st_gold) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                                $res = mysqli_query($conn, $sql);
                                $cus = mysqli_fetch_assoc($res);

                                $sqlgtt = "SELECT SUM(st_gold) as totalgtt FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                                $resgtt = mysqli_query($conn, $sqlgtt);
                                $cusgtt = mysqli_fetch_assoc($resgtt);

                                @$totaldebtg = floatval($custg['totalgt']);
                                @$totaldebttg = floatval($cusgtt['totalgtt']);
                                ?>
                                <h5 class="text-white px-4 py-2">Credit Gold: <span> <?= number_format($totalcre = $cus['totalc'], 3) ?></span></h5>
                                <h5 class="text-white px-4 py-2">Debit Gold: <span> <?= number_format($totaldeb = $cusi['total'], 3) ?></span> </h5>
                                <h5 class="text-white ">
                                    <?php

                                    if ($totaldebtg > $totaldebttg) {
                                        echo "<span class='bg-danger text-white mx-4'>Total Debit: ", number_format($totaldebtg - $totaldebttg, 3), "</span>";
                                        # code...
                                    } else  if ($totaldebtg < $totaldebttg) {
                                        echo "<span class=' text-white p-2 text-center  mx-4'>Total Credit: ", number_format($totaldebttg - $totaldebtg, 3), "</span>";
                                        # code...
                                    } else {
                                        echo "<span class='bg-danger text-white px-4 p-2 w-100 text-center rounded-pill'>Total Exist: 0 </span>";
                                    }
                                    ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
