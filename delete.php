

<?php
include "conn.inc.php";

if(isset($_GET['sid']) && !empty($_GET['sid']))
{
    $id = $_GET['sid'];
    
    $query_delete = "DELETE FROM selling_orders WHERE id=$id";
    if(mysqli_query($conn, $query_delete))
    {
            echo "<script>alert(The order is cancelled.)</script>";
			header("refresh:0,url=transactions.php");
    }
}

if(isset($_GET['bid']) && !empty($_GET['bid']))
{
    $id = $_GET['bid'];
    
    $query_delete = "DELETE FROM buying_orders WHERE id=$id";
    if(mysqli_query($conn, $query_delete))
    {
            echo "<script>alert(The order is cancelled.)</script>";
			header("refresh:0,url=transactions.php");
    }
}

if(isset($_GET['cid']) && !empty($_GET['cid']))
{
    $id = $_GET['cid'];
    
    $query_delete = "DELETE FROM companies WHERE id=$id";
    if(mysqli_query($conn, $query_delete))
    {
            echo "<script>alert(The company is deleted.)</script>";
			header("refresh:0,url=transactions.php");
    }
}



?>