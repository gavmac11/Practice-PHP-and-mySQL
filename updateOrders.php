<HTML><HEAD><TITLE> Login </TITLE></HEAD>
<BODY>
<form action="post_update_orders.php" method="post">

		Order ID: <br><input type="text" name="orderID"><br>
		
		Shipping Status:
		<input type="radio" name="shipping"
		<?php if (isset($ship) && $ship=="shipped") echo "checked";?>value="shipped">Shipped
		<input type="radio" name="shipping"
		<?php if (isset($ship) && $ship=="pending") echo "checked";?>value="pending">Pending

		<input type="submit">

		</form>
</BODY></HTML>
