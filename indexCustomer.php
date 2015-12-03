<HTML>

<HEAD>
<TITLE> Home </TITLE>
<style>
header
{
    background-color:black;
color:white;
    text-align:center;
padding:5px;
}
nav
{
    line-height:30px;
    background-color:#eeeeee;
    //height:400px;
width:200px;
    float:left;
padding:5px;
}
section
{
width:350px;
    float:left;
padding:10px;
}
footer
{
    background-color:gray;
color:white;
clear:both;
    text-align:center;
padding:5px;
}
</style>

</HEAD>

<BODY>

<?php
    
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
    
    function retrieveInventory()
    {
        
    }
    ?>
<header>
<H1> Welcome to Adam and Gavin's Online Store !</H1>
</header>
<nav>
<?PHP include "login.php";?>
</nav>


<?php echo "<h2> Browse our extensive selection of toys ! </h2>"; ?>
<?php include "navigation.html" ?>
<?php
    
    $con = connectToDB();
    echo "<br>";
    $sql = 'SELECT * FROM Inventory;';
    $result = mysqli_query($con, $sql);
    echo "<table border=1>
    <tr>
    <th>Item ID</th>
    <th>Name</th>
    <th>Description</th>
    </tr><br>";
    while($record = mysqli_fetch_array($result))
    {
        echo "<tr><td>";
        echo $record["itemID"] . "</td>";
        echo "<td>" . $record["name"] . "</td>";
        echo "<td>" . $record["description"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    //printf ("%s (%s)\n",$record["itemID"],$record["name"], $record["description"]);
    
    
    ?>

</BODY>

<footer>
<?php include "navigation.html" ?>
</footer>
</HTML>
