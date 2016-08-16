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
							echo "<br><br>Change the stock prices of any company:";
							while($array = mysqli_fetch_assoc($run_get_company_id))
							{
				
								$company_id_edit_price = $array['id'];

								$company_name = $array['name'];
								$company_abbr = $array['abbrivation'];
								$company_description = $array['description'];
								$company_no_shares = $array['total_shares'];
								$company_stock_price = $array['stock_price'];

								echo "<br> <br><form action='edit.php' method='get' >	
								$company_name($company_abbr): 
								<input type='number' value='$company_stock_price' name='new_stock_price'>
								<input type='number' value='$company_id_edit_price' name='id' hidden>
								<input type='submit' value='Change Price'>
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
		
	</body>
</html>