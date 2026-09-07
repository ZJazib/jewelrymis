
++<?php
require_once('../tcpdf/tcpdf.php'); // Make sure TCPDF is included
require "../config/Dbconn.php";

// Get dates
$fromDate = isset($_GET['from_date']) ? $_GET['from_date'] : null;
$toDate = isset($_GET['to_date']) ? $_GET['to_date'] : null;

// Function to get balances (same as your HTML)
function getAllCustomerBalances($conn, $fromDate, $toDate) {
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
        FROM customer c
        LEFT JOIN statement s ON c.id = s.se_cus
        WHERE s.se_cus > 2 ";

    if ($fromDate && $toDate) $sql .= " AND s.se_date BETWEEN ? AND ?";
    elseif ($fromDate) $sql .= " AND s.se_date >= ?";
    elseif ($toDate) $sql .= " AND s.se_date <= ?";

    $sql .= " GROUP BY c.id ORDER BY c.role";

    $stmt = $conn->prepare($sql);
    if ($fromDate && $toDate) $stmt->bind_param("ss", $fromDate, $toDate);
    elseif ($fromDate) $stmt->bind_param("s", $fromDate);
    elseif ($toDate) $stmt->bind_param("s", $toDate);

    $stmt->execute();
    return $stmt->get_result();
}

// Get data
$customers = getAllCustomerBalances($conn, $fromDate, $toDate);

// Storage totals
$totalDebitMoney = floatval(mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(st_price) as totaldebit FROM storage WHERE method='Debit' AND type='Cash'"))['totaldebit']);
$totalCreditMoney = floatval(mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(st_price) as totalcredit FROM storage WHERE method='Credit' AND type='Cash'"))['totalcredit']);
$totalMoneyBalance = abs($totalDebitMoney - $totalCreditMoney);

$totalDebitGold = floatval(mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(st_gold) as totalgt FROM storage WHERE method='Debit' AND type='Cash' "))['totalgt']);
$totalCreditGold = floatval(mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(st_gold) as totalgtt FROM storage WHERE method='Credit' AND type='Cash'"))['totalgtt']);
$totalGoldBalance = abs($totalCreditGold - $totalDebitGold);

// Create TCPDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('zia');
$pdf->SetTitle('Balance Statement');
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 10, 'Balance Statement', 0, 1, 'C');
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 7);
$html = '<table border="1" cellpadding="1">
<tr bgcolor="#007bff" color="#fff">
    <th>Role</th>
    <th>Name</th>
    <th>Debit Money</th>
    <th>Credit Money</th>
    <th>Debit Gold</th>
    <th>Credit Gold</th>
</tr>';

// Storage row
$html .= '<tr>
    <td>Storage</td>
    <td>Storage</td>
    <td>'.$totalMoneyBalance.'</td>
    <td></td>
    <td>'.$totalGoldBalance.'</td>
    <td></td>
</tr>';

// Customer rows
$pdf->SetFont('helvetica', '', 7);
while($customer = $customers->fetch_assoc()) {
    $moneyBalance = $customer['total_money_balance'];
    $goldBalance = $customer['total_gold_balance'];

    $html .= '<tr>
        <td>'.$customer['customer_role'].'</td>
        <td>'.$customer['customer_name'].'</td>
        <td>'.($moneyBalance < 0 ? number_format(abs($moneyBalance),3).' $':'').'</td>
        <td>'.($moneyBalance > 0 ? number_format($moneyBalance,3).' $':'').'</td>
        <td>'.($goldBalance < 0 ? number_format(abs($goldBalance),3):'').'</td>
        <td>'.($goldBalance > 0 ? number_format($goldBalance,3):'').'</td>
    </tr>';
}

// Totals
$totalLiability = abs($totalGoldBalance - $totalMoneyBalance);
$html .= '<tr bgcolor="#343a40" color="#fff">
    <td colspan="2" align="center">Total Balance</td>
    <td colspan="2" align="center">'.($totalMoneyBalance>0 ? "Credit: ".number_format($totalMoneyBalance,3)." USD":"Debit: ".number_format(abs($totalMoneyBalance),3)." USD").'</td>
    <td colspan="2" align="center">'.($totalGoldBalance>0 ? "Credit: ".number_format($totalGoldBalance,3)." GMS":"Debit: ".number_format(abs($totalGoldBalance),3)." GMS").'</td>
</tr>';

$html .= '</table>';

$pdf->writeHTML($html, true, false, false, false, '');

$pdf->Output('Balance_Statement.pdf', 'I');
?>
