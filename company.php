<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
		<link href='css/materialize.min.css' rel='stylesheet' type='text/css' media='screen, projection'>
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
	<body>
		<div class="navbar-fixed">
		<nav id="nav">
			<div class="nav-wrapper">
				<a href="index.php" id="logo" class="brand-logo">The Game Of Shares</a>
			
			<ul id="nav-mobile" class="right">
				<?php 
				
				include "conn.inc.php";
				if(isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name']))
				{
					echo "<li><a href='newcompany.php'> Add a new company</a></li>";
					echo "<li><a href='edit.php?changeprice='>Change the stock prices</a></li>";
					echo "<li><a href='logout.php'>Logout</a></li>";
				}
				else if(isLoggedIn())
				{
					
					echo "<li><a href='transactions.php'>My Orders</a></li>";
					echo "<li><a href='logout.php'>Logout($user_name)</a></li>";
				}
				else
				{
					echo "<li><a href='login.php'>User Login</a></li>";
					
				}
					
				?>
			</ul>
		
			</div>
		</nav>
		</div>
		
		<div class="container" id="container">
			
<?php
		

		
if(isset($_GET['id']) && !empty($_GET['id']))
{
	$company_id = $_GET['id'];
	$query_company_data = "SELECT * FROM companies WHERE id = '$company_id'";
	if($run_company_data = mysqli_query($conn, $query_company_data))
	{
		while($array_company_data = mysqli_fetch_assoc($run_company_data))
		{
			$name = $array_company_data['name'];
			$description = $array_company_data['description'];
			$abbr = $array_company_data['abbrivation'];
			$total_shares = $array_company_data['total_shares'];
			$price = $array_company_data['stock_price'];
			
			echo "<h2>$name</h2><h4>$abbr</h4><hr><br>$description<br><br>Total number of shares in the market: $total_shares<br><br>Price of a share: $price points<br>";
			
		}
	}
}
		
		
		
?>
	
			</div>
	<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>

	</body>
	
</html>
