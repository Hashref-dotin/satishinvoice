<?php
session_start();
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
    $invoiceValues = $invoice->getInvoice($_GET['invoice_id']);
    $invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
}
$invoiceDate = date("d/m/Y, H:i:s", strtotime($invoiceValues['order_date']));

$output = '<html>
<head>
	<style>
		/** Define the margins of your page **/
		@page {
			margin-top: 120px;
			page-break-after: always; 
			margin-bottom:20px;
		}

		header {
			position: fixed;
			top: -110px;
			height: 100px;
			left: 0cm;
			right: 0cm;
			width:100%;
			margin-left:-50px;
			margin-right:-150px;
			
		}

		footer {
			position: fixed; 
			bottom: -35px; 
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
		<table cellpadding="0" cellspacing="0" align="center" width="100%" style="font-size:10.5px;color:#2E2586;border-top:1px solid #000;">
			<tr>
				<td width="25%"><strong>Bank Details</strong>
					<br/>Karnataka Bank,<br/>Mahalakshmipura<br/>A/c No: 1122000100046101<br/>IFSC: KARB 0000 112<br/>MICR: 560052032</td>
				<td width="25%">
					<strong>Bank Details</strong><br/>Canara Bank,<br/>Vijayanagara<br/>A/c No: 1146 285 000 003<br/>IFSC: CNRB 0001 146<br/>MICR: 560015062
				</td>
				<td width="25%">
					<strong>Regd. address</strong><br/>B69/A, 3rd Stage,<br/>Peenya Industrial Estate,<br/>Bangalore - 560058.<br/><strong>Warehouse:</strong> 553, 5th Main,<br/>Peenya 2nd Stage<br/>Bangalore - 560058.
				</td>
				<td width="25%">
					<strong>Registration</strong><br/>GST: 29ACHPS2669E1Z7<br/>PAN: ACHPS2669E<br/><strong>Contact</strong>:info@satishengineering.com<br/>+91 80 41272652 / 40942959<br/>www.satishengineering.com
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
				<span style="font-size:18px;font-weight:bold">' . $invoiceValues['datatype'] . ' ' . invoicenumber($invoiceValues['order_id']) . '</span>
				</td>
			</tr>
			</table>

			<table style="width:100%;border: 0.5px solid black;" cellpadding="4" cellspacing="0" align="center">
			<tr>
				<th align="center" width="5%" style="border: 0.5px solid black;">SL</th>
				<th align="center" width="40%" style="border: 0.5px solid black;">Description</th>
				<th align="center" width="10%" style="border: 0.5px solid black;">HSN/SAC</th>
				<th align="center" width="10%" style="border: 0.5px solid black;">Quantity</th>
				<th align="center" width="15%" style="border: 0.5px solid black;">Unit Price<br/> INR</th>
				<th align="center" width="6%" style="border: 0.5px solid black;">Tax<br/>%</th>
				<th align="center" width="15%" style="border: 0.5px solid black;">Gross Price<br/>INR</th>
			</tr>';
$count = 0;
$sgstavaialbe = 0;
$cgstavaialbe = 0;
$igstavaialbe = 0;
$dontbreakpage = false;

foreach ($invoiceItems as $invoiceItem) {
    $count++;

    $taxtext = '';
    $cgst = '';
    $sgst = '';
    $igst = '';
	$nameshow = '';
	
    $taxtext = '';
	$quantityGross = $invoiceItem["order_item_price"] * $invoiceItem["order_item_quantity"] ;
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
	
	if($count>6 && $single && !$dontbreakpage)
	{
		$output .= '<div class="page_break"></div>';
		$output .= '</table><table style="width:100%;border: 0.5px solid black;" cellpadding="4" cellspacing="0" align="center">';
			$dontbreakpage = true;
			
	}
    			$output .= '<tr>
					<td valign="top" rowspan="2" style="border: 0.5px solid black;">' . $count . '</td>
					<td valign="top">' . $invoiceItem["item_name"] . '</td>
					<td valign="top" rowspan="2"  style="border: 0.5px solid black;">' . $invoiceItem["item_code"] . '</td>
					<td valign="top" rowspan="2"  style="border: 0.5px solid black;">' . (int) $invoiceItem["order_item_quantity"] . '</td>
					<td valign="top" style="border: 0.5px solid black;" rowspan="2">' . currencyformat($invoiceItem["order_item_price"]) . '</td>
					<td valign="bottom" style="border: 0.5px solid black;" rowspan="2">' . $taxtext . '</td>
					<td valign="top">'.currencyformat($quantityGross).'</td>
				</tr>';
				$output .= '<tr>
					<td valign="bottom" style="border-bottom: 0.5px solid black;">' . $nameshow . '</td>
					<td style="border-bottom: 0.5px solid black;" valign="bottom">' . $grosstext . '</td>
				</tr>';
	



}
	$output .= '<tr>
					<td align="right" colspan="6" style="border-right: 0.5px solid black;"><strong>Total</strong> </td>
					<td align="left" style="font-weight:bold" style="border: 0.5px solid black;">' . currencyformat($invoiceValues['order_total_after_tax']) . '</td>
				</tr>
				<tr>
					<td align="left" colspan="7"  style="border: 0.5px solid black;"><strong>INR ' . inWords($invoiceValues['order_total_after_tax']) . '<strong></td>
				</tr>';
		$output .= '
			</table><br/>';




if ($sgstavaialbe || $cgstavaialbe || $igstavaialbe) {

if((count($invoiceItems) > 4 || count($invoiceItems) > 11) && !$dontbreakpage)
{
	$output .= '<div class="page_break"></div>';
}

if((count($invoiceItems) > 4 || count($invoiceItems) > 11) > 4)
{
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
					$output .= '<th>%</th><th>Value</th>';
				}
				if ($sgstavaialbe) {
					$output .= '<th>%</th><th>Value</th>';
				}
				if ($igstavaialbe) {
					$output .= '<th>%</th><th>Value</th>';
				}
			$output .= '</tr>';

		foreach ($invoiceItems as $invoiceItem) {

			$quantityGross = $invoiceItem["order_item_price"] * $invoiceItem["order_item_quantity"] ;
	

			$cgstper = ($invoiceItem["order_item_cgst"] > 0) ? currencyformat($invoiceItem["order_item_cgst"], false) . '%' : '--';
			$sgstper = ($invoiceItem["order_item_sgst"] > 0) ? currencyformat($invoiceItem["order_item_sgst"], false) . '%' : '--';
			$igstper = ($invoiceItem["order_item_igst"] > 0) ? currencyformat($invoiceItem["order_item_igst"], false) . '%' : '--';

			$cgsttax = ($quantityGross * $invoiceItem["order_item_cgst"]) / 100 ;
			$sgsttax = ($quantityGross * $invoiceItem["order_item_sgst"]) / 100 ;
			$igsttax = ($quantityGross * $invoiceItem["order_item_igst"]) / 100 ;

			$cgstamt = ($cgstper == '--') ? '--' : currencyformat($cgsttax);
			$sgstamt = ($sgstper == '--') ? '--' : currencyformat($sgsttax);
			$igstamt = ($igstper == '--') ? '--' : currencyformat($sgsttax);

			$totalTax = $cgsttax + $sgsttax + $igsttax;
			
			$output .= '<tr>
				<td with="15%">' . $invoiceItem["item_code"] . '</td>
				<td with="15%">' . currencyformat($quantityGross) . '</td>';
			
			if ($cgstavaialbe) {
				$output .= '<td>' . $cgstper . '</td><td>' . $cgstamt . '</td>';
			}
			if ($sgstavaialbe) {
				$output .=  '<td>' . $sgstper . '</td><td>' . $sgstamt . '</td>';
			}
			if ($igstavaialbe) {
				$output .= '<td>' . $igstper . '</td><td>' . $igstamt . '</td>';
			}

			$output .= '
				<td with="15%" >' . currencyformat($totalTax) . '</td>
			</tr>';
		}
		$output .= '</table>
			</td>
			</tr>
		</table>';

	}

$output .= '<br/>

<table cellpadding="0" cellspacing="0" border="0"  align="center" width="90%"style="font-size:10.5px">
	<tr>
		<td align="left">
			<span>For SATISHENGINEERING</span><br/>
			<img src="images/signature.jpg" style="width:80px"/><br/>
			<span>Authorised Signatory</span>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0"  align="center" width="90%"style="font-size:10.5px">
	<tr>
		<td width="75%" align="left">
			<pre style="font-size:10.5px;font-family:"Arial,sans-serif;">'.$invoiceValues['declaration'].'</pre>
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
$dompdf->stream($invoiceFileName, array("Attachment" => false));
