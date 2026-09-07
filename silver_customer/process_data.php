<?php
require_once "../config/Dbconn.php";

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['submit'])) {
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
    $dis = $_POST['dis'];
    $goldg = $_POST['goldg'];
    $priceg = $_POST['priceg'];
    $bill = $_POST['bill'];
    $pur = $_POST['purity'];



    $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','$goldg','Silver Customer','Cash','Credit','0')";
    mysqli_query($conn, $amo);

    $debs = "INSERT INTO `storage`(`st_cus`, `st_price`, `st_gold`, `type`, `method`, `name`) VALUES ('$id','0','$goldg','Cash','Credit','SILVER')";
    mysqli_query($conn, $debs);

    $sta = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'$goldg','$bill','Credit','Cash', 'SILVER SOLD <br> (WT - $goldg KGS PURITY -  $pur)')";
    mysqli_query($conn, $sta);


    $invoiceHtml = '<table style="min-width:100%; font-size:10px;">';
    $invoiceHtml .= '<tr>';
    $invoiceHtml .= '<td style="" colspan="1" >';
    $invoiceHtml .= ' <h4>H M AZIM JEWELLERY CO LLC </h4>';
    $invoiceHtml .=  '<h4>2st floor, Room# B-66, Gulbahar Center <br> Kabul  Afghanistan </h4>';
    $invoiceHtml .=  '<h4>Phone : +93 (0) 202 106 845 </h4>';
    $invoiceHtml .=  '<h4>Email : info@hmajewellery.com </h4>';
    $invoiceHtml .=  '</td >';
    $invoiceHtml .= '<th colspan="1" >';
    $invoiceHtml .=  '<img style="" src="../img/Capture.png" alt="" srcset="../img/Capture.png">';
    $invoiceHtml .=  '<h4>INVOICE <br> SALES VOUCHERS</p>';
    $invoiceHtml .=  '</th >';
    $invoiceHtml .= '<td style="text-align:right;" colspan="1" >';
    $invoiceHtml .= ' <h4>شرکت زرگری حاجی محمد عظیم </h4>';
    $invoiceHtml .=  '<h4>منزل دوم  دوکان نمبر (ب ۶۶) گلبهار سنتر <br> کابل افغانستان</p>';
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
    $invoiceHtml .=  '<p><b>Per KG Rate: ' . $priceg . ' </b></p>';
    $invoiceHtml .=  '<p style="color:white"><b> .</b></p>';
    $invoiceHtml .=  '<p style="color:white"><b> .</b></p>';
    $invoiceHtml .=  '<p style="color:white"><b> .</b></p>';
    $invoiceHtml .=  '</td >';

    $invoiceHtml .=  '</tr>';

    $invoiceHtml .=  '</table>';

 $invoiceHtml .= '<table border="1" cellpadding="2" cellspacing="0" style="font-size:10px; width:100%;">';

$invoiceHtml .= '<tr style="background:lightgray;">
    <th>NO | نمبر</th>
    <th>Description | تفصیلات</th>
    <th>Weight (KGS) | وزن گیلو گرام</th>
    <th>Purity | عیار</th>
    <th>Price Per KG $ (USD) | قیمت فی کیلو</th>
    <th>Total $ (USD) | قیمت مجموعی دالر</th>
</tr>';

    $invoiceHtml .= '<tr>';
    $invoiceHtml .= '<td>' . 1 . '</td>';
    $invoiceHtml .= '<td>' . $dis . '</td>';
    $invoiceHtml .= '<td>' . number_format($goldg, 3) . '</td>';
    $invoiceHtml .= '<td>' . number_format($pur, 3) . '</td>';
    $invoiceHtml .= '<td>' . number_format($priceg, 3) . '</td>';
    $invoiceHtml .= '<td>' . number_format($bill, 3) . '</td>';
    $invoiceHtml .= '</tr>';


// Total row
$invoiceHtml .= '<tr style="background:lightgray;">
    <td colspan="5"><b>Payable Amount |  پول قابل پرداخت</b></td>
    <td><b>' . number_format($bill, 3) . ' $</b></td>
</tr>';

$invoiceHtml .= '</table>';

// Signature section
$invoiceHtml .= '<div style="font-size:12px; margin-top:30px;">';
$invoiceHtml .= '<div style="float:left;">
                    <p>Customer | مشتری</p>
                    <p>Signature: .......................</p>
                 </div>';
$invoiceHtml .= '<div style="float:right;">
                    <p>Authorized Signature | شخص مسول</p>
                    <p>Signature: .......................</p>
                 </div>';
$invoiceHtml .= '</div>';




    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($invoiceHtml);
    $dompdf->setPaper('A5', 'portrait');
    $dompdf->render();

    $pdfPath = 'invoice.pdf';
    file_put_contents($pdfPath, $dompdf->output());

    echo $invoiceHtml;
    // // You can add further actions here if needed
    // header('Location: view_invoice.php?pdf=' . urlencode($pdfPath));
    // exit();

}
