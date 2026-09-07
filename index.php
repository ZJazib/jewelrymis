<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ZMIS - Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Admin Panel Dashboard" name="description">

    <!-- Favicon -->
    <link href="favicon.ico" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome & Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

    <!-- Custom Styles -->
   <script src="ckeditor/ckeditor.js"></script>
    <link href="css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Libraries -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">

    <!-- Sidebar -->
    <?php include "layout/Seibar.php"; ?>
        
    <!-- Main Content -->
    <div class="content">

        <!-- Navbar -->

<?php include "layout/navbar.php"; ?>
        <!-- Dashboard Stats -->
        <div class="container-xxl pt-4 px-4">
            <div class="row g-4">
                <!-- Customers -->
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body d-flex align-items-center">
                            <i class="fa fa-users fa-3x text-primary"></i>
                            <div class="ms-3 text-start">
                                <p class="mb-1 fw-semibold">Total Customers</p>
                                <h5 class="mb-0">
                                    <?php
                                    $sql = "SELECT COUNT(id) as count FROM customer WHERE role = 'Customer'";
                                    $res = mysqli_query($conn, $sql);
                                    $users = mysqli_fetch_assoc($res);
                                    echo $users['count'];
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suppliers -->
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body d-flex align-items-center">
                            <i class="fa fa-truck fa-3x text-success"></i>
                            <div class="ms-3 text-start">
                                <p class="mb-1 fw-semibold">Total Suppliers</p>
                                <h5 class="mb-0">
                                    <?php
                                    $sql = "SELECT COUNT(id) as count FROM customer WHERE role = 'Supplier'";
                                    $res = mysqli_query($conn, $sql);
                                    $users = mysqli_fetch_assoc($res);
                                    echo $users['count'];
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Money Suppliers -->
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body d-flex align-items-center">
                            <i class="fa fa-coins fa-3x text-warning"></i>
                            <div class="ms-3 text-start">
                                <p class="mb-1 fw-semibold">Money Suppliers</p>
                                <h5 class="mb-0">
                                    <?php
                                    $sql = "SELECT COUNT(id) as count FROM customer WHERE role = 'Money Supplier'";
                                    $res = mysqli_query($conn, $sql);
                                    $users = mysqli_fetch_assoc($res);
                                    echo $users['count'];
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body d-flex align-items-center">
                            <i class="fa fa-user-shield fa-3x text-danger"></i>
                            <div class="ms-3 text-start">
                                <p class="mb-1 fw-semibold">Total Users</p>
                                <h5 class="mb-0">
                                    <?php
                                    $sql = "SELECT COUNT(user_id) as count FROM users";
                                    $res = mysqli_query($conn, $sql);
                                    $users = mysqli_fetch_assoc($res);
                                    echo $users['count'];
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- TradingView Widget BEGIN -->


        <!-- Stock & Gold Section -->
        <div class="container-xxl my-2">
            <div class="row g-4">
              <?php
// Fetch gold price server-side
$goldPrice = "Error fetching price";
$apiKey = '56801be46e36b3d996b1d5e6e869fc0756801be4';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://goldpricez.com/api/rates/currency/usd/measure/ounce");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-API-KEY: $apiKey"]);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
if ($data && isset($data['rates']['ounce'])) {
    $goldPrice = number_format($data['rates']['ounce'], 2);
}
?>




                <!-- Total Money -->
                <div class="col-md-4">
                    <div class="card shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <h6 class="bg-white text-primary text-center p-2 rounded">Total Money In Stock</h6>
                            <!-- PHP Credit/Debit Money Logic -->
                            <?php $sqli = "SELECT SUM(st_price) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash' and DATE(`st_date`) = CURDATE()";
                                $resi = mysqli_query($conn, $sqli);
                                $cusi = mysqli_fetch_assoc($resi);

                                $sqlt = "SELECT SUM(st_price) as totalt FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                                $rest = mysqli_query($conn, $sqlt);
                                $cust = mysqli_fetch_assoc($rest);

                                $sql = "SELECT SUM(st_price) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash' and DATE(`st_date`) = CURDATE()";
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
                                <p class="text-white ">Credit Money: <span> <?= $totalcre = number_format($cus['totalc'], 3) ?></span> USD ($)</p>
                                <p class="text-white ">Debit Money: <span> <?= $totaldeb = number_format($cusi['total'] + $userc['totalm'], 3) ?></span> USD ($) </p>
                                <h5 class="text-white ">
                                    <?php

                                    if ($totaldebt > $totaldebtt) {
                                        echo "<span class='text-white  text-center rounded-pill'>Total Debit: </span>", number_format($totaldebt - $totaldebtt, 3);
                                        # code...
                                    } else  if ($totaldebt < $totaldebtt) {
                                        echo "<span class='text-white  text-center rounded-pill'>Total Credit: </span>", number_format($totaldebtt - $totaldebt - $totaldebttt, 3);
                                        # code...
                                    } else {
                                        echo "<span class='bg-danger text-white  text-center rounded-pill'>Total Exist: 0 </span>";
                                    }
                                    ?> USD ($)</h5>
                        </div>
                    </div>
                </div>

                <!-- Total Gold -->
                                <!-- Total Gold -->
                <div class="col-md-4">
                    <div class="card shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <h6 class="bg-white text-primary text-center p-2 rounded">Total Gold In Stock</h6>
                             <?php
                                $sqli = "SELECT SUM(st_gold) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash' and `name`!='SILVER' and DATE(`st_date`) = CURDATE()";
                                $resi = mysqli_query($conn, $sqli);
                                $cusi = mysqli_fetch_assoc($resi);

                                $sqltg = "SELECT SUM(st_gold) as totalgt FROM `storage` where `method` = 'Debit' and `name`!='SILVER' and `type` = 'Cash'";
                                $resitg = mysqli_query($conn, $sqltg);
                                $custg = mysqli_fetch_assoc($resitg);

                                $sql = "SELECT SUM(st_gold) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash' and `name`!='SILVER' and DATE(`st_date`) = CURDATE()";
                                $res = mysqli_query($conn, $sql);
                                $cus = mysqli_fetch_assoc($res);

                                $sqlgtt = "SELECT SUM(st_gold) as totalgtt FROM `storage` where `method` = 'Credit' and `type` = 'Cash' and `name`!='SILVER'";
                                $resgtt = mysqli_query($conn, $sqlgtt);
                                $cusgtt = mysqli_fetch_assoc($resgtt);

                                @$totaldebtg = floatval($custg['totalgt']);
                                @$totaldebttg = floatval($cusgtt['totalgtt']);
                                ?>
                                <p class="text-white ">Credit Gold: <span> <?= number_format($totalcre = $cus['totalc'], 3) ?></span> PURE (GMS)</p>
                                <p class="text-white ">Debit Gold: <span> <?= number_format($totaldeb = $cusi['total'], 3) ?></span> PURE (GMS)</p>
                                <h5 class="text-white">
                                    <?php

                                    if ($totaldebtg > $totaldebttg) {
                                        echo "<span class='bg-danger text-white '>Total Debit: ", number_format($totaldebtg - $totaldebttg, 3), "</span>";
                                        # code...
                                    } else  if ($totaldebtg < $totaldebttg) {
                                        echo "<span class=' text-white text-center  '>Total Credit: ", number_format($totaldebttg - $totaldebtg, 3), "</span>";
                                        # code...
                                    } else {
                                        echo "<span class='bg-danger text-white  w-100 text-center rounded-pill'>Total Exist: 0 </span>";
                                    }
                                    ?> PURE (GMS)</h5>
                        </div>
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <h6 class="bg-white text-primary text-center p-2 rounded">Total Silver In Stock</h6>
                             <?php
                                $sqli = "SELECT SUM(st_gold) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash' and `name`='SILVER' and DATE(`st_date`) = CURDATE()";
                                $resi = mysqli_query($conn, $sqli);
                                $cusi = mysqli_fetch_assoc($resi);

                                $sqltg = "SELECT SUM(st_gold) as totalgt FROM `storage` where `method` = 'Debit' and `type` = 'Cash' and `name`='SILVER'";
                                $resitg = mysqli_query($conn, $sqltg);
                                $custg = mysqli_fetch_assoc($resitg);

                                $sql = "SELECT SUM(st_gold) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash' and `name`='SILVER' and DATE(`st_date`) = CURDATE()";
                                $res = mysqli_query($conn, $sql);
                                $cus = mysqli_fetch_assoc($res);

                                $sqlgtt = "SELECT SUM(st_gold) as totalgtt FROM `storage` where `method` = 'Credit' and `type` = 'Cash' and `name`='SILVER'";
                                $resgtt = mysqli_query($conn, $sqlgtt);
                                $cusgtt = mysqli_fetch_assoc($resgtt);

                                @$totaldebtg = floatval($custg['totalgt']);
                                @$totaldebttg = floatval($cusgtt['totalgtt']);
                                ?>
                                <p class="text-white ">Credit Silver: <span> <?= number_format($totalcre = $cus['totalc'], 3) ?></span>  (KGS)</p>
                                <p class="text-white ">Debit Silver: <span> <?= number_format($totaldeb = $cusi['total'], 3) ?></span> (KGS)</p>
                                <h5 class="text-white">
                                    <?php

                                    if ($totaldebtg > $totaldebttg) {
                                        echo "<span class='bg-danger text-white '>Total Debit: ", number_format($totaldebtg - $totaldebttg, 3), "</span>";
                                        # code...
                                    } else  if ($totaldebtg < $totaldebttg) {
                                        echo "<span class=' text-white text-center  '>Total Credit: ", number_format($totaldebttg - $totaldebtg, 3), "</span>";
                                        # code...
                                    } else {
                                        echo "<span class='bg-danger text-white  w-100 text-center rounded-pill'>Total Exist: 0 </span>";
                                    }
                                    ?> (KMS)</h5>
                        </div>
                    </div> 
                </div>
                <!DOCTYPE html>

                <div class="col-md-6">
    <div class="card shadow-sm bg-primary text-white text-center">
        <div class="card-body">
<iframe 
  src="https://m.sarafi.af/fa/exchange-rates/sarai-shahzada"
  width="100%" 
  height="200" 
  frameborder="0" 
  scrolling="yes"
  style="border: none; overflow: auto;">
</iframe></div></div></div>

                <div class="col-md-6">
    <div class="card shadow-sm bg-primary text-white text-center">
        <div class="card-body">
            
            <div class="tradingview-widget-container">
  <div id="tradingview_gold"></div>
  <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
  <script type="text/javascript">
    new TradingView.widget({
      "container_id": "tradingview_gold",
      "width": "100%",
      "height": 200,
      "symbol": "OANDA:XAUUSD",   // Gold / USD
      "interval": "D",
      "timezone": "Etc/UTC",
      "theme": "light",
      "style": "1",
      "locale": "en",
      "toolbar_bg": "#f1f3f6",
      "enable_publishing": false,
      "hide_legend": false,
      "save_image": false,
      "studies": [],
      "show_popup_button": true,
      "popup_width": "1000",
      "popup_height": "650"
    });
  </script>
</div>
<!-- TradingView Widget END -->
        </div>
    </div>
</div>
            </div>
        </div>
        <!-- Journal Section -->
        <div class="container-fluid pb-5">
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="text-center my-3 text-primary">Daily Journal - روز نامچه</h4>
                    <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead class="text-white">
                            <tr class="text-white bg-primary text-center">
                                <th scope="col" rowspan="2">Date</th>
                                <th scope="col" rowspan="2">Name</th>
                                <th scope="col" rowspan="2">Reference</th>
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
                            $sql = "SELECT * FROM `statement` WHERE DATE(se_date) = CURDATE()";
                            $res = mysqli_query($conn, $sql);
                            

        
                            $debitTotal = 0;
                            $creditTotal = 0;
                            $goldDebitTotal = 0;
                            $goldCreditTotal = 0;

                            while ($row = mysqli_fetch_assoc($res)) {
                                $type = mysqli_real_escape_string($conn, $row['se_type']);
                                $date = date("F j, Y", strtotime($row['se_date']));
 $sqli = "SELECT * FROM `customer` where id = ". $row['se_cus'];
        $ress = mysqli_query($conn, $sqli);
        $empp = mysqli_fetch_assoc($ress);
                                $se_tbill = floatval($row['se_tbill']);
                                $se_tpurity = floatval($row['se_tpurity']);

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
                                <td class=""><?= $row['ref'] ?></td>
                                <td class=""><?= $row['dis'] ?></td>
                                <td><?= $debitValue ? "($debitValue)" : '' ?></td>
                                <td><?= $creditValue ?></td>
                                <td><?= $goldDebitValue ? "($goldDebitValue)" : '' ?></td>
                                <td><?= $goldCreditValue ?></td>
                            </tr>
                            <?php } ?>

                            <!-- Final total row -->
                            <tr class="text-white">
                                <td colspan="3">Total</td>
                                <td><?= ($debitTotal != 0) ? "($" . number_format($debitTotal, 3) . ")" : '' ?></td>
                                <td><?= ($creditTotal != 0) ? number_format($creditTotal, 3) : '' ?></td>
                                <td><?= ($goldDebitTotal != 0) ? "($" . number_format($goldDebitTotal, 3) . ")" : '' ?></td>
                                <td><?= ($goldCreditTotal != 0) ? number_format($goldCreditTotal, 3) : '' ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Summary of Total Credit/Debit -->
                    <div style="margin-top: 20px;">
                        <strong>Total Balance Amount (USD): </strong>
                        <?= ($balance != 0) ? ($balance < 0 ? number_format(abs($balance), 3) : number_format($balance, 3)) : '' ?>
                        <?php
                        echo ($creditTotal > $debitTotal) ? "Credit" : "Debit";
                        ?>
                    </div>
                    <div>
                        <strong>Total Gold Balance: </strong>
                        <?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '' ?>
                        <?php
                        echo ($goldCreditTotal > $goldDebitTotal) ? "Credit" : "Debit";
                        ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-lg rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    </div><!-- End Content -->
        </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script src="js/main.js"></script>


</body>
</html>
