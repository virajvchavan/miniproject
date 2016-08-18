<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Register</title>
		<link href='css/materialize.min.css' rel='stylesheet' type='text/css' media='screen, projection'>
		<link href="css/custom.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
	
	<script>
	function validateForm()
		{
			var pass1 = document.forms["register"]["pass1"].value;
			var pass2 = document.forms["register"]["pass2"].value;

			if(pass1 != pass2)
				{
					alert("Passwords do not match.");
					return false;
				}
		}


</script>
	
	<body>
		<div class="navbar-fixed">
		<nav id="nav">
			<div class="nav-wrapper">
				<a href="index.php" id="logo" class="brand-logo">The Game Of Shares</a>
			
			<ul id="nav-mobile" class="right">
				<li><a href='login.php'>Login</a></li>
			</ul>
		
			</div>
		</nav>
		</div>
		<div class="container" id="container">
		<h3>Register</h3>
		<form name="register" action="index.php" onsubmit="return validateForm()" method="post" role="form">
			Name: <input type="text" id="name_reg" name="name_reg" required><br>
			Email: <input type="email" id="email_reg" name="email_reg" required><br>
			Password: <input type="password" id="pass1_reg" name="pass1_reg" required><br>
			Retype Password: <input type="password" id="pass2_reg" name="pass2_reg" required><br>
			<input type="submit" class="btn" value="Register">
		</form>
		
	
<script type="text/javascript" src="js/jquery-3.1.0.min.js"></script>
		<script type="text/javascript" src="js/materialize.js"></script>
</div>
	</body>
	
</html>
