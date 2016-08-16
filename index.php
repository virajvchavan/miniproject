<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
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
	else
	{
		echo "<br><a href='logout.php'>Logout</a>($user_name)<br><hr><br>";
	}
	
	
	
	
	echo "Your Balance: $user_balance Points<br><br>";
	
	
//showing all the companies data
		

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
				$company_description = $array['description'];
				$company_no_shares = $array['total_shares'];
				$company_stock_price = $array['stock_price'];

				//Now show all the data related to current $company_id
				//take the latest value of the stock from 'stock_values' table: the max value of id from stock_prices where company_id = $company_id
				
				echo "<br><b>$company_abbr</b> (<a href='company.php?id=$company_id'> $company_name</a>) &nbsp;&nbsp;&nbsp;&nbsp;Share Price: $company_stock_price";
										
			}
		}
		else
		{
			echo "<br>No companies exist.<br>";
		}
		
}
	?>
	
	<br><h4>Place an order: </h4>
	<form action="order.php" method="post">
		<input type="radio" name="order" value="buy" checked required>Buy
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" name="order" value="female" required>Sell<br><br>
	Select Stock:
		<select name='company' required>
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

							echo "<option value='$company_id'><b>$company_abbr</b> ($company_stock_price points)</option>";
								
						}
					}
			}
			
			?>			
		</select>
		
		<br><br>Number of Shares: <input type="number" name="shares" required>
		<br><br><input type="radio" required onclick="deactivate()" name="type" id='type' value="market" checked>Market Price
		 &nbsp;&nbsp;&nbsp;&nbsp;
		<input type="radio" onclick="activate()" name="type" value="limit">Limit &nbsp;&nbsp;&nbsp;
		<input type="number" id="limit_price" name="limit_price"><br><br>
		<input type="submit" value="Place Order">
	</form>
	

	
	
	
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
			echo "Email already registered.";
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
	
</html>
	
	