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
		<form action="post_register.php" method="post">
		First Name: <br><input type="text" name="firstName"><br>
		Last Name: <br><input type="text" name="lastName"><br>
		Username: <br><input type="text" name="userName"><br>
		Password: <br><input type="text" name="password"><br>
		Password (Again): <br><input type="text" name="password2"><br><br>

		User Type:
		<input type="radio" name="usertype"
		<?php if (isset($userType) && $userType=="customer") echo "checked";?>value="customer">Customer
		<input type="radio" name="usertype"
		<?php if (isset($userType) && $userType=="staff") echo "checked";?>value="staff">Staff
		<input type="radio" name="usertype"
		<?php if (isset($userType) && $userType=="manager") echo "checked";?>value="manager">Manager<br><br>

		<input type="submit">

		</form>
		</section>
	</BODY>
	
	<footer>
		<?php if (isset($_SESSION['userName'])){include "navigationLoggedIn.html";} else {include "navigation.html";} ?>
	</footer>
</HTML>


