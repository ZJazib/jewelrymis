<?php
 require_once "../config/Dbconn.php";

 require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

  if(isset($_POST['insert'])){
    $id =  $_GET['t'];
    $cuss = "SELECT * FROM customer WHERE id = '$id'";
    $ress = mysqli_query($conn, $cuss);
    $userc = mysqli_fetch_assoc($ress);
    $name = $userc['name'];
    $nic = $userc['nic'];
    $phone = $userc['phone'];
    $email = $userc['email'];
    $loc = $userc['loc'];
    // invoice inserteration
    $role = $userc['role'];
    $ref = $_POST['ref'];
    $bill = $_POST['bill'];
    $totalg = $_POST['totalg'];
    $Paid = $_POST['Paid'];
    $pur = $_POST['pur'];
    @$totalpure = $_POST['totalpure'];


        $deb = "INSERT INTO `debit`(`dep_cus`, `ref`, `dep_tgold`, `dep_tbill`, `dep_tpurity`, `sate`, `com`, `typec`) VALUES ('$id','$ref','$totalg','$bill','$totalpure','Debit','0','$Paid')";
        mysqli_query($conn, $deb);

        $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','$totalpure','Customer','$Paid','Debit','0')";
        mysqli_query($conn, $amo);

        $debs = "INSERT INTO `storage`(`st_cus`, `st_price`, `st_gold`, `type`, `method`, `name`) VALUES ('$id','0','$totalpure','$Paid','Debit','gold')";
        mysqli_query($conn, $debs);

        $sta = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','$totalg','$totalpure','$bill','Debit','$Paid', 'JEWELLERY SOLD <br> (GROSS WT - $totalg GMS)')";
        mysqli_query($conn, $sta);


    $goldCount = count($_POST['dis']);

   $invoiceHtml = '<table style="min-width:100%; font-size:10px;">';
    $invoiceHtml .= '<tr>';
    $invoiceHtml .= '<td style="" colspan="1" >';
    $invoiceHtml .= ' <h4>H M AZIM JEWELLERY CO LLC </h4>';
    $invoiceHtml .=  '<h4>2st floor, Room# B-66, Gulbahar Center Kabul <br> Afghanistan </h4>';
    $invoiceHtml .=  '<h4>Phone : +93 (0) 202 106 845 </h4>';
    $invoiceHtml .=  '<h4>Email : info@hmajewellery.com </h4>';
    $invoiceHtml .=  '</td >';
    $invoiceHtml .= '<th colspan="1" >';
    $invoiceHtml .=  '<img style="" src="../images/Capture.png" alt="" srcset="../images/Capture.png">';
    $invoiceHtml .=  '<h4>INVOICE <br> SALES VOUCHERS</p>';
    $invoiceHtml .=  '</th >';
    $invoiceHtml .= '<td style="text-align:right;" colspan="1" >';
    $invoiceHtml .= ' <h4>شرکت زرگری حاجی محمد عظیم </h4>';
    $invoiceHtml .=  '<h4>منزل دوم  دوکان نمبر (ب ۶۶) گلبهار سنتر  کابل افغانستان</p>';
    $invoiceHtml .=  '<h4>+93 (0) 202 106 845 : شماره تماس </h4>';
    $invoiceHtml .=  '<h4>info@hmajewellery.com : ایمیل </h4>';
    $invoiceHtml .=  '</td >';  

    $invoiceHtml .=  '</tr>';

    $invoiceHtml .=  '</table>';


    $invoiceHtml .= '<table border="1" cellspacing="0" cols style="min-width:100%; font-size:10px; margin-bottom:5px;">';
    $invoiceHtml .=  '<tr>';
    $invoiceHtml .= '<td style="padding:10px;" >';
    $invoiceHtml .=  '<p style="border-bottom:1px dotted red;"><b>' . $nic . " - " . $name . '</b> </p>';
    $invoiceHtml .=  '<p>Address: ' . $loc . ' </p>';
    $invoiceHtml .=  '<p>Tell: ' . $phone . ' </p>';
    $invoiceHtml .=  '<p>Email: ' . $email . ' </p>';
    $invoiceHtml .=  '<p>P.B. NO: ' . date("m0ji") . ' </p>';
    $invoiceHtml .=  '</td >';

    $invoiceHtml .= '<td style="padding:10px;" >';
    $invoiceHtml .=  '<p style="border-bottom:1px dotted red;"><b>Date: ' . date("F j, Y") . ' </b></p>';
    $invoiceHtml .=  '<p><b>Metal Rate:  </b></p>';
    $invoiceHtml .=  '<p style="color:white"><b> .</b></p>';
    $invoiceHtml .=  '<p style="color:white"><b> .</b></p>';
    $invoiceHtml .=  '<p style="color:white"><b> .</b></p>';
    $invoiceHtml .=  '</td >';

    $invoiceHtml .=  '</tr>';

    $invoiceHtml .=  '</table>';

    $invoiceHtml .= '<table border=1; cellpadding="2" cellspacing="0" style="font-size:10px; width:100%; min-width: fit-content;">';

    
    $invoiceHtml .= '<tr style="background:lightgray; "><th rowspan="2" >NO</th><th rowspan="2" style="padding-right:50px; padding-left:50px;">Description</th><th rowspan="2">Gold Gross Weight (GMS)</th> <th rowspan="2">Purity</th><th rowspan="2">Pure Weight</th> <th rowspan="2">Stone Weight (GMS)</th> <th colspan="2">Making</th> <tr style="background:lightgray;"><th>Rate /Grams (AED)</th><th>Amount (USD)</th></tr></tr>';
    
    $dis = $_POST['dis'];
    $goldg = $_POST['goldg'];
    $stone = $_POST['stone'];
    $purity = $_POST['purity'];
    $priceg = $_POST['priceg'];
    $mc = $_POST['mc'];
    $crry = $_POST['crry'];
    $bill = $_POST['bill'];
    $totalpure = floatval($_POST['totalpure']);
    $totalg = floatval($_POST['totalg']);
    $totalmc = floatval($_POST['totalmc']);
    $tax = $_POST['tax'];
    $pur = floatval($_POST['pur']);

    $crryFloat = floatval($crry);
    $billFloat = floatval($bill);
    $totaltax = $tax * $totalmc / 100;
    $totalcrry =  $totalg * $crryFloat / 1000;
    $no = 1;
    for ($i = 0; $i < $goldCount; $i++) {
        $dis = $_POST['dis'][$i];
        $goldg = $_POST['goldg'][$i];
        $stone = $_POST['stone'][$i];
        $purity = $_POST['purity'][$i];
        $priceg = $_POST['priceg'][$i];
        $mc = $_POST['mc'][$i];

        $pur = floatval($_POST['pur'][$i]);


        $goldgFloat = floatval($goldg);
        $purityFloat = floatval($purity);
        $pricegFloat = floatval($priceg);
        $mcFloat = floatval($mc);
        $purFloat = floatval($pur);
        $stoneFloat = floatval($stone);
        $no = 1;
        $invoiceHtml .= '<tr>';
        $invoiceHtml .= '<td>' . $no + $i . ' </td>';
        $invoiceHtml .= '<td>' . $dis . '</td>';
        $invoiceHtml .= '<td>' . number_format($goldgFloat, 3) . '</td>';
        $invoiceHtml .= '<td>' . number_format($purityFloat, 3) . '</td>';
        $invoiceHtml .= '<td>' . number_format($purFloat, 3) . '</td>';
        $invoiceHtml .= '<td>' . number_format($stoneFloat, 3) . '</td>';
        $invoiceHtml .= '<td>' . number_format($pricegFloat, 3) . ' Dr</td>';
        $invoiceHtml .= '<td>' . number_format($mcFloat, 0) . ' $</td>';
        $invoiceHtml .= '</tr>';
    }


    $invoiceHtml .= '<tr style="background:lightgray;">';
    $invoiceHtml .= '<td colspan="2" >Total</td>';
    $invoiceHtml .= '<td>' . number_format($totalg, 3) . '</td>';
    $invoiceHtml .= '<td></td>';
    $invoiceHtml .= '<td>' . number_format($totalpure, 3) . '</td>';
    $invoiceHtml .= '<td></td>';
    $invoiceHtml .= '<td></td>';
    $invoiceHtml .= '<td>' . number_format($totalmc) . ' $</td>';

    $invoiceHtml .= '</tr>';
    $invoiceHtml .= '<tr>';
    $invoiceHtml .= '<td colspan="6">Total Carry Charge </td>';
    $invoiceHtml .= '<td>' . number_format($crry) . ' $</td>';
    $invoiceHtml .= '<td>' . number_format($totalcrry) . ' $</td>';

    $invoiceHtml .= '</tr>';
    $invoiceHtml .= '<tr>';
    $invoiceHtml .= '<td colspan="6" >Total Tax</td>';
    $invoiceHtml .= '<td>' . number_format($tax) . '%</td>';
    $invoiceHtml .= '<td>' . number_format($totaltax) . ' $</td>';

    $invoiceHtml .= '</tr>';
    $invoiceHtml .= '<tr>';
    $invoiceHtml .= '<td colspan="7">Total Invoice Amount</td>';
    $invoiceHtml .= '<td>' . number_format($billFloat) . ' $</td>';

    $invoiceHtml .= '</tr>';


    $invoiceHtml .= '<tr>';
    $invoiceHtml .= '<td colspan="8">' . number_format($billFloat) . ' $ DEBITED</td>';

    $invoiceHtml .= '</tr>';
    $invoiceHtml .= '</table>';

    $invoiceHtml .= '<div style=" font-size:12px; padding-bottom:50px;">';
    $invoiceHtml .= '<div style=" position: absolute;">';
    $invoiceHtml .= '<p>Customer</p>';
    $invoiceHtml .=  '<p style="padding-top:15px;">Signature:.......................</p>';
    $invoiceHtml .=  '</div>';

    $invoiceHtml .= '<div style="position: absolute; margin-left:40%; width:250px;">';

    $invoiceHtml .=  '<h2 style=""></h2>';
    $invoiceHtml .=  '</div>';

    $invoiceHtml .= '<div style="margin-left:75%; width:250px;">';
    $invoiceHtml .=  '<p>Authorized Signature</p>';
    $invoiceHtml .=  '<p style="padding-top:15px;">Signature:.......................</p>';
    $invoiceHtml .=  '</div>';
    $invoiceHtml .=  '</div>';



    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($invoiceHtml);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $pdfPath = 'invoice.pdf';
    file_put_contents($pdfPath, $dompdf->output());

    echo $invoiceHtml;
    // // You can add further actions here if needed
    // header('Location: view_invoice.php?pdf=' . urlencode($pdfPath));
    // exit();

}
?>