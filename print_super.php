<?php
session_start();
include 'Super.php';
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

$translate = array ('Invoice' => 'Rechnung',
					'Proforma' => 'Proforma - Rechnung',
				'Quoatation'=>'Zitat',
			'Purchase Order'=>'Bestellung');

$output = '<html>
<head>
	<style>
		/** Define the margins of your page **/
		@page {
			margin-top: 190px;
			padding-top:20px;
			page-break-after: always;
			margin-bottom:40px;
		}

		header {
			position: fixed;
			top: -150px;
			height: 100px;
			left: 0cm;
			right: 0cm;
			width:100%;
			text-align:center
		}

		footer {
			position: fixed;
			bottom: -52px;
			height: 100px;
			width: 100%;
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

		.page_break { page-break-before: always; margin-top:60px; }

	</style>
</head>

	<header>
		<div style="margin:0px;"><img src="images/super.png" style="width:670px;"/>
		<p style="font-size:14px;text-align:left !important;color:#231F20;font-family: Arial Bold !important">&nbsp; &nbsp;&nbsp; &nbsp;<span style="text-decoration: underline;">SupertechS Werkzeugmaschinen GmbH, Kurt-Blaum - Platz 8- 63450 Hanau</span></p></div>
		
	</header>

	<footer>
		<table cellpadding="0" cellspacing="0" border="0"  align="center" width="90%" style="font-size:10.5px;color:#000">
			<tr>
				<td width="33%" valign="top" align="center" style="text-align:left">
				<strong>CommerzBank-60311- Frankfurt Am Main</strong><br/>Konto Nr. : 3343357<br/>BLZ : 500 400 00<br/>IBAN : DE04 5004 0000 0334 3357 00<br/>SWIFT-BIC : COBADEFFXXX
				</td>
				<td width="8%">&nbsp;</td>
				<td width="29%" valign="top" align="center" style="text-align:left">
				<strong>SupertechS Werkzeugmaschinen GmbH</strong><br/>Kurt-Blaum - Platz 8<br/>63450 Hanau<br/>E-mail: info@supertechsgmbh.de<br/>Tele:   +49 1521 4417 176
				</td>
				<td width="8%">&nbsp;</td>
				<td width="22%" valign="top" align="center" style="text-align:left">
					<strong>Handelsregister</strong><br/>HRB 96758<br/>Amtsgericht: Hanau<br/>Steuer Nr.: 044 243 46735<br/>Steuer ID: DE321263139
				</td>
			</tr>
		</table>
	</footer>';

$output .= '<main>

	<div style="font-size:12px;width=80%;font-family:"Arial;color:#575755">
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
    $output .= 'Tax id: ' . $invoiceValues['gst'] . '<br />';
}
if (!empty($invoiceValues['statecode'])) {
    $output .= 'State Code : ' . $invoiceValues['statecode'] . '<br />';
}

$istaxisapplicable = false;

if ($invoiceValues['enable_igst'] == 1) {
    $istaxisapplicable = true;
}

$output .= '</td>
								<td width="30%">&nbsp;</td>
								<td width="35%" valign="top">
									'.$translate[$invoiceValues['datatype']].' Datum : ' . date('d-m-Y', strtotime($invoiceDate)) . '<br />
						</td>
					</tr>
			</table>

			<table cellpadding="5" cellspacing="0"  align="center" width="100%" >
			<tr>
				<td align="left">
				<span style="font-size:18px;font-weight:bold">' . $translate[$invoiceValues['datatype']] . ' ' . invoicenumber($invoiceValues['invoice_id']) . '</span>
				</td>
			</tr>
			</table>

			<table style="width:100%;" cellpadding="4" cellspacing="0" align="center" class="border">
			<tr style="font-weight:bold !important;">
				<th align="center" width="5%" >S No.</th>
				<th align="center" width="46%" >Beschreibung</th>
				<th align="center" width="8%" >Menge</th>
				<th align="center" width="17%" >Einzelpries<br/> EUR</th>
				<th align="center" width="15%" >Gesamtpreis<br/>EUR</th></tr>';
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
        $nameshow .= '<p style="text-align:right">CGST &nbsp;&nbsp;&nbsp;&nbsp;'.currencyformat($invoiceItem["order_item_cgst"], false) . '% </p>';
        $cgstavaialbe = 1;
    }
    if ($invoiceItem["order_item_sgst"] > 0) {
		$sgst = $sgst + ($quantityGross * $invoiceItem["order_item_sgst"]) / 100;
        $nameshow .= '<p style="text-align:right">SGST &nbsp;&nbsp;&nbsp;&nbsp;'.currencyformat($invoiceItem["order_item_sgst"], false) . '% </p>';
        $sgstavaialbe = 1;
	}
	$igstavaialbe = 1;
		$igst = $igst + ($quantityGross * $invoiceItem["order_item_igst"]) / 100;
		$taxtext .= '<p>'.currencyformat($igst).'</p>';
		$nameshow .= '<p style="text-align:right">Ex - Steuerwert &nbsp;&nbsp;&nbsp;&nbsp;'.currencyformat($invoiceItem["order_item_igst"], false) . '% </p>';
        

	
	$totaltaxable = $totaltaxable + $quantityGross;

    if ($count > 8 && !$dontbreakpage) {
        $output .= '<div class="page_break"></div>';
        $output .= '</table><table style="width:100%;" class="border" cellpadding="4" cellspacing="0" align="center">';
        $dontbreakpage = true;
	}
	
    $output .= '<tr>
					<td valign="top" width="5%" algin="center" style="border-right: 0.5px solid #000;">' . $count . '</td>
					<td valign="top" width="46%" style="border-right: 0.5px solid #000;">' . nl2br($invoiceItem["item_name"]) . '<br/><br/><br/>' . '</td>
					<td valign="top" width="8%" align="center" style="border-right: 0.5px solid #000;">' . (int) $invoiceItem["order_item_quantity"] . '</td>
					<td valign="top" width="17%" align="center" style="border-right: 0.5px solid #000;">' . currencyformat($invoiceItem["order_item_price"]) . '</td>
					<td valign="top" width="15%" style="text-align:right;border-right: 0.5px solid #000;">' . currencyformat($quantityGross) . '</td>
				</tr>';
}

$totaltax = $cgst + $sgst + $igst;
$totalpayable = $totaltaxable + $totaltax;


$taxtext = '';
if ($cgstavaialbe) {
	$taxtext .= '<p>'.currencyformat($cgst).'</p>';
}
if ($sgstavaialbe) {
	$taxtext .= '<p>'.currencyformat($sgst).'</p>';
}
if ($igstavaialbe) {
	$taxtext .= '<p>'.currencyformat($igst).'</p>';
}


$output .= '<tr>
				<th align="right" colspan="4"><strong>Gesamt Netto</strong></th>
				<th align="right">' . currencyformat($totaltaxable) . '</th>
				</tr>';
				if($igstavaialbe)
				{
					$output .= '<tr>
					<th align="right" colspan="2">'.$nameshow.'</th>
					<th align="right" colspan="3" >' .$taxtext . '</th>
					</tr>';
				}
				$output .= '<tr>
				<th align="right" colspan="4"><strong>Gesamtbetrag</strong></th>
				<th align="right"><strong>' . currencyformat($totalpayable) . '</strong></th>
				</tr>';
$output .= '<tr>
<th align="left" colspan="5" ><strong>EURO ' . inWords($invoiceValues['order_total_after_tax']) . '<strong></th>
				</tr>';
$output .= '
			</table>';

$output .= '</table>
			</td>
			</tr>
		</table>';

if($invoiceValues['termstrue'] && trim($invoiceValues['terms']) !='')
{
	$output .= '
	<div style="font-size:10.5px;float:left;margin-left:30px;text-algin:left">
	<p><strong style="font-weight:bold;font-size:12px;">Geschäftsbedingungen</strong></p>
	<p style="font-size:10.5px;font-family:"Arial,sans-serif;">' . nl2br($invoiceValues['terms']) . '</p>
	<br/>
	</div>
';
}

$output .= '
<div style="font-size:12px;margin-left:30px;width:130px;text-align:center">
	<p><span>Für Supertechs</span></p>
	<p><img src="images/signature.png" style="width:80px"/></p>
	<p>Zeichnungsberechtigte</p>
</div>

<div style="font-size:10.5px;margin-left:30px;text-align:left">
<br/>
			<p style="font-size:10.5px;font-family:"Arial,sans-serif;">' . nl2br($invoiceValues['declaration']) . '</p>
		</div>
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
