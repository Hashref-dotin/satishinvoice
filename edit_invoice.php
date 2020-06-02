<?php
session_start();
include 'header.php';
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
if (!empty($_POST['companyName']) && $_POST['companyName'] && !empty($_POST['invoiceId']) && $_POST['invoiceId']) {
    $invoice->updateInvoice($_POST);
    if (isset($_POST['invoice_btn'])) {
        header("Location:invoice_list.php");
    }
    if (isset($_POST['invoice_btn_stay'])) {
        $message = "Updated Successfully";
    }
}
if (!empty($_GET['update_id']) && $_GET['update_id']) {
    $invoiceValues = $invoice->getInvoice($_GET['update_id']);
    $invoiceItems = $invoice->getInvoiceItems($_GET['update_id']);
}

include 'SMS.php';
if(isset($_POST['send_sms']))
{
	$sms = new SMS();
	$message = $sms->sendSMS($invoice->invoiceOrderTable,$_GET['update_id']);
}
?>
<title><?php echo WEBPAGE_TITLE; ?></title>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css">

<script src="js/invoice.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include 'container.php';?>
<div class="container content-invoice">
    	<form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate="" onsubmit="return calculateTotal()">
	    	<div class="load-animate animated fadeInUp">
		    	<div class="row">
		    		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">

						<?php include 'menu.php';?>
		    		</div>
		    	</div>
				<?php
if (!empty($message)) {
    ?>
		<div class="alert alert-success" role="alert">
  <?php echo $message; ?>
</div>
					<?php
}
?>

<?php
if (!empty($errormessage)) {
    ?>
		<div class="alert alert-danger" role="alert">
  <?php echo $errormessage; ?>
</div>
					<?php
}
?>


		      	<input id="currency" type="hidden" value="$">
		    	<div class="row">
		      		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-left">
					<div class="form-group">
						<h3 class="title"><?php echo $invoiceValues['datatype'] . ' ' . invoicenumber($invoiceValues['invoice_id']); ?></h3>
					</div>
					<div class="form-group invisible">
						<select name="datatype" id="datatype" readonly>
							<option value="Invoice" <?php if ($invoiceValues['datatype'] == 'Invoice') {echo "selected";}?> >Invoice</option>
							<option value="Proforma"  <?php if ($invoiceValues['datatype'] == 'Proforma') {echo "selected";}?> >Proforma</option>
							<option value="Quotation"  <?php if ($invoiceValues['datatype'] == 'Quotation') {echo "selected";}?> >Quotation</option>
						</select>
					</div>
						
		      		<div class="form-group">
						  <label>Company Name (or) Purchase Name</label>
							<input value="<?php echo $invoiceValues['order_receiver_name']; ?>" type="text" class="form-control" name="companyName" id="companyName" placeholder="Company Name" autocomplete="off">
					</div>
					<div class="form-group">
						<label>Address</label>
							<textarea class="form-control" rows="3" name="address" id="address" placeholder="Your Address"><?php echo $invoiceValues['order_receiver_address']; ?></textarea>
					</div>
					<div class="form-group">
						<label>GSTIN/UIN</label>
							<input type="text" class="form-control" name="gst" id="gst" placeholder="GSTIN/UIN" autocomplete="off" required value="<?php echo $invoiceValues['gst']; ?>">
					</div>
					<div class="form-group">
						<label>Mobile Number</label>
							<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $invoiceValues['mobile']; ?>" maxlength="12">
					</div>
					<div class="form-group">
						<label>Email ID</label>
							<input type="text" class="form-control" name="email" id="email" placeholder="Email Id" autocomplete="off" required value="<?php echo $invoiceValues['email']; ?>">
					</div>
					<div class="form-group">
						<label>State Code</label>
							<input type="text" class="form-control" name="statecode" id="statecode" placeholder="State code(Example: 29, Karnataka)" autocomplete="off" required value="<?php echo $invoiceValues['statecode']; ?>">
					</div>
					<div class="form-group">
						<label>Reference ID</label>
						<input type="text" class="form-control" name="reference" id="reference" placeholder="Reference ID" autocomplete="off" required value="<?php echo $invoiceValues['reference']; ?>">
					</div>
					
					<div class="form-check">
							<input type="checkbox" class="form-check-input" name="enable_csgst" id="enable_csgst" autocomplete="off" value="1" <?php echo ($invoiceValues['enable_csgst'] == 1) ? 'checked' : ''; ?>>
							<label class="form-check-label">Enable SGST/CGST</label>
							<?php  $csgstvisible = '';
							if($invoiceValues['enable_csgst'] == 0) {
								$csgststyle = 'style="display:none"';
							}  ?>
					</div>
					<div class="form-check">
							<input type="checkbox" class="form-check-input" name="enable_igst" id="enable_igst" autocomplete="off" value="1" <?php echo ($invoiceValues['enable_igst'] == 1) ? 'checked' : ''; ?>><label class="form-check-label">Enable IGST</label>
							<?php  $igstvisible = '';
							if($invoiceValues['enable_igst'] == 0) {
								$igststyle = 'style="display:none"';
							}  ?>
					</div>

					<div class="form-group">
						<label>Delivery Note</label>
						<input type="text" class="form-control" name="deliverynote" id="deliverynote" autocomplete="off" value="<?php echo $invoiceValues['deliverynote']; ?>" placeholder="Delivery Note">
					</div>

					<div class="form-group">
					<label>Invoice Date</label>
						<input type="text" class="form-control" name="order_date" id="order_date" placeholder="Invoiced Date(d/m/yyyy)" autocomplete="off" required value="<?php echo $invoiceValues['order_date']; ?>">
					</div>

		      		</div>
		      	</div>
		      	<div class="row">
		      		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		      			<table class="table table-bordered table-hover" id="invoiceItem">
							<tr>
							<th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
							<th width="25%">Item Name</th>
							<th width="15%">HSN/SAC</th>
							<th width="5%">Quantity</th>
							<th width="15%">Price</th>
							<th width="10%" class="cgst_text <?php echo $csgstvisible;?>" <?php echo $csgststyle;?>>CGST</th>
							<th width="10%" class="sgst_text <?php echo $csgstvisible;?>" <?php echo $csgststyle;?>>SGST</th>
							<th width="10%" class="igst_text <?php echo $igstvisible;?>" <?php echo $igststyle;?>>IGST</th>
							<th width="15%">Total</th>
							</tr>
							<?php
$count = 0;
foreach ($invoiceItems as $invoiceItem) {
    $count++;
    ?>
							<tr>
								<td><input class="itemRow" type="checkbox"></td>

								<td><textarea class="form-control" rows="6" class="col-md-12"  name="productName[]" id="productName_<?php echo $count; ?>" placeholder="Product Name" required><?php echo $invoiceItem["item_name"]; ?></textarea></td>
								<td><input type="text" value="<?php echo $invoiceItem["item_code"]; ?>" name="productCode[]" id="productCode_<?php echo $count; ?>" class="form-control" autocomplete="off"></td>
								<td><input type="number" value="<?php echo $invoiceItem["order_item_quantity"]; ?>" name="quantity[]" id="quantity_<?php echo $count; ?>" class="form-control quantity" autocomplete="off"></td>
								<td><input type="number" value="<?php echo $invoiceItem["order_item_price"]; ?>" name="price[]" id="price_<?php echo $count; ?>" class="form-control price" autocomplete="off"></td>
								<td class="cgst_text" <?php echo $csgststyle;?>><input type="number" name="cgst[]" value="<?php echo $invoiceItem["order_item_cgst"]; ?>" id="cgst_<?php echo $count; ?>" class="form-control price cgst_input " autocomplete="off"></td>
								<td class="sgst_text" <?php echo $csgststyle;?>><input type="number" name="sgst[]" value="<?php echo $invoiceItem["order_item_sgst"]; ?>" id="sgst_<?php echo $count; ?>" class="form-control price sgst_input " autocomplete="off"></td>
								<td class="igst_text" <?php echo $igststyle;?>><input type="number" name="igst[]" value="<?php echo $invoiceItem["order_item_igst"]; ?>" id="igst_<?php echo $count; ?>" class="form-control price igst_input" autocomplete="off"></td>
								<td><input type="number" value="<?php echo $invoiceItem["order_item_final_amount"]; ?>" name="total[]" id="total_<?php echo $count; ?>" class="form-control total" autocomplete="off" readonly></td>

								<input type="hidden" value="<?php echo $invoiceItem['order_item_id']; ?>" class="form-control" name="itemId[]">
							</tr>
							<?php }?>
						</table>
		      		</div>
		      	</div>
		      	<div class="row">
		      		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
		      			<button class="btn btn-danger delete" id="removeRows" type="button">- Delete</button>
		      			<button class="btn btn-success" id="addRows" type="button">+ Add More</button>
		      		</div>
		      	</div>

				<div class="row">
				<div class="col-xs-12">
				<div class="form-group">
					<label>Declaration</label>
					<textarea class="form-control" rows="6" class="col-md-12"  name="declaration" id="declaration" placeholder="Declaration" required><?php echo $invoiceValues['declaration']; ?></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
				<div class="form-group">
					<label>Terms and Conditions</label>
					<input type="checkbox" name="termstrue" value="1" id="termstrue" <?php echo ($invoiceValues['termstrue'] == 1) ? 'checked' : ''; ?> />
					<textarea class="form-control" rows="3" class="col-md-12"  name="terms" id="terms"  <?php echo ($invoiceValues['termstrue'] != 1) ? 'style="display:none"' : ''; ?> placeholder="Terms and conditions" maxlength="180"><?php echo $invoiceValues['terms']; ?></textarea>
					<small iclass="form-text text-muted">Not more than 160 characters.</small><small id="showtotalterms"></small>
					</div>
				</div>
			</div>

		      	<div class="row">
		      		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
						<br>
						<div class="form-group">
							<input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
							<input type="hidden" value="<?php echo $invoiceValues['order_id']; ?>" class="form-control" name="invoiceId" id="invoiceId">
			      			<input data-loading-text="Updating Invoice..." type="submit" name="invoice_btn" value="Save Invoice" class="btn btn-success submit_btn invoice-save-btm">
							<input data-loading-text="Updating Invoice..." type="submit" name="invoice_btn_stay" value="Save Invoice and Stay" class="btn btn-success submit_btn invoice-save-btm">
							<input data-loading-text="Send SMS" type="submit" name="send_sms" value="Send SMS" class="btn btn-danger submit_btn invoice-save-btm">
							<a href="print_invoice.php?invoice_id=<?php echo $invoiceValues['order_id']; ?>" target="_blank"><button type="button" class="btn btn-primary btn-sm">Print PDF</button></a>
			      		</div>

		      		</div>
		      		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
						<span class="form-inline">
							<div class="form-group">
								<label>Total: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">&#x20B9;</div>
									<input value="<?php echo $invoiceValues['order_total_after_tax']; ?>" type="number" class="form-control" name="totalAftertax" id="totalAftertax" placeholder="Total">
								</div>
							</div>
							<div class="form-group invisible">
								<label>Amount Paid: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">&#x20B9;</div>
									<input value="<?php echo $invoiceValues['order_amount_paid']; ?>" type="number" class="form-control" name="amountPaid" id="amountPaid" placeholder="Amount Paid">
								</div>
							</div>
							<div class="form-group invisible">
								<label>Amount Due: &nbsp;</label>
								<div class="input-group">
									<div class="input-group-addon currency">&#x20B9;</div>
									<input value="<?php echo $invoiceValues['order_total_amount_due']; ?>" type="number" class="form-control" name="amountDue" id="amountDue" placeholder="Amount Due">
								</div>
							</div>
						</span>
					</div>
		      	</div>
		      	<div class="clearfix"></div>
	      	</div>
		</form>
    </div>
</div>
<?php include 'footer.php';?>

