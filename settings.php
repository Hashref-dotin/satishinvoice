<?php
session_start();
include 'header.php';
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
$errorMsg = '';
$successmsg = '';

$getsettings = current($invoice->getsettings());
$smsmessage = $getsettings['smsmessage'];
if(isset($_POST['update_sms']))
{
	$mobilesnum = array();
	foreach($_POST['smsusers'] as $mobile)
	{
		if(!empty($mobile) && (!is_numeric($mobile) || strlen($mobile) != 10))
		{
			$errorMsg =  'Error with some mobile number';
		}
		else if(!empty($mobile))
		{
			$mobilesnum[] = $mobile;

		}
	}

	if(empty($errorMsg))
	{
		$mobilelist =  implode(",", $mobilesnum);
		$invoice->updateSMS($mobilelist, $_POST['smsmessage']);
		$successmsg = 'Updated successfully';
	}

}


/*$get_details = "Visitor in office.Name: $name Mobile: $number Email: $email Purpose: $purpose";
        $arr2 = str_split($get_details, 30);
        $first = urlencode($arr2[0]);
        $second =urlencode($arr2[1]);
        $third =urlencode($arr2[2]);
        $fourth = urlencode($arr2[3]);
        $fifth = urlencode($arr2[4]);
		$sixth =urlencode($arr2[5]);
		
$url = "http://203.129.203.243/blank/sms/user/urlsmstemp.php?username=satishengg&pass=@enggsatish1&senderid=SATISH&dest_mobileno=8892461021&tempid=51741&F1=hrljeqlwjeqw";

$curl_handle = curl_init($url);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($curl_handle);

if($output) {
	echo "Yes";
} else {
	echo "No";
}
curl_close($curl_handle)
*/
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
					<?php include 'menu.php';?>
				</div>
			</div>
			<?php
if (!empty($errorMsg) && isset($_POST)) {
    ?>
		<div class="alert alert-danger" role="alert">
  <?php echo $errorMsg; ?>
</div>
					<?php
}
?>
			<?php
if (!empty($successmsg) && isset($_POST)) {
    ?>
		<div class="alert alert-success" role="alert">
  <?php echo $successmsg; ?>
</div>
					<?php
}
else
{
	$mobilesnum = explode(",",$getsettings['smsusers']);

}
?>
			<div class="row">
			<div class="form-group">
					<label>Mobile Number 1</label>
							<input type="text" class="form-control" name="smsusers[]" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $mobilesnum[0];?>" maxlength="10">
					</div>
					<div class="form-group">
						<label>Mobile Number 2</label>
							<input type="text" class="form-control" name="smsusers[]" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $mobilesnum[1];?>" maxlength="10">
					</div>
					<div class="form-group">
						<label>Mobile Number 3</label>
							<input type="text" class="form-control" name="smsusers[]" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $mobilesnum[2];?>" maxlength="10">
					</div>
					<div class="form-group">
						<label>Mobile Number 4</label>
							<input type="text" class="form-control" name="smsusers[]" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $mobilesnum[3];?>" maxlength="10">
					</div>
					<div class="form-group">
						<label>Mobile Number 5</label>
							<input type="text" class="form-control" name="smsusers[]" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $mobilesnum[4];?>" maxlength="10">
					</div>
					<div class="form-group">
						<label>Mobile Number 6</label>
							<input type="text" class="form-control" name="smsusers[]" placeholder="Mobile Number" autocomplete="off" required value="<?php echo $mobilesnum[5];?>" maxlength="10">
					</div>

				
					<div class="form-group">
						<label>Delivery Message</label>
						<input type="text" class="form-control" name="smsmessage" id="smsmessage" readonly autocomplete="off" value="<?php echo $smsmessage;?>" >
						<small iclass="form-text text-muted">Not more than 120 characters.</small><small id="showtotalterms"></small>
					</div>

					<div class="row">
						<div class="form-group">
			      			<input data-loading-text="Update SMS Settings" type="submit" name="update_sms" value="Update SMS Settings" class="btn btn-success submit_btn invoice-save-btm">
			      		</div>
					  </div>		  
			</div>
			<div class="clearfix"></div>
		</div>
	</form>
</div>
</div>
<?php include 'footer.php';?>