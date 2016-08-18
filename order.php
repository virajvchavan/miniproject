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
	include "conn.inc.php";
	
	if(!isLoggedIn())
	{
		if(isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name']))
		{
			header("Location: admin.php");
		}
		else
			header("Location: login.php");
	}
	?>
	<body>
		
		<body>
			<div class="navbar-fixed">
		<nav id="nav">
			<div class="nav-wrapper">
				<a href="index.php" id="logo" class="brand-logo">The Game Of Shares</a>
			
			<ul id="nav-mobile" class="right">
				<li><a href='transactions.php'>Your Orders</a></li>
				<li><a href='logout.php'>Logout(<?php echo $user_name; ?>)</a></li>
				
			</ul>
		
			</div>
		</nav>
			</div>

			<div class="container">
<?php


//inserting an order into the 'selling_order' or 'buying_order' table
//for a transaction to happen, the two tables need to be cross checkedd with each other, and if a match is found, the transaction takes place
if(isset($_POST['order']) && isset($_POST['company']) && isset($_POST['shares']) & isset($_POST['type']) && isset($_POST['limit_price']))
{
	$order_type = $_POST['order'];
	$company_id = $_POST['company'];
	$no_of_shares = $_POST['shares'];
	$type = $_POST['type'];
	
	//fetch market value of stock if order type is market else the value is speceified by user
	if($type == "market")
	{
		$query_market_price = "SELECT stock_price FROM companies WHERE id = '$company_id'";
		if($run_price = mysqli_query($conn, $query_market_price))
		{
			$array_price = mysqli_fetch_assoc($run_price);
			$price = $array_price['stock_price'];
		}
	}
	else
	{
		$price = $_POST['limit_price'];
	}
	
	
	
	if($order_type == "buy")
	{
		//check if user has enough points in his account
		if(($no_of_shares*$price > $user_balance))
		{
			echo "<script>alert('You don not have enough points to place this order.');</script>";
			header("refresh:0,url=index.php");

		}
		$table_name = "buying_orders";
		
	}
	else
	{
		//need to check if user actually has that many shares to sell
		
		$table_name = "selling_orders";
	}
	
	
	echo "<br>";
	
	$query_place_order = "INSERT INTO $table_name(company_id, user_id, no_of_shares, price) VALUES('$company_id','$user_id','$no_of_shares','$price')";
	if(mysqli_query($conn, $query_place_order))
		{
			//success;
			echo "<div class='card'><div class='card-content'> Your order is placed. Visit <a href='transactions.php'>My Orders</a> to know the staus.</div></div>";
		}
	else
		echo "Errror";
}
			
else
{
	if(isset($_SESSION['admin_name']) && !empty($_SESSION['admin_name']))
	{
		header("Location: admin.php");
	}
	else
		header("Location: login.php");
}
			
?>
		
		</div>
		<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>

	</body>
	
</html>
