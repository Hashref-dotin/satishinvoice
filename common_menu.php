<li class="dropdown">
		<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Logged in <?php echo $_SESSION['user']; ?>
		<span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li><a href="settings.php">SMS Settings</a></li>		  
			<li><a href="action.php?action=logout">Logout</a></li>		  
		</ul>
	</li>