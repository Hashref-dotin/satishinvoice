<?php
session_start();
include 'Prowin.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
	$invoiceValues = $invoice->getInvoice($_GET['invoice_id']);
	$invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
}
$invoiceDate = $invoiceValues['order_date'];

function possiblelines($originalContent)
{
	$charWidth = 6;
	$divWidth = 376;
	$originalContent = trim($originalContent);
	$wrappedContent = wordwrap($originalContent, ($divWidth / $charWidth), "\r\n");
	$explodedLines = explode("\r\n", $wrappedContent);
	return count($explodedLines);
}


$output = '<html>
<head>
	<style>
		/** Define the margins of your page **/
		@page {
			margin-top: 140px;
			page-break-after: always;
		}

		header {
			position: fixed;
			top: -130px;
			left: 0cm;
			right: 0cm;
			width:100%;
			vertical-align:middle;
		}

		table.border {
			border-collapse: collapse;
		}

		table.border{
			border: 0.5px solid black;
		}
		table.border th{
			border-right: 0.5px solid black;
		}
		table.border th{
			border: 0.5px solid black;
			font-weight:normal;
		}
		p {
			margin: 0;
			padding: 0;
		}

		span
		{
			word-break:break-all;
		}

		footer {
			position: fixed; 
			bottom: -70px; 
			left: 0px; 
			right: 0px;
			height: 100px; 
			font-size: 12px;
		}

		.page_break { page-break-before: always; }

	</style>
</head>
	<header>
		<div style="width:100%;text-align:center;font-family:Helvetica !important">
		<div style="text-align:left;width:40%;float:left;"><img src="images/prowin_logo.png" style="width:210px;"/></div>
		<div style="text-align:right;width:55%;float:left;">
		<p style="font-size:13px;color:#2959AF;font-family: Arial Bold !important">553, 1st Floor, 4th Phase</p>
		<p style="font-size:13px;color:#2959AF;font-family: Arial Bold !important">5th Main, Peenya Industrial Area</p>
		<p style="font-size:13px;color:#2959AF;font-family: Arial Bold !important">Bengaluru 560058, India</p>
		<p style="font-size:13px;color:#2959AF;font-family: Arial Bold !important">Email: sunric@gmail.com</p>
		<p style="font-size:13px;color:#2959AF;font-family: Arial Bold !important">Mobile: +91 93798 25025</p>
		<p style="font-size:13px;color:#2959AF;font-family: Arial Bold !important">GSTIN: 29AZJPS9011P1ZH</p>
	     </div>
		</div>
	</header>
	<footer>
	<hr>
	<span style="color:#2959AF;font-family: Arial Bold !important">Bank Details:- </span><br/>
Bank name - Union Bank of india. Branch - Peenya. City - Bengaluru<br/>
A/C Number - 076321010000010. <br/>IFSC code - UBIN0907634. MICR - 560026120<br/>
	</footer>
';

$output .= '<main>

	<div style="font-size:12px;width=80%;font-family:"Arial,sans-serif;color:#575755">
		<table cellpadding="5" cellspacing="0"  align="center" width="95%">
			<tr>
			<td colspan="2" align="center">
				<table cellpadding="5"  align="center" style="width:100%" valign="top" >
					<tr>
						<td width="45%">
						<span style="font-size:14px;font-weight:bold">' . $invoiceValues['order_receiver_name'] . '</span><br />
						' . nl2br($invoiceValues['order_receiver_address']) . '<br />';
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
									' . $invoiceValues['datatype'] . ' Date : ' . date('d-m-Y', strtotime($invoiceDate)) . '<br />
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

			<table style="width:100%;" cellpadding="4" cellspacing="0" align="center" class="border">
			<tr style="font-weight:bold !important;">
				<th align="center" width="5%" >SL</th>
				<th align="center" width="46%" >Description</th>
				<th align="center" width="10%" >HSN/SAC</th>
				<th align="center" width="8%" >QTY</th>
				<th align="center" width="17%" >Unit Price<br/> INR</th>
				<th align="center" width="15%" >Gross Price<br/>INR</th></tr>';
$count = 0;
$sgstavaialbe = 0;
$cgstavaialbe = 0;
$igstavaialbe = 0;
$totaltaxable = 0;
$dontbreakpage = false;
$totallines = 0;
$cgst = 0;
$sgst = 0;
$igst = 0;
foreach ($invoiceItems as $invoiceItem) {
	$count++;

	$nameshow = '';
	$quantityGross = $invoiceItem["order_item_price"] * $invoiceItem["order_item_quantity"];
	$grosstext = '';

	$totallines = $totallines + possiblelines($invoiceItem["item_name"]) + 3;

	$single = false;
	if ($invoiceItem["order_item_cgst"] > 0) {
		$cgst =  $cgst + ($quantityGross * $invoiceItem["order_item_cgst"]) / 100;
		$nameshow .= '<p style="text-align:right">CGST &nbsp;&nbsp;&nbsp;&nbsp;' . currencyformat($invoiceItem["order_item_cgst"], false) . '% </p>';
		$cgstavaialbe = 1;
	}
	if ($invoiceItem["order_item_sgst"] > 0) {
		$sgst = $sgst + ($quantityGross * $invoiceItem["order_item_sgst"]) / 100;
		$nameshow .= '<p style="text-align:right">SGST &nbsp;&nbsp;&nbsp;&nbsp;' . currencyformat($invoiceItem["order_item_sgst"], false) . '% </p>';
		$sgstavaialbe = 1;
	}
	if ($invoiceItem["order_item_igst"] > 0) {
		$igst = $igst + ($quantityGross * $invoiceItem["order_item_igst"]) / 100;
		$taxtext .= '<p>' . currencyformat($igst) . '</p>';
		$nameshow .= '<p style="text-align:right">IGST &nbsp;&nbsp;&nbsp;&nbsp;' . currencyformat($invoiceItem["order_item_igst"], false) . '% </p>';
		$igstavaialbe = 1;
	}

	$totaltaxable = $totaltaxable + $quantityGross;

	if ($count > 8 && !$dontbreakpage) {
		$output .= '<div class="page_break"></div>';
		$output .= '</table><table style="width:100%;" class="border" cellpadding="4" cellspacing="0" align="center">';
		$dontbreakpage = true;
	}

	$output .= '<tr>
					<td valign="top" width="5%" algin="center" style="border-right: 0.5px solid #000;">' . $count . '</td>
					<td valign="top" width="46%" style="border-right: 0.5px solid #000;">' . nl2br($invoiceItem["item_name"]) . '<br/><br/><br/>' . '</td>
					<td valign="top" width="10%" style="text-align:center;border-right: 0.5px solid #000;">' . $invoiceItem["item_code"] . '</td>
					<td valign="top" width="8%" align="center" style="border-right: 0.5px solid #000;">' . (int) $invoiceItem["order_item_quantity"] . '</td>
					<td valign="top" width="17%" align="center" style="border-right: 0.5px solid #000;">' . currencyformat($invoiceItem["order_item_price"]) . '</td>
					<td valign="top" width="15%" style="text-align:right;border-right: 0.5px solid #000;">' . currencyformat($quantityGross) . '</td>
				</tr>';
}

$totaltax = $cgst + $sgst + $igst;
$totalpayable = $totaltaxable + $totaltax;


$taxtext = '';
if ($cgstavaialbe) {
	$taxtext .= '<p>' . currencyformat($cgst) . '</p>';
}
if ($sgstavaialbe) {
	$taxtext .= '<p>' . currencyformat($sgst) . '</p>';
}
if ($igstavaialbe) {
	$taxtext .= '<p>' . currencyformat($igst) . '</p>';
}


$output .= '<tr>
				<th align="right" colspan="5"><strong>Taxable value</strong></th>
				<th align="right">' . currencyformat($totaltaxable) . '</th>
				</tr>';
if ($nameshow != '') {
	$output .= '<tr>
				<th align="right" colspan="2">' . $nameshow . '</th>
				<th align="right" colspan="4" >' . $taxtext . '</th>
				</tr>';
}
$output .= '<tr>
				<th align="right" colspan="5"><strong>Total</strong></th>
				<th align="right"><strong>' . currencyformat($totalpayable) . '</strong></th>
				</tr>';
$output .= '<tr>
<th align="left" colspan="6" ><strong>INR ' . inWords($invoiceValues['order_total_after_tax']) . '<strong></th>
				</tr>';
$output .= '
			</table>';

if ($istaxisapplicable) {

	if ($totallines > 20 && count($invoiceItems) <= 4) {
		$output .= '<div class="page_break"></div>';
	}
	$output .= '<br/><table align="center" cellpadding="5" cellspacing="0" width="100%" class="border">
			<tr style="font-weight:bold !important;">
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
		$output .= '<th style="text-align:center">%</th><th style="text-align:center;font-weight:bold">Value</th>';
	}
	if ($sgstavaialbe) {
		$output .= '<th style="text-align:center">%</th><th style="text-align:center;font-weight:bold">Value</th>';
	}
	if ($igstavaialbe) {
		$output .= '<th style="text-align:center">%</th><th style="text-align:center;font-weight:bold">Value</th>';
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
				<th with="15%" style="text-align:center">' . $invoiceItem["item_code"] . '</th>
				<th with="15%" style="text-align:right">' . currencyformat($quantityGross) . '</th>';

		if ($cgstavaialbe) {
			$output .= '<th style="text-align:center">' . $cgstper . '</th><th style="text-align:right">' . $cgstamt . '</th>';
		}
		if ($sgstavaialbe) {
			$output .= '<th style="text-align:center">' . $sgstper . '</th><th style="text-align:right">' . $sgstamt . '</th>';
		}
		if ($igstavaialbe) {
			$output .= '<th style="text-align:center">' . $igstper . '</th><th style="text-align:right">' . $igstamt . '</th>';
		}

		$fulltaxablevalue = $fulltaxablevalue + $quantityGross;
		$fullsgst = $fullsgst + $sgsttax;
		$fullcgst = $fullcgst + $cgsttax;
		$fulligst = $fulligst + $igsttax;

		$output .= '
				<th with="15%" style="text-align:right">' . currencyformat($totalTax) . '</th>
			</tr>';
	}
	$wordspan = 2;
	$output .= '<tr>
	<th style="text-align:right;font-weight:bold">Total</th>
	<th with="15%" style="text-align:right;font-weight:bold">' . currencyformat($fulltaxablevalue) . '</th>';
	if ($cgstavaialbe) {
		$output .= '<th style="text-align:center">&nbsp;</th><th style="text-align:right;font-weight:bold">' . currencyformat($fullcgst) . '</th>';
		$wordspan = $wordspan + 2;
	}
	if ($sgstavaialbe) {
		$output .= '<th style="text-align:center">&nbsp;</th><th style="text-align:right;font-weight:bold">' . currencyformat($fullsgst) . '</th>';
		$wordspan = $wordspan + 2;
	}
	if ($igstavaialbe) {
		$output .= '<th style="text-align:center">&nbsp;</th><th style="text-align:right;font-weight:bold">' . currencyformat($fulligst) . '</th>';
		$wordspan = $wordspan + 2;
	}
	$wordspan = $wordspan + 1;
	$output .= '<th with="15%" style="text-align:right">' . currencyformat($finaltax) . '</th>';
	$output .= '</tr>';

	$output .= '<tr><th colspan="' . $wordspan . '" style="font-weight:bold">INR ' . inWords($finaltax) . '</th></tr>';



	$output .= '</table>
			</td>
			</tr>
		</table>';
}



if ($invoiceValues['termstrue'] && trim($invoiceValues['terms']) != '') {
	$output .= '
	<div style="font-size:10.5px;float:left;margin-left:30px;text-align:left !important">
	<p><strong style="font-weight:bold;font-size:12px;">Terms and conditions</strong></p>
	<p style="font-size:10.5px;font-family:"Arial,sans-serif;">' . nl2br($invoiceValues['terms']) . '</p>
	<br/>
	</div>
';
}

$output .= '
<div style="font-size:10.5px;margin-left:30px;text-align:left;">
<p><span>&nbsp;</span></p>
			<p style="font-size:10.5px;font-family:"Arial,sans-serif;">' . nl2br($invoiceValues['declaration']) . '</p>
		</div>
<div style="margin-left:30px;width:130px;text-align:center">
	<p><img src="images/prowin_sign.png" style="width:100px"/></p>
</div>
</div>
</main>
</html>';

// create pdf of invoice
$invoiceFileName =  $invoiceValues['datatype'] . '-' . invoicenumber($invoiceValues['invoice_id']) . '.pdf';
require_once 'dompdf/src/Autoloader.php';
Dompdf\Autoloader::register();

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml($output, 'UTF-8');
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$attachment = false;
if ($_GET['download'] == 1) {
	$attachment = true;
}
$dompdf->stream($invoiceFileName, array("Attachment" => $attachment));
