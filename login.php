<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>The Game of Shares</title>
    </head>
	

<?php

	include "conn.inc.php";
	if(isLoggedIn())
	{
	
		header("Location: index.php");
	}
?>
		
	<body>
		<a href='register.php'>Register</a>&nbsp;&nbsp;&nbsp;
		
		<a href='admin.php'> Admin Login</a>
		<br><br>
		<form action='login.php' method='post'> 
			Email: <input type='email' name='email_login' required><br>Password:<input type='password' name='password_login' required><br><input type='submit' value='Login'>
		</form>
		<br><br>
		
	</body>
</html>

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
