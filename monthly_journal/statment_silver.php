<?php require_once('tcpdf/tcpdf.php'); // Path to TCPDF library // Include database connection 
require "../config/Dbconn.php"; // Check database connection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Create new PDF document (Portrait, mm, A4) 
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
// Set document information 
$pdf->SetCreator('HM Azim Jewelry');
$pdf->SetAuthor('HM Azim Jewelry');
$pdf->SetTitle('Jewelry Sales Invoice');
$pdf->SetSubject('Statement of Account'); // Set default header/footer 
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false); // Set margins 
$pdf->SetMargins(10, 10, 10); // Set auto page breaks 
$pdf->SetAutoPageBreak(TRUE, 25); // Set font 
$pdf->SetFont('times', '', 8); // Add a page 
$pdf->AddPage(); // Start capturing HTML 
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Jewelry Sales Invoice</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: times, sans-serif;
            font-size: 6.5px;
        }

        table {
            border-collapse: collapse;
        }

        td {
            border: 1px solid black;
        }

        th {
            background-color: lightgray;
            border: 1px solid black;
        }
    </style>
</head>

<body> <?php if (!empty($_GET['from'])) {
            @$id = $_GET['id'];
            @$to = mysqli_escape_string($conn, $_GET['to']);
            @$from = mysqli_escape_string($conn, $_GET['from']);
            if (empty($to) and empty($from)) {
                $to = date("Y-m-d");
            } ?> <?php $sql = "SELECT * FROM customer where id =" . $_GET['id'];
                    $res = mysqli_query($conn, $sql);
                    $emp = mysqli_fetch_assoc($res); ?> <div class="table-responsive ">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white bg-primary text-center">
                        <th scope="col" colspan="6" style="text-align:center; background-color: white; padding-top:15px;">
                            <h4> HM Azim Jewelry <br> Letter with Statement of account with line <br> Period <?= $from ?> To <?= $to ?></h4>
                        </th>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;"> </td>
                    </tr>
                    <tr>
                        <th colspan="3"> <?= htmlspecialchars($emp['role']) ?>: <?= htmlspecialchars($emp['name']) ?> </th>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;"> </td>
                    </tr>
                </thead>
                <thead> <br>
                    <tr class="text-center">
                        <th width="60" rowspan="2">Date</th>
                        <th width="70" rowspan="2">Doc. No</th>
                        <th width="90" rowspan="2" class="left">Particulars</th>
                        <th width="157.5" colspan="3">Base Currency Amount</th>
                        <th width="157.8" colspan="3">Silver Quantity (CT)</th>
                    </tr>
                    <tr>
                        <th width="52.5">Debit</th>
                        <th width="52.5">Credit</th>
                        <th width="52.5">Balance</th>
                        <th width="52.5">Debit</th>
                        <th width="52.5">Credit</th>
                        <th width="52.5">Balance</th>
                    </tr>
                </thead>
                <tbody> <?php $sql = "SELECT * FROM statement where se_cus='$id' and se_date between '$from' and '$to'";
                        $res = mysqli_query($conn, $sql);
                        $debitTotal = 0;
                        $creditTotal = 0;
                        $goldDebitTotal = 0;
                        $goldCreditTotal = 0;
                        while ($row = mysqli_fetch_assoc($res)) {
                            $type = mysqli_real_escape_string($conn, $row['se_type']);
                            $date = date("F j, Y", strtotime($row['se_date']));
                            $se_tbill = floatval($row['se_tbill']); // Base currency amount 
                            $se_tpurity = floatval($row['se_tpurity']); // Silver quantity 
                            if ($type == "Debit") {
                                $debitTotal += $se_tbill;
                                $goldDebitTotal += $se_tpurity;
                            } elseif ($type == "Credit") {
                                $creditTotal += $se_tbill;
                                $goldCreditTotal += $se_tpurity;
                            } // Calculate running balances 
                            $balance = $debitTotal - $creditTotal;
                            $goldBalance = $goldDebitTotal - $goldCreditTotal; // Display values, showing empty string if 0 or 0.000 
                            $debitValue = ($type == "Debit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';
                            $creditValue = ($type == "Credit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';
                            $goldDebitValue = ($type == "Debit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';
                            $goldCreditValue = ($type == "Credit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';
                            // Format balances 
                            $balanceValue = ($balance < 0) ? number_format(abs($balance), 3) : number_format($balance, 3);
                            $goldBalanceValue = ($goldBalance < 0) ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3);
                        ?> <tr>
                            <td width="60"><?= htmlspecialchars($date) ?></td>
                            <td width="70" class="left"><?= $row['ref'] ?></td>
                            <td width="90" class="left"><?= $row['dis'] ?></td>
                            <td width="52.5"><?= $debitValue ? "($debitValue)" : '' ?></td>
                            <td width="52.5"><?= $creditValue ?></td>
                            <td width="52.5"><?= $balanceValue ?></td>
                            <td width="52.5"><?= $goldDebitValue ? "($goldDebitValue)" : '' ?></td>
                            <td width="52.5"><?= $goldCreditValue ?></td>
                            <td width="52.5"><?= $goldBalanceValue ?></td>
                        </tr> <?php } ?> <!-- Final total row -->
                    <tr class="text-primary">
                        <td colspan="3">Total</td>
                        <td><?= ($debitTotal != 0) ? "($" . number_format($debitTotal, 3) . ")" : '' ?></td>
                        <td><?= ($creditTotal != 0) ? number_format($creditTotal, 3) : '' ?></td>
                        <td><?= ($balance != 0) ? ($balance < 0 ? "($" . number_format(abs($balance), 3) . ")" : number_format($balance, 3)) : '' ?></td>
                        <td><?= ($goldDebitTotal != 0) ? "($" . number_format($goldDebitTotal, 3) . ")" : '' ?></td>
                        <td><?= ($goldCreditTotal != 0) ? number_format($goldCreditTotal, 3) : '' ?></td>
                        <td><?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '' ?></td>
                    </tr>
                </tbody>
            </table> <!-- Summary of Total Credit/Debit -->
            <div style="margin-top: 20px;"> <strong>Total Balance Amount (USD): </strong> <?= ($balance != 0) ? ($balance < 0 ? number_format(abs($balance), 3) : number_format($balance, 3)) : '' ?> <?php echo ($creditTotal > $debitTotal) ? "Credit" : "Debit"; ?> </div>
            <div> <strong>Total Silver Balance (KGS): </strong> <?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '' ?> <?php echo ($goldCreditTotal > $goldDebitTotal) ? "Credit" : "Debit"; ?> </div>
        </div> <?php $html = ob_get_clean(); // Output HTML content 
                $pdf->writeHTML($html, true, false, true, false, ''); // Output the PDF 
                $pdf->Output($emp['name'] . 'jewelry_invoice' . $to . '.pdf', 'I');
                // 'I' = inline display in browser 
            } else {
                $id = $_GET['id'];
                $to = date("Y-m-d");
            }
            if ($id) {
                ?> <style>
            /* Add your CSS styles here */
            body {
                font-family: times, sans-serif;
                font-size: 6.5px;
            }

            table {
                border-collapse: collapse;
            }

            td {
                border: 1px solid black;
            }

            th {
                background-color: lightgray;
                border: 1px solid black;
            }
        </style> <?php $sql = "SELECT * FROM customer WHERE id = $id";
                    $res = mysqli_query($conn, $sql);
                    $customer = mysqli_fetch_assoc($res); ?> <div class="table-responsive">
            <table>
                <thead>
                    <tr class="text-white bg-primary text-center">
                        <th scope="col" colspan="6" style="text-align:center; background-color: white; padding-top:15px;">
                            <h4> HM Azim Jewelry <br> Letter with Statement of account with line <br> Period <?= $from ?> To <?= $to ?></h4>
                        </th>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;"> </td>
                    </tr>
                    <tr>
                        <th colspan="3"> <?= htmlspecialchars($customer['role']) ?>: <?= htmlspecialchars($customer['name']) ?> </th>
                    </tr>
                    <tr style="border:none;">
                        <td style="border:none;"> </td>
                    </tr>
                </thead>
                <thead> <br>
                    <tr class="text-center">
                        <th width="60" rowspan="2">Date</th>
                        <th width="70" rowspan="2">Doc. No</th>
                        <th width="90" rowspan="2" class="left">Particulars</th>
                        <th width="157.5" colspan="3">Base Currency Amount</th>
                        <th width="157.8" colspan="3">Silver Quantity (CT)</th>
                    </tr>
                    <tr>
                        <th width="52.5">Debit</th>
                        <th width="52.5">Credit</th>
                        <th width="52.5">Balance</th>
                        <th width="52.5">Debit</th>
                        <th width="52.5">Credit</th>
                        <th width="52.5">Balance</th>
                    </tr>
                </thead>
                <tbody> <?php $sql = "SELECT * FROM statement WHERE se_cus='$id' ";
                        $res = mysqli_query($conn, $sql);
                        $debitTotal = 0;
                        $creditTotal = 0;
                        $goldDebitTotal = 0;
                        $goldCreditTotal = 0;
                        while ($row = mysqli_fetch_assoc($res)) {
                            $type = mysqli_real_escape_string($conn, $row['se_type']);
                            $date = date("F j, Y", strtotime($row['se_date']));
                            $se_tbill = floatval($row['se_tbill']); // Base currency amount 
                            $se_tpurity = floatval($row['se_tpurity']); // Silver quantity 
                            if ($type == "Debit") {
                                $debitTotal += $se_tbill;
                                $goldDebitTotal += $se_tpurity;
                            } elseif ($type == "Credit") {
                                $creditTotal += $se_tbill;
                                $goldCreditTotal += $se_tpurity;
                            } // Calculate running balances 
                            $balance = $debitTotal - $creditTotal;
                            $goldBalance = $goldDebitTotal - $goldCreditTotal; // Display values, showing empty string if 0 or 0.000 
                            $debitValue = ($type == "Debit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';
                            $creditValue = ($type == "Credit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';
                            $goldDebitValue = ($type == "Debit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';
                            $goldCreditValue = ($type == "Credit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : ''; // Format balances 
                            $balanceValue = ($balance < 0) ? number_format(abs($balance), 3) : number_format($balance, 3);
                            $goldBalanceValue = ($goldBalance < 0) ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3);
                        ?> <tr>
                            <td width="60"><?= htmlspecialchars($date) ?></td>
                            <td width="70" class="left"><?= $row['ref'] ?></td>
                            <td width="90" class="left"><?= $row['dis'] ?></td>
                            <td width="52.5"><?= $debitValue ? "($debitValue)" : '' ?></td>
                            <td width="52.5"><?= $creditValue ?></td>
                            <td width="52.5"><?= $balanceValue ?></td>
                            <td width="52.5"><?= $goldDebitValue ? "($goldDebitValue)" : '' ?></td>
                            <td width="52.5"><?= $goldCreditValue ?></td>
                            <td width="52.5"><?= $goldBalanceValue ?></td>
                        </tr> <?php } ?> <!-- Final total row -->
                    <tr class="text-primary">
                        <td colspan="3">Total</td>
                        <td><?= ($debitTotal != 0) ? "($" . number_format($debitTotal, 3) . ")" : '' ?></td>
                        <td><?= ($creditTotal != 0) ? number_format($creditTotal, 3) : '' ?></td>
                        <td><?= ($balance != 0) ? ($balance < 0 ? "($" . number_format(abs($balance), 3) . ")" : number_format($balance, 3)) : '' ?></td>
                        <td><?= ($goldDebitTotal != 0) ? "($" . number_format($goldDebitTotal, 3) . ")" : '' ?></td>
                        <td><?= ($goldCreditTotal != 0) ? number_format($goldCreditTotal, 3) : '' ?></td>
                        <td><?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '' ?></td>
                    </tr>
                </tbody>
            </table> <!-- Summary of Total Credit/Debit -->
            <div style="margin-top: 20px;"> <strong>Total Balance Amount (USD): </strong> <?= ($balance != 0) ? ($balance < 0 ? number_format(abs($balance), 3) : number_format($balance, 3)) : '0.000' ?> <?php echo ($creditTotal > $debitTotal) ? "Credit" : "Debit"; ?> </div>
            <div> <strong>Total Silver Balance (KGS): </strong> <?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '0.000' ?> <?php echo ($goldCreditTotal > $goldDebitTotal) ? "Credit" : "Debit"; ?> </div>
        </div> <?php }
            $html = ob_get_clean(); // Output HTML content 
            $pdf->writeHTML($html, true, false, true, false, ''); // Output the PDF 
            $pdf->Output($emp['name'] . 'jewelry_invoice' . $to . '.pdf', 'I'); // 'I' = inline display in browser 
                ?>