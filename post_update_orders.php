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
				
		if (isset($_SESSION['userName'])){echo "Welcome " . $_SESSION['firstName'] . " " . $_SESSION['lastName'] . "<br/>";}
		else {include "login.php";}
		
		if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";}
		else {include "navigation.html";}
		?>
		</nav>

		<section>
		<?php
		$con = connectToDB();
	
        if (isset($_SESSION['usertype']))
		{
			if  ($_SESSION['usertype'] == "staff")
			{
				$tmpOrder = $_POST['orderID'];
				$tmpShip = $_POST['shipping'];
				updateOrders($con, $tmpOrder,$tmpShip);
			}
            else if ($_SESSION['usertype'] == "manager")
            {   
				// manager fcts
            }
		}
            
		?>
		</section>
	</BODY>
	
	<footer>
		<?php if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";} else {include "navigation.html";} ?>
	</footer>
</HTML>


