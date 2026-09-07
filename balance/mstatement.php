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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
    <?php
    require "../config/Dbconn.php";

    // Get the selected 'from' and 'to' dates from the GET request, defaulting to null if not provided
    $fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : null;
    $toDate = isset($_GET['to_date']) ? $_GET['to_date'] : null;

    // Function to fetch customer balances based on the selected date range
    function getAllCustomerBalances($conn, $fromDate, $toDate)
    {
        // If no date filter is provided, select all records
        $sql = "
            SELECT 
                c.id AS customer_id, 
                c.name AS customer_name,
                c.role AS customer_role,
                SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tpurity ELSE 0 END) AS total_debit_gold,
                SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tpurity ELSE 0 END) AS total_credit_gold,
                (SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tpurity ELSE 0 END) - 
                 SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tpurity ELSE 0 END)) AS total_gold_balance,
                SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tbill ELSE 0 END) AS total_debit_money,
                SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tbill ELSE 0 END) AS total_credit_money,
                (SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tbill ELSE 0 END) - 
                 SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tbill ELSE 0 END)) AS total_money_balance
            FROM 
                customer c
            LEFT JOIN 
                statement s ON c.id = s.se_cus
            WHERE 
                s.se_cus > 2";

        // Add date filters if they are provided
        if ($fromDate && $toDate) {
            $sql .= " AND s.se_date BETWEEN ? AND ?";
        } elseif ($fromDate) {
            $sql .= " AND s.se_date >= ?";
        } elseif ($toDate) {
            $sql .= " AND s.se_date <= ?";
        }

        $sql .= " GROUP BY c.id  ORDER BY c.role";

        $stmt = $conn->prepare($sql);
        
        // Bind parameters if dates are provided
        if ($fromDate && $toDate) {
            $stmt->bind_param("ss", $fromDate, $toDate);
        } elseif ($fromDate) {
            $stmt->bind_param("s", $fromDate);
        } elseif ($toDate) {
            $stmt->bind_param("s", $toDate);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    // Get balances with the applied date filter
    $customers = getAllCustomerBalances($conn, $fromDate, $toDate);

    // Initialize totals
    $totalDebitMoney = 0;
    $totalCreditMoney = 0;
    $totalDebitGold = 0;
    $totalCreditGold = 0;
    $totalMoneyBalance = 0;
    $totalGoldBalance = 0;
    
    $customerGoldBalances = [];
    $customerMoneyBalances = [];
    ?>

    <div class="container-fluid my-5">
        <h3 class="text-center">Balance Statement</h3>
<div class="text-end mb-3">
    <a href="print_blance.php" class="btn btn-success"><i class="fa fa-file-pdf"></i> Generate PDF</a>
</div>
      <table class="table table-bordered my-4">
                                <thead>
                                    <tr class="bg-primary text-white">
                                   
                                         <th>Role</th>
                                        <th>Name</th>
                                        <th>Debit Money</th>
                                        <th>Credit Money</th>
                                        <th>Debit Gold</th>
                                        <th>Credit Gold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    

                                                                        <?php
                                    $sqlt = "SELECT SUM(st_price) as totaldebit FROM `storage` where `method` = 'Debit' and `type` = 'Cash'";
                                    $rest = mysqli_query($conn, $sqlt);
                                    $cust = mysqli_fetch_assoc($rest);


                                    $sqltt = "SELECT SUM(st_price) as totalcredit FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                                    $restt = mysqli_query($conn, $sqltt);
                                    $custt = mysqli_fetch_assoc($restt);

                                    $totalDebit_money = floatval($cust['totaldebit']);
                                    $totalCredit_money = floatval($custt['totalcredit']);
                                    $total_money_balance = abs($totalDebit_money - $totalCredit_money);
                                    ?>


                                    <?php

                                    $sqltg = "SELECT SUM(st_gold) as totalgt FROM `storage` where `method` = 'Debit' and `type` = 'Cash' ";
                                    $resitg = mysqli_query($conn, $sqltg);
                                    $custg = mysqli_fetch_assoc($resitg);

                                    $sqlgtt = "SELECT SUM(st_gold) as totalgtt FROM `storage` where `method` = 'Credit' and `type` = 'Cash'";
                                    $resgtt = mysqli_query($conn, $sqlgtt);
                                    $cusgtt = mysqli_fetch_assoc($resgtt);

                                    @$totaldebtg = floatval($custg['totalgt']);
                                    @$totaldebttg = floatval($cusgtt['totalgtt']);
                                    $total_gold_balance = abs($totaldebttg - $totaldebtg);
                                    ?>
                                    <tr>
                                        <td>Storage</td>
                                        <td>Storage</td>
                                        <td class="<?= ($total_money_balance  ?: '') ?>">
                                            <?= ($total_money_balance  ?: '') ?>
                                        </td>
                                        <td class="=">

                                        </td>
                                        <td class="<?= ($total_gold_balance ?: '') ?>">
                                            <?= number_format(($total_gold_balance ?: ''), 3) ?>
                                        </td>
                                        <td class="">

                                        </td>

                                    </tr>
                                    <?php
                                    while ($customer = $customers->fetch_assoc()) {
                                        $moneyBalance = $customer['total_money_balance'];
                                        $goldBalance = $customer['total_gold_balance'];

                                        $debitMoney = number_format($customer['total_debit_money'], 3);
                                        $creditMoney = number_format($customer['total_credit_money'], 3);
                                        $debitGold = number_format($customer['total_debit_gold'], 3);
                                        $creditGold = number_format($customer['total_credit_gold'], 3);

                                        // Calculate overall totals
                                        $totalDebitMoney += $customer['total_debit_money'];
                                        $totalCreditMoney += $customer['total_credit_money'];
                                        $totalDebitGold += $customer['total_debit_gold'];
                                        $totalCreditGold += $customer['total_credit_gold'];
                                      $totalMoneyBalance = $totalCreditMoney - ($totalDebitMoney + $total_money_balance);
$totalGoldBalance = $totalCreditGold - ($totalDebitGold + $total_gold_balance);
                                        ?><tr>
                                             <td><?= $customer['customer_role'] ?></td>
                                       
                                        <td><?= $customer['customer_name'] ?></td>
                                        <td class="<?= ($moneyBalance < 0 ? 'debit-class' : '') ?>">
                                            <?= $moneyBalance < 0 ? number_format(abs($moneyBalance), 3) . ' $' : '' ?>
                                        </td>
                                        <td class="<?= ($moneyBalance > 0 ? 'credit-class' : '') ?>">
                                            <?= $moneyBalance > 0 ? number_format($moneyBalance, 3) . ' $' : '' ?>
                                        </td>
                                        <!-- Gold Balance: Display in Debit or Credit column based on value -->
                                        <td class="<?= ($goldBalance < 0 ? 'debit-class' : '') ?>">
                                            <?= $goldBalance < 0 ? number_format(abs($goldBalance), 3) : '' ?>
                                        </td>
                                        <td class="<?= ($goldBalance > 0 ? 'credit-class' : '') ?>">
                                            <?= $goldBalance > 0 ? number_format($goldBalance, 3) : '' ?>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                        
                                            
        $totalLiability = abs($totalGoldBalance - $totalMoneyBalance);
        $customerGoldBalances[] = $totalGoldBalance;
        $customerMoneyBalances[] = $totalMoneyBalance;
    
                                            ?>

                                </tbody>
                                <tfoot>
                                    <tr class="bg-dark text-white">
                                        <td colspan="2" rowspan="2" class="text-center">Total Balance</td>
                                  
                                        <td colspan="2" class="text-center">
                                            <?= $totalMoneyBalance != 0 ? (($totalMoneyBalance) > 0 ? "Credit: " . number_format($totalMoneyBalance, 3) . " USD" : "Debit: " . number_format(abs($totalMoneyBalance), 3) . " USD") : '';?>
                                        </td>
                                        <td colspan="2" class="text-center">
                                            <?=  $totalGoldBalance != 0 ? ($totalGoldBalance > 0 ? "Credit: " . number_format($totalGoldBalance, 3) . " GMS" : "Debit: " . number_format(abs($totalGoldBalance), 3) . " GMS") : '';?>
                                        </td>
                                    </tr>
                                      <tr class="bg-dark text-white">
                                    
                                        <td colspan="2">
                                            <div class="">Ounce</div>
            <input type="number" id="own" name="own" class="form-control" value="1" oninput="calculateTotals()"> </td>
         <td colspan="2">
             <div class="liability-type"><?= $totalLiability < 0 ? 'Debit' : 'Credit' ?></div>
            <input type="text" class="form-control total-liability" readonly>
            
      </td>
     </tr>
                                
                                </tfoot>
                            </table>
                   
        

    <script>
        const customerGoldBalances = <?= json_encode($customerGoldBalances) ?>;
        const customerMoneyBalances = <?= json_encode($customerMoneyBalances) ?>;

        function calculateTotals() {
            let own = parseFloat(document.getElementById("own").value) || 1;
            document.querySelectorAll(".total-liability").forEach((input, index) => {
                let goldBalance = customerGoldBalances[index];
                let moneyBalance = customerMoneyBalances[index];
                let total = ((own / 31.1035 * 3.674 / 3.67 * goldBalance) + moneyBalance).toFixed(3);
                input.value = Math.abs(total);

                let liabilityType = total < 0 ? 'Debit' : 'Credit';
                document.querySelectorAll(".liability-type")[index].innerText = liabilityType;
            });
        }
    </script>
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
document.getElementById('generatePDF').addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'pt', 'a4');

    // Select the table container
    const table = document.querySelector('.table');

    html2canvas(table, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const imgProps = doc.getImageProperties(imgData);
        const pdfWidth = doc.internal.pageSize.getWidth();
        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

        doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        doc.save('Balance_Statement.pdf');
    });
});
</script>
</body>
</html>