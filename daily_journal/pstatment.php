<?php
require '../vendor/autoload.php'; // Load Composer's autoloader
use Dompdf\Dompdf;

// Include necessary files and database connection
require "../config/Dbconn.php";

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Initialize Dompdf
$dompdf = new Dompdf();

// Start PDF content
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>STATEMENT OF DAILY STATEMENT</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            text-transform: uppercase;
            border: solid 1px black;
            padding: 0;
        }

        table {
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            font-size: 13px;
            padding: 5px;
        }

        th {
            background-color: lightgray;
        }
    </style>
</head>

<body>
    <?php
    if (!empty($_GET['from'])) {
        @$id = $_GET['id'];
        @$to = mysqli_escape_string($conn, $_GET['to']);
        @$from = mysqli_escape_string($conn, $_GET['from']);
        if (empty($to) and empty($from)) {
            $to = date("Y-m-d");
        } ?>
 <div class="header">
        <h3 style="margin: 4px;">HM Azim Jewelry </h3>
         <h5 style="margin: 0;">letter with Statement of Daily Statement</h5>
        <h6 style="padding:0; font-size:10px; margin:0;">Period From <?= $from ?>  To <?= $to ?></h6>
    </div>
   

    <div class="table-responsive ">
        <table class="table text-start align-middle table-bordered table-hover mb-0">
            <thead>
                <tr class="text-white bg-primary text-center">
                    <th scope="col" rowspan="2">Date</th>
                    <th scope="col" rowspan="2">Doc. No</th>
                    <th scope="col" rowspan="2">Particulars Ref. No.</th>
                    <th colspan="3">Base Currecny Amount</th>
                    <th colspan="3">Gold Qty. In CT</th>

                <tr>
                    <th>debit</th>
                    <th>credit</th>
                    <th>Balance</th>
                    <th>debit</th>
                    <th>credit</th>
                    <th>Balance</th>
                </tr>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `statement` where se_date BETWEEN '$from' and '$to'";
                $res = mysqli_query($conn, $sql);
                while ($emp = mysqli_fetch_assoc($res)) {
                    $type = mysqli_escape_string($conn, $emp['se_type']);
                    if ($type == 'Debit') {
                ?>

                        <tr class="text-white bg-dark">
                            <td><?php @$timestamp = $emp['se_date'];
                                $am_pm_time = date("F j, Y", strtotime($timestamp));
                                echo $am_pm_time;
                                ?></td>
                            <td>DE00<?= $emp['se_id'] ?></td>
                            <td><?= $emp['dis'] ?></td>
                            <td><?= number_format($debb = $emp['se_tbill'], 3) ?> </td>
                            <td></td>
                            <td><?= @$totalbilld = number_format(@$debill = $emp['se_tbill'], 3) ?> </td>
                            <td><?php
                            if (empty($emp['se_tpurity'])) {
                                # code...
                            }else {
                                echo $emp['se_tpurity'];
                            }
                               
                                ?></td>
                            <td></td>
                            <td><?php
                             if (empty($emp['se_tpurity'])) {
                                
                            }else {
                                echo $totalpur = abs(@$pudec = $emp['se_tpurity']);
                            }
                             ?></td>
                        </tr>
                    <?php


                    }
                    if ($type == 'Credit') {
                    ?>

                        <tr class="text-white bg-dark">
                            <td><?php @$timestamp = $emp['se_date'];
                                $am_pm_time = date("F j, Y", strtotime($timestamp));
                                echo $am_pm_time;
                                ?></td>
                            <td>CR00<?= $emp['se_id'] ?></td>
                            <td><?= $emp['dis'] ?></td>
                            <td></td>
                            <td><?= number_format(@$debbillc = $emp['se_tbill'], 3)
                                ?> </td>

                            <td><?= number_format(@$totalse = $debbillc , 3) ?> </td>
                            <td></td>
                            <td><?php
                            if (empty($emp['se_tpurity'])) {
                                # code...
                            }else {
                                echo $emp['se_tpurity'];
                            }
                               
                                ?></td>

                            <td><?php
                             if (empty($emp['se_tpurity'])) {
                                # code...
                            }else {
                                echo $totalpur = abs(@$pudec = $emp['se_tpurity']);
                            }
                             ?></td>
                        </tr>
                <?php
                    }
                }

                $sqle = "SELECT SUM(se_tbill) as totalbill FROM `statement` where se_type = 'Debit' and se_date BETWEEN '$from' and '$to'";
                $rese = mysqli_query($conn, $sqle);
                $empe = mysqli_fetch_assoc($rese);
                $totalbill = floatval($empe['totalbill']);

                $sqlc = "SELECT SUM(se_tbill) as totalbillc FROM `statement` where se_type = 'Credit' and se_date BETWEEN '$from' and '$to'";
                $resc = mysqli_query($conn, $sqlc);
                $empc = mysqli_fetch_assoc($resc);
                $totalbillc = floatval($empc['totalbillc']);

                $sqlke = "SELECT SUM(se_tpurity) as totalbillk FROM `statement` where se_type = 'Debit' and se_date BETWEEN '$from' and '$to'";
                $reske = mysqli_query($conn, $sqlke);
                $empke = mysqli_fetch_assoc($reske);
                $totalbillk = floatval($empke['totalbillk']);

                $sqlkc = "SELECT SUM(se_tpurity) as totalbillkc FROM `statement` where se_type = 'Credit' and se_date BETWEEN '$from' and '$to'";
                $reskc = mysqli_query($conn, $sqlkc);
                $empkc = mysqli_fetch_assoc($reskc);
                $totalbillkc = floatval($empkc['totalbillkc']);
                ?>
                <tr class="text-white bg-primary">
                    <td colspan="3">Total</td>
                    <td><?= number_format($totalbill, 3) ?></td>
                    <td><?= number_format($totalbillc, 3) ?></td>
                    <td><?= $totalblanc = number_format($totalbill - $totalbillc, 3)?></td>
                    <td><?= @$totalbillk ?></td>
                    <td><?= $totalbillkc ?></td>
                    <td><?= $totalblang = number_format($totalbillk - $totalbillkc, 3) ?></td>
                </tr>
            </tbody>
        </table>
        <h5 style=" border: solid 1px black; padding:5px;">Balance Dollar <?= $totalblanc ?> &nbsp; &nbsp; Gold <?= $totalblang ?> </h5>
    </div>
    
    <div class="col-md-6">
                    <h5 class="bg-white p-2 text-primary text-center">Total money In Your Storage</h5>
                    <?php $sqli = "SELECT SUM(st_price) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash' and st_date BETWEEN '$from' and '$to'";
                    $resi = mysqli_query($conn, $sqli);
                    $cusi = mysqli_fetch_assoc($resi);

                    $sql = "SELECT SUM(st_price) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash' and st_date BETWEEN '$from' and '$to'";
                    $res = mysqli_query($conn, $sql);
                    $cus = mysqli_fetch_assoc($res);


                    $cuss = "SELECT SUM(of_money) as totalm FROM office where `state` = 'Cash' and of_date BETWEEN '$from' and '$to'";
                    $ress = mysqli_query($conn, $cuss);
                    $userc = mysqli_fetch_assoc($ress);
                    ?>

                    <h5>Total Debit Money: <span> <?= number_format($totaldeb = $cusi['total'],3) ?></span> </h5>
                    <h5>Total credit Money: <span> <?= number_format($totalcre =$cus['totalc'] + $userc['totalm'],3) ?></span></h5>
                    <?php

                    if ($totaldeb > $totalcre) {
                        echo "<h5>Total Debit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else  if ($totaldeb < $totalcre) {
                        echo "<h5>Total Credit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else {
                        echo "<h5>Total Exist: 0</h5>";
                    }
                    ?></span> </h5>
                </div>
                <div class="col-md-6">
                    <h5 class="bg-white p-2 text-primary text-center">Total Gold In Your Storage</h5>
                    <?php $sqli = "SELECT SUM(st_gold) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash' and st_date BETWEEN '$from' and '$to'";
                    $resi = mysqli_query($conn, $sqli);
                    $cusi = mysqli_fetch_assoc($resi);

                    $sql = "SELECT SUM(st_gold) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash' and st_date BETWEEN '$from' and '$to'";
                    $res = mysqli_query($conn, $sql);
                    $cus = mysqli_fetch_assoc($res);
                    ?>
                    <h5>Total Debit Gold: <span> <?=  number_format($totaldeb = $cusi['total'],3)?></span> </h5>
                    <h5>Total credit Gold: <span> <?=  number_format($totalcre = $cus['totalc'],3) ?></span></h5>
                    <?php

                    if ($totaldeb > $totalcre) {
                        echo "<h5>Total Debit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else  if ($totaldeb < $totalcre) {
                        echo "<h5>Total Credit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else {
                        echo "<h5>Total Exist: 0</h5>";
                    }
                    ?></span> </h5>
                </div>
</body>

</html>
<?php
        $html = ob_get_clean();

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Stream the PDF content directly to the browser
        $dompdf->stream('jewelry_invoice.pdf', array("Attachment" => false));
    } else {
        

        $to = date("Y-m-d");
?>
    <div class="header">
        <h3 style="margin: 4px;">HM Azim Jewelry </h3>
         <h5 style="margin: 0;">letter with Statement of Daily Statement</h5>
        <h6 style="padding:0; font-size:10px; margin:0;">Period Of <?= $to ?></h6>
    </div>
   

    <div class="table-responsive ">
        <table class="table text-start align-middle table-bordered table-hover mb-0">
            <thead>
                <tr class="text-white bg-primary text-center">
                    <th scope="col" rowspan="2">Date</th>
                    <th scope="col" rowspan="2">Doc. No</th>
                    <th scope="col" rowspan="2">Particulars Ref. No.</th>
                    <th colspan="3">Base Currecny Amount</th>
                    <th colspan="3">Gold Qty. In CT</th>

                <tr>
                    <th>debit</th>
                    <th>credit</th>
                    <th>Balance</th>
                    <th>debit</th>
                    <th>credit</th>
                    <th>Balance</th>
                </tr>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM `statement`";
                $res = mysqli_query($conn, $sql);
                while ($emp = mysqli_fetch_assoc($res)) {
                    $type = mysqli_escape_string($conn, $emp['se_type']);
                    if ($type == 'Debit') {
                ?>

                        <tr class="text-white bg-dark">
                            <td><?php @$timestamp = $emp['se_date'];
                                $am_pm_time = date("F j, Y", strtotime($timestamp));
                                echo $am_pm_time;
                                ?></td>
                            <td>DE00<?= $emp['se_id'] ?></td>
                            <td><?= $emp['dis'] ?></td>
                            <td><?= number_format($debb = $emp['se_tbill'], 3) ?> </td>
                            <td></td>
                            <td><?= @$totalbilld = number_format(@$debill = $emp['se_tbill'], 3) ?> </td>
                            <td><?php
                            if (empty($emp['se_tpurity'])) {
                                # code...
                            }else {
                                echo $emp['se_tpurity'];
                            }
                               
                                ?></td>
                            <td></td>
                            <td><?php
                             if (empty($emp['se_tpurity'])) {
                                
                            }else {
                                echo $totalpur = abs(@$pudec = $emp['se_tpurity']);
                            }
                             ?></td>
                        </tr>
                    <?php


                    }
                    if ($type == 'Credit') {
                    ?>

                        <tr class="text-white bg-dark">
                            <td><?php @$timestamp = $emp['se_date'];
                                $am_pm_time = date("F j, Y", strtotime($timestamp));
                                echo $am_pm_time;
                                ?></td>
                            <td>CR00<?= $emp['se_id'] ?></td>
                            <td><?= $emp['dis'] ?></td>
                            <td></td>
                            <td><?= number_format(@$debbillc = $emp['se_tbill'], 3)
                                ?> </td>

                            <td><?= number_format(@$totalse = $debbillc , 3) ?> </td>
                            <td></td>
                            <td><?php
                            if (empty($emp['se_tpurity'])) {
                                # code...
                            }else {
                                echo $emp['se_tpurity'];
                            }
                               
                                ?></td>

                            <td><?php
                             if (empty($emp['se_tpurity'])) {
                                # code...
                            }else {
                                echo $totalpur = abs(@$pudec = $emp['se_tpurity']);
                            }
                             ?></td>
                        </tr>
                <?php
                    }
                }

                $sqle = "SELECT SUM(se_tbill) as totalbill FROM `statement` where se_type = 'Debit'";
                $rese = mysqli_query($conn, $sqle);
                $empe = mysqli_fetch_assoc($rese);
                $totalbill = floatval($empe['totalbill']);

                $sqlc = "SELECT SUM(se_tbill) as totalbillc FROM `statement` where se_type = 'Credit'";
                $resc = mysqli_query($conn, $sqlc);
                $empc = mysqli_fetch_assoc($resc);
                $totalbillc = floatval($empc['totalbillc']);

                $sqlke = "SELECT SUM(se_tpurity) as totalbillk FROM `statement` where se_type = 'Debit'";
                $reske = mysqli_query($conn, $sqlke);
                $empke = mysqli_fetch_assoc($reske);
                $totalbillk = floatval($empke['totalbillk']);

                $sqlkc = "SELECT SUM(se_tpurity) as totalbillkc FROM `statement` where se_type = 'Credit'";
                $reskc = mysqli_query($conn, $sqlkc);
                $empkc = mysqli_fetch_assoc($reskc);
                $totalbillkc = floatval($empkc['totalbillkc']);
                ?>
                <tr class="text-white bg-primary">
                    <td colspan="3">Total</td>
                    <td><?= number_format($totalbill, 3) ?></td>
                    <td><?= number_format($totalbillc, 3) ?></td>
                    <td><?= $totalblanc = number_format($totalbill - $totalbillc, 3)?></td>
                    <td><?= @$totalbillk ?></td>
                    <td><?= $totalbillkc ?></td>
                    <td><?= $totalblang = number_format($totalbillk - $totalbillkc, 3) ?></td>
                </tr>
            </tbody>
        </table>
        <h5 style=" border: solid 1px black; padding:5px;">Balance Dollar <?= $totalblanc ?> &nbsp; &nbsp; Gold <?= $totalblang ?> </h5>
    </div>
    
    <div class="col-md-6">
                    <h5 class="bg-white p-2 text-primary text-center">Total money In Your Storage</h5>
                    <?php $sqli = "SELECT SUM(st_price) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                    $resi = mysqli_query($conn, $sqli);
                    $cusi = mysqli_fetch_assoc($resi);

                    $sql = "SELECT SUM(st_price) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                    $res = mysqli_query($conn, $sql);
                    $cus = mysqli_fetch_assoc($res);


                    $cuss = "SELECT SUM(of_money) as totalm FROM office where `state` = 'Cash' ";
                    $ress = mysqli_query($conn, $cuss);
                    $userc = mysqli_fetch_assoc($ress);
                    ?>

                    <h5>Total Debit Money: <span> <?= number_format($totaldeb = $cusi['total'],3) ?></span> </h5>
                    <h5>Total credit Money: <span> <?= number_format($totalcre =$cus['totalc'] + $userc['totalm'],3) ?></span></h5>
                    <?php

                    if ($totaldeb > $totalcre) {
                        echo "<h5>Total Debit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else  if ($totaldeb < $totalcre) {
                        echo "<h5>Total Credit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else {
                        echo "<h5>Total Exist: 0</h5>";
                    }
                    ?></span> </h5>
                </div>
                <div class="col-md-6">
                    <h5 class="bg-white p-2 text-primary text-center">Total Gold In Your Storage</h5>
                    <?php $sqli = "SELECT SUM(st_gold) as total FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                    $resi = mysqli_query($conn, $sqli);
                    $cusi = mysqli_fetch_assoc($resi);

                    $sql = "SELECT SUM(st_gold) as totalc FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                    $res = mysqli_query($conn, $sql);
                    $cus = mysqli_fetch_assoc($res);
                    ?>
                    <h5>Total Debit Gold: <span> <?=  number_format($totaldeb = $cusi['total'],3)?></span> </h5>
                    <h5>Total credit Gold: <span> <?=  number_format($totalcre = $cus['totalc'],3) ?></span></h5>
                    <?php

                    if ($totaldeb > $totalcre) {
                        echo "<h5>Total Debit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else  if ($totaldeb < $totalcre) {
                        echo "<h5>Total Credit: <span>",  number_format($totaldeb - $totalcre,3);
                        # code...
                    } else {
                        echo "<h5>Total Exist: 0</h5>";
                    }
                    ?></span> </h5>
                </div>


    </body>

    </html>
<?php
        $html = ob_get_clean();

        // Load HTML content into Dompdf
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Stream the PDF content directly to the browser
        $dompdf->stream('jewelry_invoice.pdf', array("Attachment" => false));
    }
?>