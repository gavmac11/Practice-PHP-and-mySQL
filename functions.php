<script>
function addToShopCart(itemID)
{
	alert(itemID);
	//alert("<?php addToCart(itemID) ?>");
}
</script>

<?php
session_start();

function connectToDB()
{
	$ini_array = parse_ini_file("credentials.ini");
	$serverName = $ini_array["servername"];
	$userName = $ini_array["username"];
	$password = $ini_array["password"];
	//echo $password;
	$con = mysqli_connect($serverName, $userName, $password, 'project');

	if (!$con)
	{
		die("Cannot connect: " . mysqli_connect_error());
	}
	else
	{
		//echo "Connection successful.\n\n";
	}
	return $con;
}

function closeDB($con)
{
	mysqli_close($con);
}

function checkUsername($con,$page,$firstName="",$lastName="",$username="",$password="",$usertype="")
{	
	$uName  = $_SESSION['userName'] = $_POST['userName'];
	$uPassword = $_POST['password'];
	
	if ($page == "register") //register
	{
		$query=mysqli_query($con,"SELECT * FROM Credentials WHERE username = '$uName';");
		$count=mysqli_num_rows($query);
		$row=mysqli_fetch_array($query);

		if ($count==1)
		{
		   echo "Username already taken. You will be redirected to try again.<br/>\n";
		   echo '<META HTTP-EQUIV="Refresh" Content="2;URL=register.php">';
		}
		else //good to go
		{
			addUserToDB($firstName, $lastName, $username, $password, $usertype);
			echo '<META HTTP-EQUIV="Refresh" Content="2;URL=index.php">';
		}
		$sql = "SELECT * FROM Credentials NATURAL JOIN User WHERE username = '$uName'";
		$query = mysqli_query($con,$sql);
		$count = mysqli_num_rows($query);
		$row = mysqli_fetch_array($query);
		
		$_SESSION['uniqueID'] = $row['uniqueID'];
		$_SESSION['firstName'] = $row['firstName'];
		$_SESSION['lastName'] = $row['lastName'];
		$_SESSION['usertype'] = $row['usertype'];
	}
	else
	{
		$query=mysqli_query($con,"SELECT * FROM Credentials WHERE username = '$uName' AND password = '$uPassword';");
		$count=mysqli_num_rows($query);
		$row=mysqli_fetch_array($query);

		if ($count==1)
		{
			echo "<br/>Welcome $uName.<br/>\n";
			echo '<META HTTP-EQUIV="Refresh" Content="2;URL=index.php">';
		}
		else
		{
			echo "Username or password was incorrect. You will be redirected to try again.";
			echo '<META HTTP-EQUIV="Refresh" Content="2;URL=login.php">';
		}
		
		$sql = "SELECT * FROM Credentials NATURAL JOIN User WHERE username = '$uName'";
		$query = mysqli_query($con,$sql);
		$count = mysqli_num_rows($query);
		$row = mysqli_fetch_array($query);
		
		$_SESSION['uniqueID'] = $row['uniqueID'];
		$_SESSION['firstName'] = $row['firstName'];
		$_SESSION['lastName'] = $row['lastName'];
		$_SESSION['usertype'] = $row['usertype'];
	}
    
}

function shoppingCart()
{
	$con = connectToDB();
	echo "<br>";
	echo "<table border=1>
		<tr>
		<th>Item ID</th>
		<th>Name</th>
		<th>Price</th>
		</tr><br>";
	
	$i=0;
	foreach ($_SESSION['cart'] as $id => $name)
	{
		$sql = "SELECT price FROM Inventory WHERE itemID = '$id';";
		$query = mysqli_query($con,$sql);
		$row = mysqli_fetch_array($query);
		
		echo "<tr><td>";
		//$tmpID = $record["itemID"];
		//$tmpName = $record["name"];
		echo $id . "</td>";
		echo "<td>" . $name . "</td>";
		echo "<td>" . $row["price"] . "</td>";
		echo "</tr>";
		$i++;
	}
	
	echo "</table>";
}

function totalRev($con)
{
	$sql = "SELECT SUM(totalCost) FROM Orders;";
	$query = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($query);
	return $row['SUM(totalCost)'];
}

function totalItemSold($con)
{
	$total=0;
	$sql = "SELECT COUNT(*) FROM Orders;";
	$query = mysqli_query($con,$sql);
	$row = mysqli_fetch_array($query);
	$count = intval($row['COUNT(*)']);
	
	$sql = "SELECT items FROM Orders;";
	$query = mysqli_query($con,$sql);
	for ($i=0; $i < $count;$i++)
	{
		$row = mysqli_fetch_array($query);
		$currentItems = explode("|",$row['items']);
		$total = $total + count($currentItems);
	}
	return $total;
}

function promotion($con,$sale)
{
	$sql = "UPDATE Inventory SET price= price * $sale;";
	if (!mysqli_query($con,$sql)) 
			{
				echo "Errormessage: ", mysqli_error($con);
			}
		else
		{
			echo "Successfull promotion.\n";
		}
	echo '<META HTTP-EQUIV="Refresh" Content="2;URL=index.php">';
}

function updateInventoryStaff($con, $tmpID, $tmpQuantity)
{
	$sql = "UPDATE Inventory SET quantity= $tmpQuantity WHERE itemID = $tmpID;";
	if (!mysqli_query($con,$sql)) 
			{
				echo "Errormessage: ", mysqli_error($con);
			}
		else
		{
			echo "Successfull insert.\n";
		}
	echo '<META HTTP-EQUIV="Refresh" Content="2;URL=index.php">';
}

function updateOrders($con, $tmpOrder, $tmpStatus)
{
	$sql = "UPDATE Orders SET status = '$tmpStatus' WHERE orderID = '$tmpOrder';";//echo $sql;exit;
	if (!mysqli_query($con,$sql)) 
			{
				echo "Errormessage: ", mysqli_error($con);
			}
		else
		{
			echo "Successfull change.\n";
		}
	echo '<META HTTP-EQUIV="Refresh" Content="2;URL=index.php">';
}

function addToCart($itemID)
{
	echo $itemID;
}

function showInventory($con)
{
	echo "<br>";
	$sql = 'SELECT * FROM Inventory;';
	$result = mysqli_query($con, $sql);
	echo "<table border=1>
		<tr>
		<th>Item ID</th>
		<th>Name</th>
		<th>Description</th>
        <th>Price $</th>
		<th>Add To Cart</th>
		</tr><br>";
	while($record = mysqli_fetch_array($result))
	{
		echo "<tr><td>";
		$tmpID = $record["itemID"];
		$tmpName = $record["name"];
		echo $record["itemID"] . "</td>";
		echo "<td>" . $record["name"] . "</td>";
		echo "<td>" . $record["description"] . "</td>";
        echo "<td>" . $record["price"] . "</td>";
		echo "<td>" . "<a href='addToCart.php?itemID={$tmpID}&name={$tmpName}'>Add To Cart</a>" . "</td>";
		echo "</tr>";
	}
	echo "</table>";
}
    
function showInventoryStaff($con)
{
    echo "<br>";
    $sql = 'SELECT * FROM Inventory;';
    $result = mysqli_query($con, $sql);
    echo "<table border=1>
    <tr>
    <th>Item ID</th>
    <th>Name</th>
    <th>Description</th>
    <th>Price $</th>
    <th>Quantity In Stock</th>
    <th>Add To Cart</th>
    </tr><br>";
    while($record = mysqli_fetch_array($result))
    {
        echo "<tr><td>";
        $tmpID = $record["itemID"];
        $tmpName = $record["name"];
        echo $record["itemID"] . "</td>";
        echo "<td>" . $record["name"] . "</td>";
        echo "<td>" . $record["description"] . "</td>";
        echo "<td>" . $record["price"] . "</td>";
        echo "<td>" . $record["quantity"] . "</td>";
        echo "<td>" . "<a href='addToCart.php?itemID={$tmpID}&name={$tmpName}'>Add To Cart</a>" . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
    
function showOrders($con)
{
    echo "<br>";
    $sql = "SELECT * FROM Orders NATURAL JOIN User WHERE uniqueID = " . $_SESSION['uniqueID'] . ";";
    $result = mysqli_query($con, $sql);
    $sql2 = "SELECT items FROM Orders NATURAL JOIN User WHERE uniqueID = " . $_SESSION['uniqueID'] . ";";
    $result2 = mysqli_query($con, $sql2);
    echo "<table border=1>
    <tr>
    <th>Order ID</th>
    <th>Order Date</th>
    <th>Items</th>
    <th>Total Cost</th>
    <th>Status</th>
    </tr><br>";
    
    $record2 = mysqli_fetch_array($result2);
    $items = explode("|",$record2["items"]);
    $i=0;$iWhole = "";
    while($i < sizeof($items))
    {
		$iWhole = $iWhole . " " . $items[$i];
		
		$i++;
	}
	
    while($record = mysqli_fetch_array($result))
    {
        echo "<tr><td>";
        echo $record["orderID"] . "</td>";
        echo "<td>" . $record["orderDate"] . "</td>";
        echo "<td>" . $record["items"] ."</td>";
        echo "<td>" . $record["totalCost"] ."</td>";
        echo "<td>" . $record["status"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
    
function showOrdersStaff($con)
{
    echo "<br>";
    $sql = "SELECT * FROM Orders NATURAL JOIN User";
    $result = mysqli_query($con, $sql);
    $sql2 = "SELECT items FROM Orders NATURAL JOIN User";
    $result2 = mysqli_query($con, $sql2);
    echo "<table border=1>
    <tr>
    <th>Order ID</th>
    <th>Recipient</th>
    <th>Order Date</th>
    <th>Items</th>
    <th>Total Cost</th>
    <th>Status</th>
    </tr><br>";
    
    $record2 = mysqli_fetch_array($result2);
    $items = explode("|",$record2["items"]);
    $i=0;$iWhole = "";
    while($i < sizeof($items))
    {
        $iWhole = $iWhole . " " . $items[$i];
        
        $i++;
    }
    
    while($record = mysqli_fetch_array($result))
    {
        echo "<tr><td>";
        echo $record["orderID"] . "</td>";
        echo "<td>" . $record["firstName"] . " " . $record["lastName"] . "</td>";
        echo "<td>" . $record["orderDate"] . "</td>";
        echo "<td>" . $record["items"] ."</td>";
        echo "<td>" . $record["totalCost"] ."</td>";
        echo "<td>" . $record["status"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
    
function showStaff($con)
{
    echo "<br>";
    $sql = 'SELECT * FROM Staff;';
    $result = mysqli_query($con, $sql);
    echo "<table border=1>
    <tr>
    <th>Staff ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Position</th>
    </tr><br>";
    while($record = mysqli_fetch_array($result))
    {
        echo "<tr><td>";
        echo $record["uniqueID"] . "</td>";
        echo "<td>" . $record["firstName"] . "</td>";
        echo "<td>" . $record["lastName"] . "</td>";
        echo "<td>" . $record["position"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
  
function checkQuantity($con,$tmpOrder)
{
		$sql = "SELECT items FROM Orders WHERE orderID = '$tmpOrder' ;";
		
		$result = mysqli_query($con,$sql);
		$record = mysqli_fetch_array($result);
		$segments = explode("|",$record['items']);
		//print_r($segments);exit;
		$successShip=true;
	
		foreach ($segments as $item)
		{
			$sql = "SELECT quantity FROM Inventory WHERE name = '$item' ;";
		
			if (!mysqli_query($con,$sql)) 
			{
				echo "Errormessage: ", mysqli_error($con);
			}
			
			$record = mysqli_fetch_array($result);
			if (intval($record['quantity']) >= 1)
			{
				$tmpQ = $record['quantity'] - 1;
				$sql = "UPDATE Inventory SET quantity = '$tmpQ'	 WHERE name = '$item'; ";
		
				if (!mysqli_query($con,$sql)) 
				{
					echo "Errormessage: ", mysqli_error($con);
				}
			}
			else
			{
				echo $item . "is out of stock. Order will remain pending.<br/>";
				//~ $rollback = "ROLLBACK TO sp";	
				//~ $successShip=false;
				//~ if (!mysqli_query($con,$rollback)) 
				//~ {
					//~ echo "Errormessage: ", mysqli_error($con);
				//~ }
				break 2;
				
			}
		}
		
		return $successShip;
}
    
function addUserToDB($firstName, $lastName, $username, $password, $usertype)
{
    $con = connectToDB();
    $uniqueID = uniqid();//echo $uniqueID;
        
	$sql = "INSERT INTO User VALUES('$uniqueID', '$firstName', '$lastName','$usertype');";
	
	if (!mysqli_query($con,$sql)) 
		{
			echo "Errormessage: ", mysqli_error($con);
		}
	else
	{
		echo "Successfull insert.\n";
	}
    
    //insert into credentials
    $sql = "INSERT INTO Credentials(uniqueID, username, password) VALUES('$uniqueID', '$username', '$password');";
		
		if (!mysqli_query($con,$sql)) 
			{
				echo "Errormessage: ", mysqli_error($con);
			}
		else
		{
			echo "Successfull insert.\n";
		}
	
    closeDB($con);
}

?>
