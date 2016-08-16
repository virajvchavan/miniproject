<?php
ob_start();
session_start();

$servername = "127.0.0.1";
$username_db = "root";
$password = "";
$dbname = "miniproject";
// Create connection
$conn = new mysqli($servername, $username_db, $password, $dbname);
// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

function isLoggedIn()
{
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
	{
		$user_name = $_SESSION['user_name'];
		$user_id = $_SESSION['user_id'];
		return true;
	}
	else
	{
		$user_name = "";
		$user_id = "";
		return false;
	}
}
if(isLoggedIn())
{
	$user_name = $_SESSION['user_name'];
	$user_id = $_SESSION['user_id'];
}
else
{
	$user_name = "";
	$user_id = "";
}
$query_user_balance = "SELECT balance FROM users WHERE id='$user_id'";
	if($run_balance = mysqli_query($conn, $query_user_balance))
	{
		$array_balance = mysqli_fetch_assoc($run_balance);
		$user_balance = $array_balance['balance'];
	}

?> 
