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
		
		if (isset($_SESSION['userName'])){echo "Welcome " . $_SESSION['firstName'] . " " . $_SESSION['lastName'] . "<br/>";}
		else {include "login.php";}
		
		if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";}
		else {include "navigation.html";}
		?>
		</nav>

		<section>
		<?php
		$id = isset($_GET['itemID']) ? $_GET['itemID'] : "";
		$name = isset($_GET['name']) ? $_GET['name'] : "";

		if(!isset($_SESSION['cart']))
		{
			$_SESSION['cart'] = array();
		}

		$_SESSION['cart'][$id]=$name;
		// redirect to product list and tell the user it was added to cart
		echo $name . " added to cart";
		echo '<META HTTP-EQUIV="Refresh" Content="2;URL=index.php">';
	
		?>
		</section>
	</BODY>
	
	<footer>
		<?php if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";} else {include "navigation.html";} ?>
	</footer>
</HTML>


