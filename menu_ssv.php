<ul class="nav navbar-nav">
<li class="dropdown">
	<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Invoice/Proforma/Quotation
	<span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="ssv_list.php">All List</a></li>
		<li><a href="create_ssv.php">Create New Item</a></li>				  
	</ul>
</li>
<?php 
if($_SESSION['userid']) { ?>
	<li class="dropdown">
		<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Logged in <?php echo $_SESSION['user']; ?>
		<span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li><a href="action.php?action=logout">Logout</a></li>		  
		</ul>
	</li>
<?php } ?>
</ul>
<br /><br /><br /><br />