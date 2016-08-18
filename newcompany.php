<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
		<link href='css/materialize.min.css' rel='stylesheet' type='text/css' media='screen, projection'>
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
	
	
	<?php
	session_start();
	if(!isset($_SESSION['admin_name']))
	{
		header("Location: index.php");
	}
	
	
	
	?>
	<body>
		<div class="navbar-fixed">
		<nav id="nav">
			<div class="nav-wrapper">
				<a href="index.php" id="logo" class="brand-logo">The Game Of Shares</a>
			
			<ul id="nav-mobile" class="right">
				<?php 
				
				if(isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name']))
				{
					echo "<li><a href='newcompany.php'> Add a new company</a></li>";
					echo "<li><a href='edit.php?changeprice='>Change the stock prices</a></li>";
					echo "<li><a href='logout.php'>Logout</a></li>";
				}
				else
					echo "<li><a href='login.php'>User Login</a></li>";
				?>
			</ul>
		
			</div>
		</nav>
		</div>
		<div class="container" id="container">
	<h3>Enter Company Details</h3><br>
		<form name="register" action="admin.php" onsubmit="return validateForm()" method="post" role="form">
			Name: <input type="text" id="company_name" name="company_name" required><br>
			Stock Abreviation: <input type="text" id="abbr" name="abbr" required><br>
			Description: <textarea id="description" name="description" required></textarea><br>
			Initial Number Of Shares: <input type="number" id="shares" name="shares" required><br>
			Initial Price Of A Share: <input type="number" id="price" name="price" required><br>
			<input type="submit" value="Add The Company" class="btn">
		</form>
	</div>
		<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>

	</body>
	
</html>
