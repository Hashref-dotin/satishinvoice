<?php
ob_start();
session_start();
include 'header.php';
include 'Prowin.php';
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
	  <?php include 'menu_prowin.php';?>
    <form action="prowin_list.php" method="POST">
    
            <table id="data-table" class="table table-condensed table-striped" data-tablename="<?php echo $invoice->invoiceOrderTable;?>">
        <thead>
          <tr>
            <th>Invoice Number</th>
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
        <tfoot>
          <tr>
            <th>Invoice Number</th>
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
        </tfoot>
       </table>
<?php include 'footer.php';?>