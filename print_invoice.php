<?php
session_start();
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
    $invoiceValues = $invoice->getInvoice($_GET['invoice_id']);
    $invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
}
$invoiceDate = $invoiceValues['order_date'];

$output = '<html>
<head>
	<style>
		/** Define the margins of your page **/
		@page {
			margin-top: 150px;
			page-break-after: always;
		}

		header {
			position: fixed;
			top: -95px;
			height: 100px;
			left: 0cm;
			right: 0cm;
			width:100%;
			margin-left:-50px;
			margin-right:-150px;

		}

		footer {
			position: fixed;
			bottom: -55px;
			height: 100px;
			width: 100%;
		}

		table.border {
			border-collapse: collapse;
		}

		table.border{
			border: 0.5px solid black;
		}
		table.border td{
			border: 0.5px solid black;
		}
		table.border th{
			border: 0.5px solid black;
		}
		p {
			margin: 0;
			padding: 0;
		}

		span
		{
			word-break:break-all;
		}

		.page_break { page-break-before: always; }

	</style>
</head>

	<header>
		<div style="margin:0px;width:100%;height:100px;background-color:#24157B"><img src="images/pdf_logo.png" style="height:100px;"/></div>
	</header>

	<footer>
		<table cellpadding="0" cellspacing="0" border="0"  align="center" width="100%" style="font-size:10.5px;color:#000">
			<tr>
				<td width="25%" valign="top"><strong style="color:#2E2586">Bank Details</strong>
					<br/>Karnataka Bank,<br/>Mahalakshmipura<br/>A/c No: 1122 000 100 0461 01<br/>IFSC: KARB 0000 112<br/>MICR: 560052032</td>
				<td width="25%" valign="top">
					<strong style="color:#2E2586">Bank Details</strong><br/>Canara Bank,<br/>Vijayanagara<br/>A/c No: 1146 285 000 003<br/>IFSC: CNRB 0001 146<br/>MICR: 560015062
				</td>
				<td width="25%" valign="top">
					<strong style="color:#2E2586">Regd. address</strong><br/>B69/A, 3rd Stage,<br/>Peenya Industrial Estate,<br/>Bangalore - 560058.<br/><strong style="color:#2E2586">Warehouse:</strong> 553, 5<sup>th</sup> Main,<br/>Peenya 2<sup>nd</sup> Stage<br/>Bangalore - 560058.
				</td>
				<td width="25%" valign="top">
					<strong style="color:#2E2586">Registration</strong><br/>GST: 29ACHPS2669E1Z7<br/>PAN: ACHPS2669E<br/><strong style="color:#2E2586">Contact</strong>:info@satishengineering.com<br/>+91 80 41272652 / 40942959<br/>www.satishengineering.com
				</td>
			</tr>
		</table>
	</footer>';

$output .= '<main>

	<div style="font-size:12px;width=80%;font-family:"Arial,sans-serif;color:#575755">
		<table cellpadding="5" cellspacing="0"  align="center" width="95%">
			<tr>
			<td colspan="2" align="center">
				<table cellpadding="5"  align="center" style="width:100%" valign="top" >
					<tr>
						<td width="45%">
						<span style="font-size:14px;font-weight:bold">' . $invoiceValues['order_receiver_name'] . '</span><br />
						' . $invoiceValues['order_receiver_address'] . '<br />';
if (!empty($invoiceValues['mobile'])) {
    $output .= 'Mobile : ' . $invoiceValues['mobile'] . '<br />';
}
if (!empty($invoiceValues['email'])) {
    $output .= 'Email id : ' . $invoiceValues['email'] . '<br />';
}
if (!empty($invoiceValues['gst'])) {
    $output .= 'GSTIN/UIN : ' . $invoiceValues['gst'] . '<br />';
}
if (!empty($invoiceValues['statecode'])) {
    $output .= 'State Code : ' . $invoiceValues['statecode'] . '<br />';
}

$istaxisapplicable = false;

if ($invoiceValues['enable_csgst'] == 1 || $invoiceValues['enable_igst'] == 1) {
    $istaxisapplicable = true;
}

$output .= '</td>
								<td width="30%">&nbsp;</td>
								<td width="35%" valign="top">
									Reference : ' . $invoiceValues['reference'] . '<br />
									Delivery Note : ' . $invoiceValues['deliverynote'] . '<br />
									Invoice Date : ' . $invoiceDate . '<br />
						</td>
					</tr>
			</table>

			<table cellpadding="5" cellspacing="0"  align="center" width="100%" >
			<tr>
				<td align="left">
				<span style="font-size:18px;font-weight:bold">' . $invoiceValues['datatype'] . ' ' . invoicenumber($invoiceValues['invoice_id']) . '</span>
				</td>
			</tr>
			</table>

			<table style="width:100%;border: 0.5px solid black;" cellpadding="4" cellspacing="0" align="center">
			<tr>
				<th align="center" width="5%" style="border: 0.5px solid black;">SL</th>
				<th align="center" width="40%" style="border: 0.5px solid black;">Description</th>
				<th align="center" width="10%" style="border: 0.5px solid black;">HSN/SAC</th>
				<th align="center" width="8%" style="border: 0.5px solid black;">QTY</th>
				<th align="center" width="17%" style="border: 0.5px solid black;">Unit Price<br/> INR</th>';
if ($istaxisapplicable) {
    $output .= '<th align="center" width="6%" style="border: 0.5px solid black;">Tax<br/>%</th>';
}

$output .= '<th align="center" width="15%" style="border: 0.5px solid black;">Gross Price<br/>INR</th></tr>';
$count = 0;
$sgstavaialbe = 0;
$cgstavaialbe = 0;
$igstavaialbe = 0;
foreach ($invoiceItems as $invoiceItem) {
    $count++;

    $taxtext = '';
    $cgst = '';
    $sgst = '';
    $igst = '';
    $nameshow = '';

    $taxtext = '';
    $quantityGross = $invoiceItem["order_item_price"] * $invoiceItem["order_item_quantity"];
    $grosstext = '';

    $single = false;
    if ($invoiceItem["order_item_cgst"] > 0) {
        $cgst = ($quantityGross * $invoiceItem["order_item_cgst"]) / 100;
        $taxtext .= '<p>' . currencyformat($invoiceItem["order_item_cgst"], false) . '% </p>';
        $grosstext .= '<p >' . currencyformat($cgst) . '</p>';
        $nameshow .= '<p style="text-align:right">CGST</p>';
        $cgstavaialbe = 1;
        $single = true;
    }
    if ($invoiceItem["order_item_sgst"] > 0) {
        $sgst = ($quantityGross * $invoiceItem["order_item_sgst"]) / 100;
        $taxtext .= '<p>' . currencyformat($invoiceItem["order_item_sgst"], false) . '% </p>';
        $grosstext .= '<p>' . currencyformat($sgst) . '</p>';
        $nameshow .= '<p style="text-align:right">SGST</p>';
        $sgstavaialbe = 1;
        $single = true;
    }
    if ($invoiceItem["order_item_igst"] > 0) {
        $igst = ($quantityGross * $invoiceItem["order_item_igst"]) / 100;
        $taxtext .= '<p>' . currencyformat($invoiceItem["order_item_igst"], false) . '% </p>';
        $grosstext .= '<p>' . currencyformat($igst) . '</p>';
        $nameshow .= '<p style="text-align:right;width:100%">IGST</p>';
        $igstavaialbe = 1;
        $single = true;
    }
    $dontbreakpage = false;

    if ($count > 6 && $single) {
        $output .= '<div class="page_break"></div>';
        $output .= '</table><table style="width:100%;border: 0.5px solid black;" cellpadding="4" cellspacing="0" align="center">';
        $dontbreakpage = true;

    }
	$addbreaks = '';
	if (!$istaxisapplicable) {
		$addbreaks = '<br/><br/>';
	}
    $output .= '<tr>
					<td valign="top" algin="center" rowspan="2" style="border: 0.5px solid black;text-align:center">' . $count . '</td>
					<td valign="top">' . $invoiceItem["item_name"].$addbreaks . '</td>
					<td valign="top" rowspan="2" algin="center" style="border: 0.5px solid black;text-align:center">' . $invoiceItem["item_code"] . '</td>
					<td valign="top" rowspan="2"  style="border: 0.5px solid black;text-align:center">' . (int) $invoiceItem["order_item_quantity"] . '</td>
					<td valign="top" style="border: 0.5px solid black;text-align:center" rowspan="2">' . currencyformat($invoiceItem["order_item_price"]) . '</td>';
    $grossspan = "'";
    if ($istaxisapplicable) {
        $output .= '<td valign="bottom" style="border: 0.5px solid black;text-align:center" rowspan="2">' . $taxtext . '</td>';
    }

    $output .= '<td valign="top" style="text-align:right">' . currencyformat($quantityGross) . '</td>
				</tr>';
    $output .= '<tr>
					<td valign="bottom" style="border-bottom: 0.5px solid black;">' . $nameshow . '</td>
					<td style="border-bottom: 0.5px solid black;text-align:right" valign="bottom">' . $grosstext . '</td>
				</tr>';

}
$totalspan = '6';
$wordsspan = '7';
if (!$istaxisapplicable) {
    $totalspan = '5';
    $wordsspan = '6';
}


$output .= '<tr>
				<td align="right" colspan="' . $totalspan . '" style="border-right: 0.5px solid black;"><strong>Total</strong> </td>
				<td align="right" style="font-weight:bold" style="border: 0.5px solid black;">' . currencyformat($invoiceValues['order_total_after_tax']) . '</td>
				</tr>
				<tr>
					<td align="left" colspan="' . $wordsspan . '"  style="border: 0.5px solid black;"><strong>INR ' . inWords($invoiceValues['order_total_after_tax']) . '<strong></td>
				</tr>';
$output .= '
			</table><br/>';

if ($istaxisapplicable) {

    if (count($invoiceItems) > 4 && !$dontbreakpage) {
        $output .= '<div class="page_break"></div>';
    }

    if (count($invoiceItems) > 4) {
        $output .= '<div style="height:50px">&nbsp;</div>';
    }
    $output .= '<table align="center" cellpadding="5" cellspacing="0" width="100%" class="border">
			<tr>
				<th with="20%" rowspan="2" align="center">HSN/SAC</th>
				<th with="20%" rowspan="2" align="center">Taxable Value</th>';

    if ($cgstavaialbe) {
        $output .= '<th colspan="2" with="16%"  align="center">CGST</th>';
    }
    if ($sgstavaialbe) {
        $output .= '<th colspan="2" with="16%"  align="center">SGST</th>';
    }
    if ($igstavaialbe) {
        $output .= '<th colspan="2" with="16%"  align="center">IGST</th>';
    }

    $output .= '<th with="14%" rowspan="2" align="center">Total<br/>Tax Amount</th></tr>';

    $output .= '<tr>';
    if ($cgstavaialbe) {
        $output .= '<th style="text-align:center">%</th><th style="text-align:center">Value</th>';
    }
    if ($sgstavaialbe) {
        $output .= '<th style="text-align:center">%</th><th style="text-align:center">Value</th>';
    }
    if ($igstavaialbe) {
        $output .= '<th style="text-align:center">%</th><th style="text-align:center">Value</th>';
    }
    $output .= '</tr>';

	$fulltaxablevalue = 0;
	$fullsgst = 0;
	$fullcgst = 0;
	$fulligst = 0;
	$finaltax = 0;
    foreach ($invoiceItems as $invoiceItem) {

        $quantityGross = $invoiceItem["order_item_price"] * $invoiceItem["order_item_quantity"];

        $cgstper = ($invoiceItem["order_item_cgst"] > 0) ? currencyformat($invoiceItem["order_item_cgst"], false) . '%' : '--';
        $sgstper = ($invoiceItem["order_item_sgst"] > 0) ? currencyformat($invoiceItem["order_item_sgst"], false) . '%' : '--';
        $igstper = ($invoiceItem["order_item_igst"] > 0) ? currencyformat($invoiceItem["order_item_igst"], false) . '%' : '--';

        $cgsttax = ($quantityGross * $invoiceItem["order_item_cgst"]) / 100;
        $sgsttax = ($quantityGross * $invoiceItem["order_item_sgst"]) / 100;
        $igsttax = ($quantityGross * $invoiceItem["order_item_igst"]) / 100;

        $cgstamt = ($cgstper == '--') ? '--' : currencyformat($cgsttax);
        $sgstamt = ($sgstper == '--') ? '--' : currencyformat($sgsttax);
        $igstamt = ($igstper == '--') ? '--' : currencyformat($igsttax);

		$totalTax = $cgsttax + $sgsttax + $igsttax;
		$finaltax = $finaltax + $totalTax;

        $output .= '<tr>
				<td with="15%" style="text-align:center">' . $invoiceItem["item_code"] . '</td>
				<td with="15%" style="text-align:right">' . currencyformat($quantityGross) . '</td>';

        if ($cgstavaialbe) {
            $output .= '<td style="text-align:center">' . $cgstper . '</td><td style="text-align:right">' . $cgstamt . '</td>';
        }
        if ($sgstavaialbe) {
            $output .= '<td style="text-align:center">' . $sgstper . '</td><td style="text-align:right">' . $sgstamt . '</td>';
        }
        if ($igstavaialbe) {
            $output .= '<td style="text-align:center">' . $igstper . '</td><td style="text-align:right">' . $igstamt . '</td>';
        }

		$fulltaxablevalue = $fulltaxablevalue + $quantityGross;
		$fullsgst = $fullsgst + $sgstamt;
		$fullcgst = $fullcgst + $cgstamt;
		$fulligst = $fulligst + $igstamt;

        $output .= '
				<td with="15%" style="text-align:right">' . currencyformat($totalTax) . '</td>
			</tr>';
	}
	$wordspan = 2;
	$output .='<tr style="font-weight:bold"><td style="text-align:right">Total</td>
	<td with="15%" style="text-align:right">' . currencyformat($fulltaxablevalue) . '</td>';
	if ($cgstavaialbe) {
		$output .= '<td style="text-align:center">&nbsp;</td><td style="text-align:right">' . $fullcgst . '</td>';
		$wordspan = $wordspan + 2;
	}
	if ($sgstavaialbe) {
		$output .= '<td style="text-align:center">&nbsp;</td><td style="text-align:right">' . $fullsgst . '</td>';
		$wordspan = $wordspan + 2;
	}
	if ($igstavaialbe) {
		$output .= '<td style="text-align:center">&nbsp;</td><td style="text-align:right">' . $fulligst . '</td>';
		$wordspan = $wordspan + 2;
	}
	$wordspan = $wordspan + 1;
	$output .= '<td with="15%" style="text-align:right">' . currencyformat($finaltax) . '</td>';
	$output .= '</tr>';

	$output .= '<tr style="font-weight:bold"><td colspan="'.$wordspan.'">INR '.inWords($finaltax).'</td></tr>';

	

    $output .= '</table>
			</td>
			</tr>
		</table>';

}

if($invoiceValues['termstrue'] && trim($invoiceValues['terms']) !='')
{
	$output .= '<br/><table cellpadding="0" cellspacing="0" border="0"  align="center" width="90%" style="font-size:10.5px">
	<tr><td><strong style="font-weight:bold">Terms and conditions</strong>:</td></tr>
	<tr>
		<td width="75%" align="left">
			<p style="font-size:10.5px;font-family:"Arial,sans-serif; word-wrap: break-word;max-width:300px;">' . wordwrap($invoiceValues['terms'],200,"<br>\n") . '</p>
		</td>
	</tr>
	</table>';
}




$output .= '<br/>

<table cellpadding="0" cellspacing="0" border="0"  align="center" width="90%" style="font-size:10.5px">
	<tr>
		<td align="left">
			<span>For SATISHENGINEERING</span><br/>
			<img src="images/signature.jpg" style="width:80px"/><br/>
			<span>Authorised Signatory</span>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0"  align="center" width="90%">
	<tr>
		<td width="75%" align="left">
			<pre style="font-size:10.5px;font-family:"Arial,sans-serif; word-wrap: break-word;max-width:300px;">' . $invoiceValues['declaration'] . '</pre>
		</td>
	</tr>
</table>
</div>
</main>
</html>';

// create pdf of invoice

$invoiceFileName = 'Invoice-' . $invoiceValues['order_id'] . '.pdf';
require_once 'dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($output, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$attachment = false;
if($_GET['download'] == 1)
{
	$attachment = true;
}
$dompdf->stream($invoiceFileName, array("Attachment" => $attachment));
