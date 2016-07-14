<?
echo "<br><br><br><br><br>";

echo "<table width = '600' align = 'center' class='imagetable'>";
echo "<tr><td align = 'center'><a href='logout.php'>logout</a><br><br>Copyright &reg; 2011-2016 Hoot Interactive. All Rights Reserved.</td></tr>";

if ($_GET['debug']) {

	echo "<tr><td>$sql</td></tr>";
}

echo "</table>";

?>