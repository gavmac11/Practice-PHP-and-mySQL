<?php session_start();
	include "functions.php"; 
 
	echo "Your order has been placed.";
 
	$con = connectToDB();
	$orderID = uniqid();
	$date = getdate();
	$orderDate = $date['mon'] . "/" . $date['mday'] . "/" . $date['year'];
	$tmpUniqueID = $_SESSION['uniqueID'];
	$tmpItems = $_SESSION['cart'];
	$totalCost =0;
	foreach ($_SESSION['cart'] as $k => $item)
	{
		$sql = "SELECT price FROM Inventory WHERE name = '$item';";
		$result = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($result);
		$totalCost = $totalCost + intval($row['price']);
	}
	
	$tmpCart = "";
	foreach ($tmpItems as $item)
	{
		$tmpCart = $tmpCart . $item . "|";
	}
	$tmpCart = rtrim($tmpCart,"|");
	
	$sql = "INSERT INTO Orders VALUES('$orderID', '$orderDate', '$tmpUniqueID','$tmpCart','$totalCost','pending');";
	if (!mysqli_query($con,$sql)) 
		{
			echo "Errormessage: ", mysqli_error($con);
		}
	else
	{
		echo "Successfull insert.\n";
		unset($_SESSION['cart']);
		echo '<META HTTP-EQUIV="Refresh" Content="2;URL=index.php">';
	}
	
	
?>

