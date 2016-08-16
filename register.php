<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Register</title>
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
		<h2>Register</h2>
		<form name="register" action="index.php" onsubmit="return validateForm()" method="post" role="form">
			Name: <input type="text" id="name_reg" name="name_reg" required><br>
			Email: <input type="email" id="email_reg" name="email_reg" required><br>
			Password: <input type="password" id="pass1_reg" name="pass1_reg" required><br>
			Retype Password: <input type="password" id="pass2_reg" name="pass2_reg" required><br>
			<input type="submit" value="Register">
		</form>
		
	
	</body>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
</html>