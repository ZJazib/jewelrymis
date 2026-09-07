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
    require "../config/Dbconn.php";
    ?>
    <div class="container-fluid my-5">
        <div class="rounded align-items-center bg-light justify-content-center mx-0">
            <div class="container py-2">
                <a class="text-center btn btn-dark m-3 w-25" href="../index.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b></a>

                <h4 class="text-center my-4 bg-primary rounded p-2 text-white">Daily Journal روز نامچه</h4>
                
             
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
                            $sql = "SELECT * FROM `statement`";
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
                                <td class=""><?= $row['dis'] ?></td>
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
</body>
</html>
