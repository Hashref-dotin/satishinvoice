<?php
ob_start();
session_start();
include 'header.php';
include 'Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
$limit = 15;  
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  

?>
<title><?php echo WEBPAGE_TITLE; ?></title>
<script src="js/invoice.js"></script>
<link href="css/style.css" rel="stylesheet">
<?php include 'container.php';?>
	<div class="container">
	  <h2 class="title">Invoice System</h2>
	  <?php include 'menu.php';?>
    <form action="invoice_list.php" method="POST">
    <select name="datatype" id="datatype" onchange="this.form.submit()">
              <option value="">Show All</option>
							<option value="Invoice" <?php if ($_POST['datatype'] == 'Invoice') {echo "selected";}?> >Invoice</option>
							<option value="Proforma"  <?php if ($_POST['datatype'] == 'Proforma') {echo "selected";}?> >Proforma</option>
							<option value="Quotation"  <?php if ($_POST['datatype'] == 'Quotation') {echo "selected";}?> >Quotation</option>
						</select>
      <table id="data-table" class="table table-condensed table-striped">
        <thead>
          <tr>
            <th>Sl No.</th>
            <th>Created Date</th>
            <th>Customer Name</th>
            <th>Invoiced Date</th>
            <th>Invoice Total</th>
            <th>Type</th>
            <th>Print</th>
            <th>Download</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <?php
$datatype = (isset($_POST['datatype'])) ? $_POST['datatype'] : '';
$invoiceList = $invoice->getInvoiceList($datatype, '', false, $start_from, $limit);
$sl = 1;
foreach ($invoiceList as $invoiceDetails) {
    $invoiceDate = date("d/M/Y, H:i:s", strtotime($invoiceDetails["date_created"]));
    echo '
              <tr>
                <td>' . $sl . '</td>
                <td>' . $invoiceDate . '</td>
                <td>' . $invoiceDetails["order_receiver_name"] . '</td>
                <td>' . $invoiceDetails["order_date"] . '</td>
                <td>' . $invoiceDetails["order_total_after_tax"] . '</td>
                <td>' . $invoiceDetails["datatype"] . '</td>
                <td><a href="print_invoice.php?invoice_id=' . $invoiceDetails["order_id"] . '" target="_blank" title="Print Invoice"><span class="glyphicon glyphicon-print"></span></a></td>
                <td><a href="print_invoice.php?invoice_id=' . $invoiceDetails["order_id"] . '&download=1" target="_blank" title="Print Invoice"><span class="glyphicon glyphicon-download"></span></a></td>
                <td><a href="edit_invoice.php?update_id=' . $invoiceDetails["order_id"] . '" target="_blank" title="Edit Invoice"><span class="glyphicon glyphicon-edit"></span></a></td>
                <td><a href="#" id="' . $invoiceDetails["order_id"] . '" class="deleteInvoice"  title="Delete Invoice"><span class="glyphicon glyphicon-remove"></span></a></td>
              </tr>
            ';
    $sl++;
}
?></table>

<?php  
$totalcounts = $invoice->getInvoiceList($datatype, '', true);
$totaldata = current($totalcounts); 
$total_records = $totaldata['count']; 
$total_pages = ceil($total_records / $limit); 
if($total_pages>1)
{ 
  
$page_no = $page;
$previous_page = $page_no - 1;
  $next_page = $page_no + 1;
  $total_no_of_pages = $total_pages;
?>



<ul class="pagination">
	<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>
    
	<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no > 1){ echo "href='?page=$previous_page'"; } ?>>Previous</a>
	</li>
       
    <?php 
	if ($total_no_of_pages <= 10){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 10){
		
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?page=$second_last'>$second_last</a></li>";
		echo "<li><a href='?page=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}

	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?page=1'>1</a></li>";
		echo "<li><a href='?page=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?page=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?page=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page=1'>1</a></li>";
		echo "<li><a href='?page=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no < $total_no_of_pages) { echo "href='?page=$next_page'"; } ?>>Next</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li><a href='?page=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
		} ?>
</ul>

<?php 
}
?>


<?php include 'footer.php';?>