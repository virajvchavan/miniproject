<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
    </head>
	
	
	<?php
	session_start();
	if(!isset($_SESSION['admin_name']))
	{
		header("Location: index.php");
	}
	
	
	
	?>
	<body>
	<h2>Enter Company Details</h2>
		<form name="register" action="admin.php" onsubmit="return validateForm()" method="post" role="form">
			Name: <input type="text" id="company_name" name="company_name" required><br>
			Stock Abreviation: <input type="text" id="abbr" name="abbr" required><br>
			Description: <textarea id="description" name="description" required></textarea><br>
			Initial Number Of Shares: <input type="number" id="shares" name="shares" required><br>
			Initial Price Of A Share: <input type="number" id="price" name="price" required><br>
			<input type="submit" value="Add The Company">
		</form>
	
	</body>
	
	
	
	
</html>