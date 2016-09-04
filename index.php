<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
		<link href='css/gfonts.css' rel='stylesheet' type='text/css'>
		<link href='css/materialize.min.css' rel='stylesheet' type='text/css' media='screen, projection'>
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<link href="css/index.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
	<script>
	
	</script>
	
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
		<div class="container" id="container">
	
		<div class="row">
	
	<?php
	
	
	echo "<div class='card-panel col s3 teal' id='balance'><div class='card-content white-text'> Balance: $user_balance Points</div></div>";
	
	
//showing the shares owned by the user
	echo "<div  class='card col s8 blue-grey darken-1' id='shares'>";
	echo "<div class='card-content white-text'> <span class='card-title'>Your shares:</span><br>";
	
			
	$query_get_company_id = "SELECT DISTINCT company_id FROM shares_distribution WHERE user_id = $user_id";
	if($run_get_company_id = mysqli_query($conn, $query_get_company_id))
	{
		if(mysqli_num_rows($run_get_company_id) >= 1)
		{
			echo "<table >
							<thead>
								<tr>
									<th data-field='abbr'>Company</th>
									<th data-field='name'>Quantity</th>
									<th data-field='price'>Current Price</th>
								</tr>
							</thead>	<tbody>";
			
			while($array = mysqli_fetch_assoc($run_get_company_id))
			{
				
				$company_id = $array['company_id'];
				
				$query_get_users_shares = "SELECT companies.name, companies.stock_price, SUM(shares_distribution.no_of_shares) AS sum FROM companies, shares_distribution WHERE companies.id = shares_distribution.company_id AND user_id = $user_id AND company_id = $company_id";
				if($run_get_users_shares = mysqli_query($conn, $query_get_users_shares))
				{
					if(mysqli_num_rows($run_get_users_shares) >= 1)
					{
						while($array_shares = mysqli_fetch_assoc($run_get_users_shares))
						{

							$share_name = $array_shares['name'];
							$share_price = $array_shares['stock_price'];
							$share_number = $array_shares['sum'];
							
							
							echo "<tr>
									<td><a href='company.php?id=$company_id'>$share_name</a></td>
									<td> $share_number</a></td> <td>$share_price</td>
								</tr>
							";
						}
					}
				}

			
			}
			echo "</tbody>
						</table>";
		}
		else
		{
			echo "You have no shares of any company.";
		}
	}
	echo "</div></div>";
	
echo "</div>";
	
echo "<div class='row'>";
			
	?>
	<br>
			<div class="card col s7" id="order_card">
				<div class="card-content"><h4>Place an order: </h4><hr><br><br>
	<form class="col s12" action="order.php" method="post">
		<input type="radio" name="order" id="order1" value="buy" checked required>
		<label for="order1">Buy</label>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="order" id="order2" value="sell" required>
		<label for="order2">Sell</label><br><br>
		
	
		<div class="input-field col s12">
		<select name='company' required>
			<option value="" disabled selected>Chose a company</option>
			<?php
			
			//get company data
			$query_get_company_id = "SELECT * FROM companies";
		
			if($run_get_company_id = mysqli_query($conn, $query_get_company_id))
				{
					if(mysqli_num_rows($run_get_company_id) >= 1)
					{
						while($array = mysqli_fetch_assoc($run_get_company_id))
						{

							$company_id = $array['id'];

							$company_name = $array['name'];
							$company_abbr = $array['abbrivation'];
							$company_stock_price = $array['stock_price'];

							echo "<option value='$company_id'><b>$company_abbr</b><span class='badge'> $company_stock_price points</span></option>";
								
						}
					}
			}
			
			?>			
		</select>
		<br><br>	
		</div>
		<br>
        
		<div class="input-field">
            Number of shares:
			<input class="validate" placeholder="Enter the number of shares" type="number" name="shares" min="1" required>
		</div>
		
		<br>
		<br>
		<div class="row">
			
			<input class="col s4" type="radio" onclick="deactivate()" name="type" id='type1' checked value="market">
			<label for="type1">Market Price</label>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<br><br>
			<input class="col s4" type="radio" onclick="activate()" name="type" id="type2" value="limit">
		  
			<label for="type2">Limit Price  <input class="col s4" id="limit_price" name="limit_price"></label>

			
		</div>	
		<br><br>
		<input type="submit" class="btn" value="Place Order">
		<br><br>
	</form>
					
					</div>
			</div>

<?php
//showing all the companies data	
$query_get_company_id = "SELECT * FROM companies";
		
if($run_get_company_id = mysqli_query($conn, $query_get_company_id))
	{
		if(mysqli_num_rows($run_get_company_id) >= 1)
		{
			echo "<div class='card col s4' id='price_card'><table class='striped'>
							<thead>
								<tr>
									<th data-field='abbr'>Abbrevation</th>
									<th data-field='name'>Name</th>
									<th data-field='price'>Price</th>
								</tr>
							</thead>	<tbody>";
			while($array = mysqli_fetch_assoc($run_get_company_id))
			{
				
				$company_id = $array['id'];
				$company_name = $array['name'];
				$company_abbr = $array['abbrivation'];
				$company_description = $array['description'];
				$company_no_shares = $array['total_shares'];
				$company_stock_price = $array['stock_price'];

				//Now show all the data related to current $company_id
				//take the latest value of the stock from 'stock_values' table: the max value of id from stock_prices where company_id = $company_id
				
				
							
						echo "<tr>
									<td><b>$company_abbr</b></td>
									<td><a href='company.php?id=$company_id'> $company_name</a></td> <td>$company_stock_price</td>
								</tr>
							";
										
			}
			echo "</tbody>
						</table></div>";
		}
		else
		{
			echo "<br>No companies exist.<br>";
		}
		
}
	?>
			
			</div>
		
	

	
	
	
<?php

	
//registering the user (Inserting into the 'users' table )	
//A trigger has been written in database that sets the default balance of the user as 500pts in the 'balances_of_users' table
if(isset($_POST['name_reg']) && isset($_POST['email_reg']) && isset($_POST['pass1_reg']) && isset($_POST['pass2_reg']))

{
	
	$ok = true;
	$name = filter_var($_POST['name_reg'], FILTER_SANITIZE_STRING);
	$email = filter_var($_POST['email_reg'], FILTER_SANITIZE_EMAIL);
	$pass1 = md5(filter_var(($_POST['pass1_reg']), FILTER_SANITIZE_STRING));
	$pass2 = md5(filter_var(($_POST['pass2_reg']), FILTER_SANITIZE_STRING));
	
	
	$query_email_check = "SELECT username FROM users WHERE username = '$email'";
	if($run = mysqli_query($conn, $query_email_check))
	{
		if(mysqli_num_rows($run) >= 1)
		{
			echo "<script>alert('Email already registered.');</script>";
			$ok = false;
		}
	}
	
	
	if($pass1 !== $pass2)
	{
		echo "Password don't match";
		$ok = false;
	}
	if(strlen($pass1)<6)//no use!
	{
		echo "Password must be more than 6 characters";
		$ok = false;
	}
	if($ok)
	{
		$query_resgister = "INSERT INTO users(name, username, password) VALUES('$name', '$email', '$pass1')";

		if(mysqli_query($conn, $query_resgister))
		{
			echo "<script>alert('Registerd Successfully.');</script>";
			
			
			
			//now log the user in if registration is successful
			$query_login = "SELECT id, name from users WHERE username = '$email' AND password = '$pass1'";
			if($run = mysqli_query($conn, $query_login))
			{

				if(mysqli_num_rows($run) == 1)
				{
					$array = mysqli_fetch_assoc($run);
					$_SESSION['user_id'] = $array['id'];
					$_SESSION['user_name'] = $array['name'];
				}
				else
					echo "Invalid Username/Password combination.";

		
			}
			
			header('Location:index.php');

			//header("refresh:0,url=index.php");
		}
		else
			echo "Error Registering.";
	}
	
}
	
	
	
	
	
?>
	
<?php


	
?>
            
			</div>
        
		<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>

        <script>
            $(document).ready(function() 
            {
                $('select').material_select();
            });
            
        </script>
	</body>
	
</html>

	