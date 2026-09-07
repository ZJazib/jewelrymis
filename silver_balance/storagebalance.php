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
    // Display PHP errors for debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require "../config/Dbconn.php";

    // Function to fetch customer balances based on the selected date range
    function getCustomerProfitAndLoss($conn, $fromDate, $toDate)
    {
        $params = [];
        $types = "";
        $sql = "
            SELECT 
                s.st_id, 
                s.st_cus AS customer_id, 
                c.name,  -- Added customer name
                SUM(CASE WHEN s.method = 'Debit' THEN s.st_price ELSE 0 END) AS total_debit_money,
                SUM(CASE WHEN s.method = 'Credit' THEN s.st_price ELSE 0 END) AS total_credit_money,
                SUM(CASE WHEN s.method = 'Debit' THEN s.st_gold ELSE 0 END) AS total_debit_gold,
                SUM(CASE WHEN s.method = 'Credit' THEN s.st_gold ELSE 0 END) AS total_credit_gold
            FROM 
                storage s
            JOIN 
                customer c ON s.st_cus = c.id  -- Assuming customer table exists
            WHERE 
                s.type = 'Cash'";

        $sql .= " GROUP BY s.st_cus, s.st_id";  // Add s.st_id to GROUP BY

        $stmt = $conn->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    // Get balances with the applied date filter

    // Check if records are returned
    if ($customerRecords->num_rows === 0) {
        echo "No records found for the selected date range.";
    }

    // Initialize totals
    $totalMoneyDebit = 0;
    $totalMoneyCredit = 0;
    $totalGoldDebit = 0;
    $totalGoldCredit = 0;
    $totalMoneyBalance = 0;
    $totalGoldBalance = 0;
    ?>

    <div class="container-fluid my-5">
        <h3>Customer Balance and Profit/Loss Statement</h3>

        <!-- Date Filter Form -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col">
                    <input type="date" name="from_date" class="form-control" value="<?= htmlspecialchars($fromDate) ?>" placeholder="From Date">
                </div>
                <div class="col">
                    <input type="date" name="to_date" class="form-control" value="<?= htmlspecialchars($toDate) ?>" placeholder="To Date">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered my-4">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Customer Name</th>
                    <th>Customer ID</th>
                    <th>Profit/Loss (Money)</th>
                    <th>Profit/Loss (Gold)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($customer = $customerRecords->fetch_assoc()) :
                    // Calculate the Profit/Loss for Money and Gold
                    $customerMoneyBalance = $customer['total_credit_money'] - $customer['total_debit_money'];
                    $customerGoldBalance = $customer['total_credit_gold'] - $customer['total_debit_gold'];

                    // Summing total debit and credit values for money and gold
                    $totalMoneyDebit += max(0, -$customerMoneyBalance);
                    $totalMoneyCredit += max(0, $customerMoneyBalance);
                    $totalGoldDebit += max(0, -$customerGoldBalance);
                    $totalGoldCredit += max(0, $customerGoldBalance);

                    // Total balance calculations for money and gold
                    $totalMoneyBalance += $customerMoneyBalance;
                    $totalGoldBalance += $customerGoldBalance;

                    // Display empty space if profit or loss is zero
                    $moneyProfitLoss = ($customerMoneyBalance == 0) ? '' : ($customerMoneyBalance > 0 ? "Profit: " . number_format($customerMoneyBalance, 3) . " USD" : "Loss: " . number_format(abs($customerMoneyBalance), 3) . " USD");
                    $goldProfitLoss = ($customerGoldBalance == 0) ? '' : ($customerGoldBalance > 0 ? "Profit: " . number_format($customerGoldBalance, 3) . " GMS" : "Loss: " . number_format(abs($customerGoldBalance), 3) . " GMS");
                ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['name']) ?></td> <!-- Display customer name -->
                        <td><?= htmlspecialchars($customer['customer_id']) ?></td>
                        <td class="<?= $customerMoneyBalance < 0 ? 'bg-danger text-white' : ($customerMoneyBalance > 0 ? 'bg-success text-white' : '') ?>">
                            <?= $moneyProfitLoss ?>
                        </td>
                        <td class="<?= $customerGoldBalance < 0 ? 'bg-danger text-white' : ($customerGoldBalance > 0 ? 'bg-success text-white' : '') ?>">
                            <?= $goldProfitLoss ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
    <tr class="bg-light">
        <td colspan="2" class="text-right">Total Profit/Loss:</td>
        <td class="<?= $totalMoneyBalance < 0 ? 'bg-danger text-white' : ($totalMoneyBalance > 0 ? 'bg-success text-white' : '') ?>">
            <?= $totalMoneyBalance != 0 ? number_format($totalMoneyBalance, 3) . ' USD' : '' ?>
        </td>
        <td class="<?= $totalGoldBalance < 0 ? 'bg-danger text-white' : ($totalGoldBalance > 0 ? 'bg-success text-white' : '') ?>">
            <?= $totalGoldBalance != 0 ? number_format($totalGoldBalance, 3) . ' GMS' : '' ?>
        </td>
    </tr>
</tfoot>
        </table>

        
    </div>
</body>

</html>
