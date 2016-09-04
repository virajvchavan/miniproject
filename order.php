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


//take the data from the form submitted for placing an order
if(isset($_POST['order']) && isset($_POST['company']) && isset($_POST['shares']) & isset($_POST['type']) && isset($_POST['limit_price']))
{
	
	
	
	$order_type = $_POST['order'];
	$company_id = $_POST['company'];
	$order_quantity = $_POST['shares'];
	$order_quantity_temp = $order_quantity;
	$type = $_POST['type'];		
	$completely_executed = false;
	$limit_price = $_POST['limit_price'];
	
	//find the market price of share
	$query_market_price = "SELECT stock_price FROM companies WHERE id = $company_id";
	if($run_market_price = mysqli_query($conn, $query_market_price))
	{
		while($array_market_price = mysqli_fetch_assoc($run_market_price))
		{
			$market_price = $array_market_price['stock_price'];
		}
	}	
	
	
	//for a buy order
	if($order_type == "buy")
	{
		//check if user has enough points in his account
		if($type == "limit" && ($order_quantity*$limit_price > $user_balance))
		{
			echo "<script>alert('You don not have enough points to place this order.');</script>";
			header("refresh:0,url=index.php");
			exit();
		}	
		elseif(($order_quantity*$market_price > $user_balance))
		{
			echo "<script>alert('You don not have enough points to place this order.');</script>";
			header("refresh:0,url=index.php");
			exit();

		}
		
		//if limit, directly add entry in buying_orders table
		if($type == "limit")
		{
			if(($limit_price > $market_price))
			{
				echo "<script>alert('Limit Price should be lesser than market price for Buy Orders.');</script>";
				header("refresh:0,url=index.php");
				exit();
			}
			
			//add an entry in buying_orders

			$query_insert_in_buy = "INSERT INTO buying_orders(company_id, user_id, no_of_shares, price) VALUES($company_id, $user_id, $order_quantity_temp, $limit_price)";
			if(mysqli_query($conn, $query_insert_in_buy))
			{
				echo "Limit order added in buying_orders table<br>";
			}
			else
				echo "Failed adding in buying_orders for limit<br>";
			
		}
		//if market, start finding matches, execute partially / fully at the best price available
		elseif($type == "market")
		
		{
		
			//the selling_orders table needs to be sorted with lowest price and lowest time first before finding a match
			$query_cmp_selling_table = "SELECT * FROM `selling_orders` WHERE company_id = $company_id ORDER BY price ASC";
			if($run_cmp_selling_table = mysqli_query($conn, $query_cmp_selling_table))
			{
				//if no entries in selling_orders table
				if(mysqli_num_rows($run_cmp_selling_table) < 1)
				{
					//add an entry in buying_orders

					$query_insert_in_buy = "INSERT INTO buying_orders(company_id, user_id, no_of_shares, price) VALUES($company_id, $user_id, $order_quantity_temp, $market_price)";
					if(mysqli_query($conn, $query_insert_in_buy))
					{
						echo "Inerted into buying_orders<br>";
					}
				}
				else
				{
					while($array_sells = mysqli_fetch_assoc($run_cmp_selling_table))
					{
						$sell_order_id = $array_sells['id'];
						$seller_id = $array_sells['user_id'];
						$seller_price = $array_sells['price'];
						$seller_quantity = $array_sells['no_of_shares'];
						
						
						if($user_id == $seller_id)
						{
							continue;
						}

							if($order_quantity_temp > $seller_quantity)
							{
								//decrease the order_quantity occordingly
								$order_quantity_temp = $order_quantity_temp - $seller_quantity;

								//delete this row
								$query_delete_row = "DELETE FROM selling_orders WHERE id=$sell_order_id";
								if(mysqli_query($conn, $query_delete_row))
								{
									echo "deleted a row<br>";
								}

							}
							if($order_quantity_temp < $seller_quantity)
							{
								//update the quantity in this row
								$seller_quantity = $seller_quantity - $order_quantity_temp;


								$query_update_row = "UPDATE `selling_orders` SET `no_of_shares`= '$seller_quantity' WHERE id = $sell_order_id";
								if(mysqli_query($conn, $query_update_row))
								{
									echo "updated shares quantity for seller<br>";
								}

								$completely_executed = true;	

							}
							if($order_quantity_temp == $seller_quantity)
							{

								//delete this row
								$query_delete_row = "DELETE FROM selling_orders WHERE id=$sell_order_id";
								if(mysqli_query($conn, $query_delete_row))
								{
									echo "Deleted row<br>";
								}
								$completely_executed = true;	
							}

							//insert into transaction table
							$query_transacation = "INSERT INTO transactions(company_id, seller_id, buyer_id, no_of_shares, price) VALUES('$company_id','$seller_id','$user_id','$order_quantity_temp','$seller_price')";
							if(mysqli_query($conn, $query_transacation))
							{
								//success
								echo "Added into transactions<br>";
							}


							//for new buyer balance
							$user_balance = $user_balance - $order_quantity_temp*$seller_price;

							//update balance for the buyer(logged in user)
							$query_update_balance1 = "UPDATE users SET balance = $user_balance WHERE id = $user_id";

							if(mysqli_query($conn, $query_update_balance1))
							{
								echo "Updated balance for buyer<br>";
							}
							else
								echo "Error updating buyer balance in users table";



							//update balance for the seller if seller_id != -1
							if($seller_id != -1)
							{
								$query_update_balance2 = "UPDATE users SET balance = balance + $order_quantity_temp*$seller_price WHERE id = $seller_id";

								if(mysqli_query($conn, $query_update_balance2))
								{
									echo "updaed balance for seller<br>";
								}
								else
									echo "Error updating seller balance in users table<br>";


								//for new seller balance
								//first fetch current balance for seller_id
								$query_fetch_seller_balance  = "SELECT balance FROM users WHERE id = $seller_id";
								if($run_seller_balance = mysqli_query($conn, $query_fetch_seller_balance))
								{
									while($array_seller_balance= mysqli_fetch_assoc($run_seller_balance))
									{
										$seller_balance = $array_seller_balance['balance'];
									}
								}	
								$seller_balance = $seller_balance + $order_quantity_temp*$seller_price;


								$query_update_balances_both = "INSERT INTO balances_of_users(user_id, balance) VALUES($user_id, $user_balance), ($seller_id, $seller_balance)";
							}
							else
							{
								$query_update_balances_both = "INSERT INTO balances_of_users(user_id, balance) VALUES($user_id, $user_balance)";
							}


							//add entries in balances_of_users table for changes in user balances
							if(mysqli_query($conn, $query_update_balances_both))
							{
								echo "Updated balances of both<br>";
							}
							else
							{
								echo "Failed adding entries in balances_of_users table<br>";
							}



							//update shares_distribution table
							$query_insert_shares_d = "INSERT INTO shares_distribution(user_id, company_id, no_of_shares) VALUES($user_id, $company_id, $order_quantity_temp), ($seller_id, $company_id, -$order_quantity_temp)";

							if(mysqli_query($conn, $query_insert_shares_d))
							{
								echo "Updated shares distibu table<br>";
							}
							else
								echo "Error inserting into shares_d table<br>";



							//set the market price of the share to the price the transaction occurred
							$market_price = $seller_price;


							
							if($completely_executed)
							{
								echo "Completely executed.<br>";
								exit();
							}


					}
					//if order is partially executed, insert the order into the buying_orders table
					if(!$completely_executed)
					{

						//add an entry in buying_orders

						$query_insert_in_buy = "INSERT INTO buying_orders(company_id, user_id, no_of_shares, price) VALUES($company_id, $user_id, $order_quantity_temp, $market_price)";
						if(mysqli_query($conn, $query_insert_in_buy))
						{
							echo "Partial execution. Added entry into buying_orders<br>";
						}
					}
				}

			}
		}
		
	}

	//for a sell order
	elseif($order_type == "sell")
	{
		//first need to check if user actually has that many shares to sell
		$query_check_shares = "SELECT SUM(no_of_shares) FROM `shares_distribution` WHERE user_id = $user_id AND company_id = $company_id";
		if($run_check_shares = mysqli_query($conn, $query_check_shares))
		{
			while($array_check_shares = mysqli_fetch_assoc($run_check_shares))
			{
				$quant_shares = $array_check_shares['SUM(no_of_shares)'];
			}
		}
		else
			echo "Err";
		if($quant_shares < $order_quantity)
		{
			echo "<script>alert('You don not have enough shares of this company to place this order.');</script>";
			header("refresh:0,url=index.php");
			exit();
		}
		
		
		//if limit, directly add entry in selling_orders table
		if($type == "limit")
		{
			if(($limit_price < $market_price))
			{
				echo "<script>alert('Limit Price should be greater than market price for Buy Orders.');</script>";
				header("refresh:0,url=index.php");
				exit();
			}
			
			//add an entry in selling_orders

			$query_insert_in_sell = "INSERT INTO selling_orders(company_id, user_id, no_of_shares, price) VALUES($company_id, $user_id, $order_quantity_temp, $limit_price)";
			if(mysqli_query($conn, $query_insert_in_sell))
			{
				echo "Limit order added in selling_orders table<br>";
			}
			else
				echo "Failed adding in selling_orders for limit<br>";
			
		}
		
		//if market, start finding matches, execute partially / fully at the best price available
		elseif($type == "market")
		
		{
		
			//the buying_orders table needs to be sorted with highest price and lowest time first before finding a match
			$query_cmp_buying_table = "SELECT * FROM `buying_orders` WHERE company_id = $company_id ORDER BY price DESC";
			if($run_cmp_buying_table = mysqli_query($conn, $query_cmp_buying_table))
			{
				//if no entries in buying_orders table
				if(mysqli_num_rows($run_cmp_buying_table) < 1)
				{
					//add an entry in selling_orders

					$query_insert_in_sell = "INSERT INTO selling_orders(company_id, user_id, no_of_shares, price) VALUES($company_id, $user_id, $order_quantity_temp, $market_price)";
					if(mysqli_query($conn, $query_insert_in_sell))
					{
						echo "Market order added in selling_orders table<br>";
					}
					else
						echo "Failed adding in selling_orders for market<br>";
				}
				else
				{
					while($array_buys = mysqli_fetch_assoc($run_cmp_buying_table))
					{
						$buy_order_id = $array_buys['id'];
						$buyer_id = $array_buys['user_id'];
						$buyer_price = $array_buys['price'];
						$buyer_quantity = $array_buys['no_of_shares'];
						
						
						if($user_id == $buyer_id)
						{
							continue;
						}

							if($order_quantity_temp > $buyer_quantity)
							{
								//decrease the order_quantity occordingly
								$order_quantity_temp = $order_quantity_temp - $buyer_quantity;

								//delete this row
								$query_delete_row = "DELETE FROM buying_orders WHERE id=$buy_order_id";
								if(mysqli_query($conn, $query_delete_row))
								{
									echo "Deleted row from buying_orders.<br>";
								}

							}
							if($order_quantity_temp < $buyer_quantity)
							{
								//update the quantity in this row
								$buyer_quantity = $buyer_quantity - $order_quantity_temp;


								$query_update_row = "UPDATE `buying_orders` SET `no_of_shares`= '$buyer_quantity' WHERE id = $buy_order_id";
								if(mysqli_query($conn, $query_update_row))
								{
									echo "Updated shares quantity for a row<br>";
								}	
								else
									echo "Failes a";

								$completely_executed = true;	

							}
							if($order_quantity_temp == $buyer_quantity)
							{

								//delete this row
								$query_delete_row = "DELETE FROM buying_orders WHERE id=$buy_order_id";
								if(mysqli_query($conn, $query_delete_row))
								{
									echo "Deleted row from buying_orders<br>";
								}
								else
									echo "Failed nb<br>";
								$completely_executed = true;	
							}

							//insert into transaction table
							$query_transacation = "INSERT INTO transactions(company_id, seller_id, buyer_id, no_of_shares, price) VALUES('$company_id','$user_id','$buyer_id','$order_quantity_temp','$buyer_price')";
							if(mysqli_query($conn, $query_transacation))
							{
								//success
								echo "Inerted into transactions<br>";
								
							}
						else
							echo "Ruyiyi<br>";
						


							//for new seller balance(logged in user)
							$user_balance = $user_balance + $order_quantity_temp*$buyer_price;

							//update balance for the seller(logged in user)
							$query_update_balance1 = "UPDATE users SET balance = $user_balance WHERE id = $user_id";

							if(mysqli_query($conn, $query_update_balance1))
							{
								echo "Balance updated for seller<br>";
							}
							else
								echo "Error updating seller balance in users table<br>";



							//update balance for the buyer
							
								$query_update_balance2 = "UPDATE users SET balance = balance - $order_quantity_temp*$buyer_price WHERE id = $buyer_id";

								if(mysqli_query($conn, $query_update_balance2))
								{
									echo "Balancec updated for buyer<br>";
								}
								else
									echo "Error updating buyer balance in users table<br>";


								//for new buyer balance
								//first fetch current balance for buyer_id
								$query_fetch_buyer_balance  = "SELECT balance FROM users WHERE id = $buyer_id";
								if($run_buyer_balance = mysqli_query($conn, $query_fetch_buyer_balance))
								{
									while($array_buyer_balance= mysqli_fetch_assoc($run_buyer_balance))
									{
										$buyer_balance = $array_buyer_balance['balance'];
									}
								}
						else
							echo "gas";
								$buyer_balance = $buyer_balance - $order_quantity_temp*$buyer_price;


								$query_update_balances_both = "INSERT INTO balances_of_users(user_id, balance) VALUES($user_id, $user_balance), ($buyer_id, $buyer_balance)";
							
							//add entries in balances_of_users table for changes in user balances
							if(mysqli_query($conn, $query_update_balances_both))
							{
								echo "Balance updated for both<br>";
							}
							else
							{
								echo "Failed adding entries in balances_of_users table<br>";
							}



							//update shares_distribution table
							$query_insert_shares_d = "INSERT INTO shares_distribution(user_id, company_id, no_of_shares) VALUES($user_id, $company_id, -$order_quantity_temp), ($buyer_id, $company_id, $order_quantity_temp)";

							if(mysqli_query($conn, $query_insert_shares_d))
							{
								echo "Shares distribution table updated<br>";
							}
							else
								echo "Error inserting into shares_d table<br>";


							//set the market price of the share to the price the transaction occurred
							$market_price = $buyer_price;


							
							if($completely_executed)
							{
								echo "Completely executed.<br>";
								exit;
							}


					}
					//if order is partially executed, insert the order into the buying_orders table
					if(!$completely_executed)
					{

						//add an entry in selling_orders

						$query_insert_in_sell = "INSERT INTO selling_orders(company_id, user_id, no_of_shares, price) VALUES($company_id, $user_id, $order_quantity_temp, $market_price)";
						if(mysqli_query($conn, $query_insert_in_sell))
						{
							echo "Added partial entry in selling_orders<br>";
						}
						else
							echo "Error insert in sell for market<br>";
					}
				}

			}
		}
		
		
	}
	
	
	//change the price of the share in companies table
	$query_update_price = "UPDATE companies SET stock_price = $market_price WHERE id = $company_id";
	
	
	//insert into stock_prices table
	$query_insert_price = "INSERT INTO stock_prices(company_id, stock_price) VALUES($company_id, $market_price)";
	
	if(mysqli_query($conn, $query_update_price)) 
	{
		echo "Changed the price of share 1<br>";
	}
	else
		echo "Failed to change the price of share 1<br>";
	
    if(mysqli_query($conn, $query_insert_price))
    {
         echo "Changed the price of share 2<br>";
    }
    else
        echo "Failed to change the price of share 2<br>";
    
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
