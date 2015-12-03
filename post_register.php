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
		$con = connectToDB();	
		checkUsername($con,"register",$_POST['firstName'],$_POST['lastName'],$_POST['userName'],$_POST['password'],$_POST['usertype']);
	
		?>
		</section>
	</BODY>
	
	<footer>
		<?php if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";} else {include "navigation.html";} ?>
	</footer>
</HTML>


