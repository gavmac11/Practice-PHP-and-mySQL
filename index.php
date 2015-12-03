<?php session_start(); ?>
<HTML>
	<HEAD>
		<TITLE> Home </TITLE>
		<link rel="stylesheet" type="text/css" href="projectStyle.css">
	</HEAD>

	<BODY>
		<?php include "functions.php" ?>
		<header>
		<H1> Welcome to Adam and Gavin's Online Store !</H1>
		</header>
		<nav>
		<?PHP 
		$con = connectToDB();
		if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";echo"<br/>";}
		else {include "navigation.html";echo"<br/>";}
		
		if (isset($_SESSION['userName'])){echo "Welcome " . $_SESSION['firstName'] . " " . $_SESSION['lastName'] . "<hr>";}
		else {include "login.php";}
		
		if (isset($_SESSION['usertype']))
		{
			if  ($_SESSION['usertype'] == "staff")
			{
				//staff fcts
				include "updateInventory.php";
				echo "<br>";
				include "updateOrders.php";
			}
            else if ($_SESSION['usertype'] == "manager")
            {   
				// manager fcts
				include "updateInventory.php";
				echo "<hr>";
				include "updateOrders.php";
				echo "<hr>";
				include "promotion.php";
				
            }
		}
		
		?>
		</nav>

		<section>
		<?php
		echo "<h2> Browse our extensive selection of toys ! </h2>";
		$con = connectToDB();
		
		if (isset($_SESSION['usertype']))
		{
			if  ($_SESSION['usertype'] == "customer")
			{
                showInventory($con);
				showOrders($con);
			}
            else if ($_SESSION['usertype'] == "staff")
            {
                showInventoryStaff($con);
                showOrdersStaff($con);
            }
            else
            {
				showInventoryStaff($con);
                showOrdersStaff($con);
                echo "<br>Total revenue for your company is currently: $" . totalRev($con) ."<br><br>";
                echo "Total number of items sold is: " . totalItemSold($con);
			}
		}
        else
        {
            showInventory($con);
        }
		?>
		</section>
	</BODY>
	
	<footer>
		<?php if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";} else {include "navigation.html";} ?>
	</footer>
</HTML>
