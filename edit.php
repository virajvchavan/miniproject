<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
		<link href='css/materialize.min.css' rel='stylesheet' type='text/css' media='screen, projection'>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
	<body>
		<?php
		include "conn.inc.php";
		?>
		<nav class="navbar-fixed" id="nav">
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
		<div class="container">
		<?php
		if(!isset($_SESSION['admin_name']) || empty($_SESSION['admin_name']))
		{

			header("Location:index.php");

		}
		
			//form for changing the stock prices for all the companies
			if(isset($_GET['changeprice']))
			{
				
				$query_get_company_id = "SELECT * FROM companies";

				if($run_get_company_id = mysqli_query($conn, $query_get_company_id))
					{
						if(mysqli_num_rows($run_get_company_id) >= 1)
						{
							echo "<br><br><h5>Change the stock prices of any company:</h5><br><br>";
							while($array = mysqli_fetch_assoc($run_get_company_id))
							{
				
								$company_id_edit_price = $array['id'];

								$company_name = $array['name'];
								$company_abbr = $array['abbrivation'];
								$company_description = $array['description'];
								$company_no_shares = $array['total_shares'];
								$company_stock_price = $array['stock_price'];

								echo "<br> <br><form action='edit.php' class='col s12' method='get' >	
								$company_name($company_abbr): 
								<div class='row'>
								<input type='number' value='$company_stock_price' class='col s8' name='new_stock_price'>
								<input type='number' value='$company_id_edit_price' name='id' hidden>
								<input type='submit' class='btn' value='Change Price' class='col s4'>
								</div>
								</form> ";

							}
						}
				}
			}
		
		//change the values of the stock in the database
		//ie insert into stock_prices table
		if(isset($_GET['new_stock_price']) && isset($_GET['id']) && !empty($_GET['new_stock_price']) && !empty($_GET['id']))
		{
			$new_stock_price = $_GET['new_stock_price'];
			$company_id_new_price = $_GET['id'];
			
			$query_change_price = "INSERT INTO stock_prices(company_id, stock_price) VALUES('$company_id_new_price','$new_stock_price');";
			
			if(mysqli_query($conn, $query_change_price))
			{
				mysqli_query($conn, "UPDATE `companies` SET `stock_price` = '$new_stock_price' WHERE `id` = $company_id_new_price");
				header("Location:admin.php");
			}
		}
		
		?>
		
		<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>
</div>
	</body>
	
</html>
