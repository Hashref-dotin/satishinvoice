<ul class="nav navbar-nav">
<li class="dropdown">
	<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Invoice/Proforma/Quotation/PO
	<span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="super_list.php">All List</a></li>
		<li><a href="create_super.php">Create New Item</a></li>				  
	</ul>
</li>
<?php 
if($_SESSION['userid']) { ?>
	<?php include 'common_menu.php'; ?>
<?php } ?>
</ul>
<br /><br /><br /><br />