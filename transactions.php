<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Transactions | The Game Of Shares</title>
		<link href='css/materialize.min.css' rel='stylesheet' type='text/css' media='screen, projection'>
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        
           <script>
			function deleteOrder()
				{
					
					var ask = confirm('Sure to delete?');
					if(ask != true)
					{
						return false;
					}
				}
				
		
    </script>
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
		<div class="navbar-fixed">
		<nav id="nav">
			<div class="nav-wrapper">
				<a href="index.php" id="logo" class="brand-logo">The Game Of Shares</a>
			
			<ul id="nav-mobile" class="right ">
				<li class="active"><a href='transactions.php'>Your Orders</a></li>
				<li><a href='logout.php'>Logout(<?php echo $user_name; ?>)</a></li>
				
			</ul>
		
			</div>
		</nav>
		</div>
		<div class="container" id="container">
	
		<h3>Order Book</h3><br>
			<div class="card">
				<div class="card-content">
			<table class="striped">
				<thead>
					<tr>
						<th>Time</th>
						<th>Buy/Sell</th>
						<th>Company</th>
						<th>Quantity</th>
						<th>Price/share</th>
						<th>Status</th>
					</tr>
				</thead>
				
				<tbody>	
					
					<?php
					
					$query_order_book_sell = "SELECT selling_orders.id, selling_orders.price, selling_orders.no_of_shares, selling_orders.time, companies.abbrivation FROM  selling_orders, companies WHERE selling_orders.company_id = companies.id AND selling_orders.user_id = $user_id ORDER BY selling_orders.id DESC";
					
					if($run_sells = mysqli_query($conn, $query_order_book_sell))
					{
						if(mysqli_num_rows($run_sells) >= 1)
						{
							while($array_sells = mysqli_fetch_assoc($run_sells))
							{
								$time = $array_sells['time'];
								$price = $array_sells['price'];
								$quantity = $array_sells['no_of_shares'];
								$abbr = $array_sells['abbrivation'];
                                $order_id = $array_sells['id'];
								
								echo "<tr>
										<td>$time</td>
										<td>Sell</td>
										<td>$abbr</td>
										<td>$quantity</td>
										<td>$price</td>
										<td><span id='yellow'>Pending</span></td>
                                        <td>
                                        <form action='delete.php?sid=$order_id' onsubmit='return deleteOrder()' method='post'>
							             <input type='submit' value='Cancel' id='red'>
						                </form></td>
									  </tr>";
							}
						}
						
					}
					
					
					$query_order_book_buy = "SELECT buying_orders.id, buying_orders.price, buying_orders.no_of_shares, buying_orders.time, companies.abbrivation FROM  buying_orders, companies WHERE buying_orders.company_id = companies.id AND buying_orders.user_id = $user_id ORDER BY buying_orders.id DESC";
					
					if($run_buys = mysqli_query($conn, $query_order_book_buy))
					{
						if(mysqli_num_rows($run_buys) >= 1)
						{
							while($array_sells = mysqli_fetch_assoc($run_buys))
							{
								$time = $array_sells['time'];
								$price = $array_sells['price'];
								$quantity = $array_sells['no_of_shares'];
								$abbr = $array_sells['abbrivation'];
                                $order_id = $array_sells['id'];
								
								echo "<tr>
										<td>$time</td>
										<td>Buy</td>
										<td>$abbr</td>
										<td>$quantity</td>
										<td>$price</td>
										<td><span id='yellow'>Pending</span></td>
                                        <td>
                                        <form action='delete.php?bid=$order_id' onsubmit='return deleteOrder()' method='post'>
							             <input type='submit' value='Cancel' id='red'>
						                </form>
                                        </td>
									  </tr>";
							}
						}
					
					}
					
					
					
					
					?>
				</tbody>
			</table>
			</div>
				</div>
			
			<br><br><br>
			<h3>Trade Book</h3>
			<div class="card">
			<div class="card-content">
			<table class="striped">
				<thead>
					<tr>
						<th>Time</th>
						<th>Buy/Sell</th>
						<th>Company</th>
						<th>Quantity</th>
						<th>Price/share</th>
						<th>Points</th>
						<th>Balance</th>
					</tr>
				</thead>
				
				<tbody>	
					<?php
					
					$query_transactions = "SELECT transactions.time, transactions.no_of_shares, transactions.price, companies.abbrivation, transactions.seller_id, transactions.buyer_id FROM transactions, companies WHERE transactions.company_id = companies.id AND (transactions.buyer_id = $user_id OR transactions.seller_id = $user_id) ";
					if($run_transactions= mysqli_query($conn, $query_transactions))
					{
						if(mysqli_num_rows($run_transactions) >= 1)
						{
							$balance = 500;
							while($array_transactions = mysqli_fetch_assoc($run_transactions))
							{
								$time = $array_transactions['time'];
								$price = $array_transactions['price'];
								$quantity = $array_transactions['no_of_shares'];
								$abbr = $array_transactions['abbrivation'];
								$points = $price*$quantity;
								
								if($array_transactions['seller_id'] == $user_id)
								{
									$user = "Sell";
									$balance = $balance + $points;
								}
								if($array_transactions['buyer_id'] == $user_id)
								{
									$user = "Buy";
									$balance = $balance - $points;
								}
								
								
								echo "<tr>
										<td>$time</td>
										<td>$user</td>
										<td>$abbr</td>
										<td>$quantity</td>
										<td>$price</td>
										<td>";
										if($user == "Buy")
											echo "<span id='red'>-$points</span>";
										elseif($user == "Sell")
											echo "<span id='green'>+$points</span>";
								echo "</td>
										<td>$balance</td>
									  </tr>"; 
										
							}
						}
					
					}
					
					
					
					
					?>
			
				</tbody>
			</table>
				</div>
			</div>
			<br>
		</div>
	<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>
	</body>
</html>

	