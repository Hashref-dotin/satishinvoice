<?php
session_start();
include 'header.php';
include 'Ssv.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
$errorMsg = '';
include 'SMS.php';
if (!empty($_POST['companyName']) && !empty($_POST['mobile']) && !empty($_POST['email'])) {
    if (empty($_POST['order_date']) && !checkDateForm($_POST['order_date'])) {
        $errorMsg = "Invoice date should be valid";
    } else {
        $id = $invoice->saveInvoice($_POST);
        if (!is_numeric($id)) {
            $errorMsg = "Unable to save." . $id;
        } else {
			$sms = new SMS();
            $message = $sms->sendSMS($invoice->invoiceOrderTable, $id);
            header("Location:edit_ssv.php?update_id=" . $id);
        }
    }
} else if (empty($_POST['companyName'])  || empty($_POST['mobile']) || empty($_POST['email'])) {
	$errorMsg = "Input error fields. Check company name/email/mobile fields.";
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
	<form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate="">
		<div class="load-animate animated fadeInUp">
			<div class="row">
				<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
					<h2 class="title">Invoice System</h2>
					<?php include 'menu_ssv.php';?>
				</div>
			</div>
			<input id="currency" type="hidden" value="$">
			<?php
if (!empty($errorMsg) && isset($_POST)) {
    ?>
		<div class="alert alert-danger" role="alert">
  <?php echo $errorMsg; ?>
</div>
					<?php
}
?>
			<div class="row">
				<!--<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
					<h3>From,</h3>
					<?php //echo $_SESSION['user']; ?><br>
					<?php //echo $_SESSION['address']; ?><br>
					<?php //echo $_SESSION['mobile']; ?><br>
					<?php //echo $_SESSION['email']; ?><br>
				</div> -->
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-left">
					<h3>To,</h3>
					<div class="form-group">
					<label>Generate Type</label>
						<select name="datatype" id="datatype">
						<option value="Invoice" <?php if ($_POST['datatype'] == 'Invoice') {echo "selected";}?>>Invoice</option>
						<option value="Proforma" <?php if ($_POST['datatype'] == 'Proforma') {echo "selected";}?>>Proforma</option>
						<option value="Quotation" <?php if ($_POST['datatype'] == 'Quotation') {echo "selected";}?>>Quotation</option>
						<option value="Purchase Order"  <?php if ($_POST['datatype'] == 'Purchase Order') {echo "selected";}?> >Purchase Order</option>
						</select>

					</div>
					<div class="form-group">
					<label>Company Name (or) Purchase Name</label>
						<input type="text" class="form-control" name="companyName" id="companyName" placeholder="Company Name" autocomplete="off" required value="<?php echo $_POST['companyName']; ?>">
					</div>
					<div class="form-group">
					<label>Address</label>
						<textarea class="form-control" rows="3" name="address" id="address" placeholder="Company Address" required value="<?php echo $_POST['address']; ?>"></textarea>
					</div>
					<div class="form-group">
					<label>GSTIN/UIN</label>
						<input type="text" class="form-control" name="gst" id="gst" placeholder="GSTIN/UIN" autocomplete="off" required value="<?php echo $_POST['gst']; ?>">
					</div>
					<div class="form-group">
						<label>Mobile Number</label>
							<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $_POST['mobile']; ?>" maxlength="12">
						</div>
						<div class="form-group">
						<label>Email ID</label>
							<input type="text" class="form-control" name="email" id="email" placeholder="Email Id" autocomplete="off" required value="<?php echo $_POST['email']; ?>">
						</div>
						<div class="form-group">
						<label>State Code</label>
							<input type="text" class="form-control" name="statecode" id="statecode" placeholder="State code(Example: 29, Karnataka)" autocomplete="off" required value="<?php echo $_POST['statecode']; ?>">
						</div>

					<div class="form-check">
							<input type="checkbox" class="form-check-input" name="enable_csgst" id="enable_csgst" autocomplete="off" value="1">
							<label class="form-check-label">Enable SGST/CGST</label>
					</div>
					<div class="form-check">
							<input type="checkbox" class="form-check-input" name="enable_igst" id="enable_igst" autocomplete="off" value="1">
							<label class="form-check-label">Enable IGST</label>
					</div>

					<div class="form-group">
					<label>Reference ID</label>
						<input type="text" class="form-control" name="reference" id="reference" placeholder="Reference ID" autocomplete="off" required value="<?php echo $_POST['reference']; ?>">
					</div>
					<div class="form-group">
					<label>Delivery Note</label>
						<input type="text" class="form-control" name="deliverynote" id="deliverynote" placeholder="Delivery Note" autocomplete="off" required value="<?php echo $_POST['deliverynote']; ?>">
					</div>
					<div class="form-group">
					<label>Invoice Date</label>
						<input type="text" class="form-control" name="order_date" id="order_date" placeholder="Invoiced Date(d/m/yyyy)" autocomplete="off" required value="<?php echo $_POST['order_date']; ?>">
					</div>
					<br/>
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
							<th width="10%" class="cgst_text" style="display:none">CGST</th>
							<th width="10%" class="sgst_text" style="display:none">SGST</th>
							<th width="10%" class="igst_text" style="display:none">IGST</th>
							<th width="15%">Total</th>
						</tr>
						<tr>
							<td><input class="itemRow" type="checkbox"></td>
							<td><textarea class="form-control" rows="6" class="col-md-12"  name="productName[]" id="productName_1" placeholder="Product Name"></textarea></td>
							<td><input type="text" name="productCode[]" id="productCode_1" class="form-control" autocomplete="off"></td>
							<td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" autocomplete="off" value="1"></td>
							<td><input type="number" name="price[]" id="price_1" class="form-control price" autocomplete="off"></td>
							<td class="cgst_text" style="display:none"><input type="number" name="cgst[]" id="cgst_1" class="form-control price cgst_input"autocomplete="off"></td>
							<td class="sgst_text" style="display:none"><input type="number" name="sgst[]" id="sgst_1" class="form-control price sgst_input" autocomplete="off"></td>
							<td class="igst_text" style="display:none"><input type="number" name="igst[]" id="igst_1" class="form-control price igst_input" autocomplete="off"></td>
							<td><input type="number" name="total[]" id="total_1" readonly class="form-control total" autocomplete="off"></td>
						</tr>
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
					<?php $declaration = 'Declaration:
1. Interest @24% will be charged on payment not made with in 15 days.
2. We are not responsible for the loss occur due to theft or fire after sale.
3. All disputes subject to Bangalore Jurisdiction only.
4. Goods once sold will not be taken back.
5. The customer has seen the machine before buying. Any mistakes in machine description and details are reserve.
Satish Engineering (seller) does not give any guarantee or takes any responsibility on used machines, it will be bought as where it is condition.';?>
					<textarea class="form-control" rows="6" class="col-md-12"  name="declaration" id="declaration" placeholder="Declaration" required><?php echo !empty($_POST['declaration']) ? $_POST['declaration'] : $declaration; ?></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
				<div class="form-group">
					<label>Terms and Conditions</label>
					<input type="checkbox" name="termstrue" value="1" id="termstrue"  <?php echo ($_POST['termstrue'] == 1) ? 'checked' : ''; ?>/>
					<?php $terms = '1) GST at 18% extra
					2) Price are exworks S.S.V. Engineering
					3) Machine delivery - After 100% Payment
					';?>
					<textarea class="form-control" rows="3" class="col-md-12"  name="terms" id="terms" placeholder="Terms and conditions" maxlength="160" <?php echo ($_POST['termstrue'] != 1) ? 'style="display:none"' : ''; ?> ><?php echo !empty($_POST['terms']) ? $_POST['terms'] : $terms; ?></textarea>
					<small iclass="form-text text-muted">Not more than 160 characters.</small><small id="showtotalterms"></small>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
					<br>
					<div class="form-group">
						<input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="userId">
						<input data-loading-text="Saving Invoice..." type="submit" name="invoice_btn" value="Save Invoice" class="btn btn-success submit_btn invoice-save-btm">
					</div>

				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
					<span class="form-inline">
						<div class="form-group">
							<label>Total: &nbsp;</label>
							<div class="input-group">
								<div class="input-group-addon currency">&#x20B9;</div>
								<input value="" type="number" class="form-control" name="totalAftertax" id="totalAftertax" placeholder="Total">
							</div>
						</div>
						<div class="form-group invisible">
							<label>Amount Paid: &nbsp;</label>
							<div class="input-group">
								<div class="input-group-addon currency">&#x20B9;</div>
								<input value="" type="number" class="form-control" name="amountPaid" id="amountPaid" placeholder="Amount Paid">
							</div>
						</div>
						<div class="form-group invisible">
							<label>Amount Due: &nbsp;</label>
							<div class="input-group">
								<div class="input-group-addon currency">&#x20B9;</div>
								<input value="" type="number" class="form-control" name="amountDue" id="amountDue" placeholder="Amount Due">
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