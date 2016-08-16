<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
    </head>
	<body>

<?php
include "conn.inc.php";

if(!isset($_SESSION['admin_name']) || empty($_SESSION['admin_name']))
{
	
	echo "<br><hr>Admin Login:<br>
		<form action='admin.php' method='post'> 
			Username: <input type='text' name='username_admin' required><br>Password:<input type='password' name='password_admin' required><input type='submit' value='Login'>
		</form>";
	
}
else
{
	echo "<br><a href='logout.php'>Logout</a>";
	
	echo "<br><a href='newcompany.php'> Add a new company.</a><br><br>";

	echo "<br><a href='edit.php?changeprice='>Change the stock prices</a><br><br>";
	
	
		
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
				
				echo "<br><b>$company_abbr</b> <a href='company.php?id=$company_id'> $company_name</a> &nbsp;&nbsp;&nbsp;&nbsp;Share Price: $company_stock_price&nbsp;&nbsp;&nbsp;&nbsp;Total Shares: $company_no_shares";
										
			}
		}
		else
		{
			echo "<br>No companies exist.<br>";
		}
		
}

} //this block was executed only if admin was logged in

?>
		
	</body>
</html>

<?php
//Adding a new company (That means:
//1. Inerting info into the 'companies' table 
//2. insering the initial stock price into 'stock_prices' table
//3. insrting the total number of shares initiallly offered by company into 'selling_orders' table 
//4. inserting the total number of shares initiallly offered by company into 'shares_distribution' table)
if(isset($_POST['company_name']) && isset($_POST['abbr']) && isset($_POST['description']) && isset($_POST['shares']) && isset($_POST['price']))

{
	
	$ok = true;
	$name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
	$abbr = strtoupper(filter_var($_POST['abbr'], FILTER_SANITIZE_STRING));
	$description = filter_var(($_POST['description']), FILTER_SANITIZE_STRING);
	$initial_no_of_shares = filter_var(($_POST['shares']), FILTER_SANITIZE_NUMBER_INT);
	$initial_price_share = filter_var(($_POST['price']), FILTER_SANITIZE_NUMBER_INT);
	
	//check if same company already registered
	$query_abbr_check = "SELECT abbrivation FROM companies WHERE abbrivation = '$abbr'";
	if($run = mysqli_query($conn, $query_abbr_check))
	{
		if(mysqli_num_rows($run) >= 1)
		{
			echo "Abbrivation already registered.";
			$ok = false;
		}
	}
	
	
	if($ok)
	{
		
		//1. first insert into the 'conpanies' table
		$query_new_company1 = "INSERT INTO companies(name, abbrivation, description, total_shares, stock_price) VALUES('$name', '$abbr', '$description', '$initial_no_of_shares','$initial_price_share')";

		if(mysqli_query($conn, $query_new_company1))
		{
			echo "<script>alert('Added $name.');</script>";
			
			
			header('Location:admin.php');

			//header("refresh:0,url=index.php");
		}
		else
			echo "Error Registering.";
		
		
		//Now get the company id of the company just added
		$query_company_id = "SELECT id FROM companies";
		if($run_company_id = mysqli_query($conn, $query_company_id))
		{
			while($array = mysqli_fetch_assoc($run_company_id))
			{
				$company_id = $array['id'];
			}
		}
		
		
		//2. Now insert into the 'stock_prices' table
		$query_new_company2 = "INSERT INTO stock_prices(company_id, stock_price) VALUES('$company_id','$initial_price_share')";
		if(mysqli_query($conn, $query_new_company2))
		{
			//success
		}
		else
		{
			echo "Error adding stock price";
		}
		
		//3. Now insert into the 'selling_orders' table
//IMP	  user_id kept as -1 because these shares will be sold directly by the company and not by another user
		$query_new_company3 = "INSERT INTO selling_orders(company_id, user_id, price, no_of_shares) VALUES('$company_id','-1','$initial_price_share','$initial_no_of_shares')";
		if(mysqli_query($conn, $query_new_company3))
		{
			//success
		}
		else
		{
			echo "Error adding the selling order";
		}
		
		//4. Now insert into the 'shares_distribution' table
		//set user_id = -1 to indicate that the shares are being hold by the company itself
		$query_new_company4 = "INSERT INTO shares_distribution(user_id, company_id, no_of_shares) VALUES('-1','$company_id','$initial_no_of_shares')"; 
		if(mysqli_query($conn, $query_new_company4))
		{
			//success
		}
		else
		{
			echo "Error adding the shares_distribution table";
		}
	}
	
	
}




//log in the admin if email and pass match with database
if(isset($_POST['username_admin']) && isset($_POST['password_admin']))
{
	$username_admin = $_POST['username_admin'];
	
	$password_admin = filter_var(($_POST['password_admin']), FILTER_SANITIZE_STRING);
	
	$username_admin = filter_var($username_admin, FILTER_SANITIZE_EMAIL);
	
	$query_login = "SELECT username, password from admin WHERE username = '$username_admin' AND password = '$password_admin'";
	if($run = mysqli_query($conn, $query_login))
	{
		
		if(mysqli_num_rows($run) == 1)
		{
			$array = mysqli_fetch_assoc($run);
			$_SESSION['admin_name'] = $array['username'];

			header("refresh:0,admin.php");
		}
		else
			echo "Invalid Username/Password combination.";
			
		
	}
}	



?>