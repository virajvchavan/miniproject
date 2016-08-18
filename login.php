<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Login | The Game of Shares</title>
		<link href='css/gfonts.css' rel='stylesheet' type='text/css'>
		<link href='css/materialize.min.css' rel='stylesheet' type='text/css' media='screen, projection'>
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<link href="css/login.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
	

<?php

	include "conn.inc.php";
	if(isLoggedIn())
	{
	
		header("Location: index.php");
	}
?>
		
	<body>
		<div class="navbar-fixed">
		<nav>
			<div class="nav-wrapper">
				<a href="index.php" id="logo" class="brand-logo">The Game Of Shares</a>
			
			<ul id="nav-mobile" class="right ">
				<li><a href='register.php'>Register</a></li>
				<li><a href='admin.php'> Admin Login</a></li>
			</ul>
		
			</div>
		</nav>
		</div>
		<div class="container">
			<div id="main"  class="card col s12 teal darken-2 ">
				
				<form action='login.php' method='post'> 
					<div class="row">
						<div class="input-field col s6">	
							<input class="validate" placeholder="example@email.com" type='email' id="email" name='email_login' required>
							<label for="email">Email</label>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s6">	
							<input class="validate"  type='password' id="password" name='password_login' required>
							<label for="password">Password</label>
						</div>
						
					</div>
					<input type='submit' class="btn" value='Login'>
				</form>
			
		</div>
			
		
		

<?php
//log in the user if email and pass match with database
if(isset($_POST['email_login']) && isset($_POST['password_login']))
{
	$email_login = $_POST['email_login'];
	
	$password_login = md5(filter_var(($_POST['password_login']), FILTER_SANITIZE_STRING));
	
	$email_login = filter_var($email_login, FILTER_SANITIZE_EMAIL);
	
	$query_login = "SELECT id, name from users WHERE username = '$email_login' AND password = '$password_login'";
	if($run = mysqli_query($conn, $query_login))
	{
		
		if(mysqli_num_rows($run) == 1)
		{
			$array = mysqli_fetch_assoc($run);
			$_SESSION['user_id'] = $array['id'];
			$_SESSION['user_name'] = $array['name'];

			header("refresh:0,index.php");
		}
		else
			echo "Invalid Username/Password combination.";
			
		
	}
}
?>
		
		
		</div>
		<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>

	</body>
	
</html>
