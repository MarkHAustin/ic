tools<hr>
<a href="home.php">End User Interface (UI)</a><br>



<a href="../bulksheet/index.php">bulk sheet maker</a><br>

<a href="sandbox/pricesearch.php">vehicle name and price search (dealer.com only)</a><br>
<a href="userchk.php">check urls</a><br>
<a href="displaypg.php">display contents of website</a>
<a href="admin.php?showrecs=1">show detailed url status</a>

<?PHP

include("inc/dbconnect.php");
include('inc/style.css');

$sql = "SELECT * FROM advertisers order by accountname";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
 	echo "<table class='imagetable' border='1'>";
	echo "<tr><th align='center'>name</th><th align='center'>location</th><th align='center'>no inventory<br>text</th><th align='center'>404<br>text</th><th align='center'>google id</th>
	<th align='center'>url scrape<br>active</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
		
			<td>" . $row["accountname"]. "</td>
			<td>" . $row["location"]. "</td>
			<td>" . $row["noinvtxt"]. "</td>
			<td>" . $row["404txt"]. "</td>
			<td>" . $row["googleid"] . "</td>
			<td>" . $row["active"]. "</td>
			
			</tr>";
    }

	echo "</table>";
}


echo "<br><br>";


echo "<table border='1'  class='imagetable' >";

$sql = "select count(*) as cnt FROM gs_url_dd where status = '' and date_format(recdate,'%Y-%m-%d') >= CURDATE( )";
$result3 = $conn->query($sql);

    while($row = $result3->fetch_assoc()) {
        echo "<tr><td>left to check</td><td>" . $row["cnt"]. "</td></tr>";
 	}



$sql = "SELECT MAX( chktime ) AS maxtime, MIN( chktime ) AS mintime, TIME_TO_SEC( TIMEDIFF( MAX( chktime ) , MIN( chktime ) ) ) AS diff
FROM gs_url_dd
WHERE recdate >= CURDATE( ) and chktime != '0000-00-00 00:00:00'";
$result2 = $conn->query($sql);

    while($row = $result2->fetch_assoc()) {
        echo "<tr><td>start time</td><td>" . $row["mintime"]. "</td></tr>";
        echo "<tr><td>end time</td><td>" . $row["maxtime"]. "</td></tr>";
        echo "<tr><td>duration (sec.)</td><td>" . $row["diff"]. "</td></tr>";
        echo "<tr><td>duration (min.)</td><td>" . number_format(strval($row["diff"]) / 60,2) . "</td></tr>";
	}


	echo "</table><hr>";




// URL count by day
		
		$sql = "SELECT date_format(recdate, '%Y-%m-%d') as recdate, COUNT( * ) as cnt
		FROM  `gs_url_dd` 
		GROUP BY date_format(recdate, '%Y-%m-%d')
		ORDER BY date_format(recdate, '%Y-%m-%d') DESC 
		";
		$result1 = $conn->query($sql);
		
		if ($result1->num_rows > 0) {
			echo "<table border='1'  class='imagetable' >";
			echo "<tr><th>day</th><th>URL tot</th></tr>";
			
			while($row = $result1->fetch_assoc()) {
			
				echo "<tr><td>" . $row["recdate"]. "</td><td>" . $row["cnt"].  "</td></tr>";
			}
		
			echo "</table>";
		}
		
		echo "<br><br>";
		




$sql = "SELECT accountname, date_format(recdate, '%Y-%m-%d') as recdate, status, count(*) as cnt FROM gs_url_dd 
group by accountname, date_format(recdate, '%Y-%m-%d'), status order by accountname, date_format(recdate,'%Y %m %d') desc, status";
$result1 = $conn->query($sql);

if ($result1->num_rows > 0) {
 	echo "<table border='1'  class='imagetable' >";
	
    while($row = $result1->fetch_assoc()) {
		if ($row["accountname"] != $oldacct) {
			echo "<tr><th colspan = '4'></td></tr>";
		
		}
	
        echo "<tr><td>" . $row["accountname"]. "</td><td>" . $row["recdate"]. "</td><td>" . $row["status"]. "</td><td>" . $row["cnt"] . "</td></tr>";
		$oldacct = $row["accountname"];
    }

	echo "</table>";
}

echo "<br><br>";



// show detailed records


if ($_GET['showrecs']) {

	$sql = "SELECT * FROM gs_url_dd WHERE recdate >= CURDATE( ) order by accountname,status,url";
	$result1 = $conn->query($sql);
	
	if ($result1->num_rows > 0) {
		echo "<table border='1'>";
		
		while($row = $result1->fetch_assoc()) {
			echo "<tr><td>" . $row["accountname"]. "</td><td>" . $row["campaign"]. "</td><td>" . $row["adgroup"]. "</td><td><a target='_blank' href='" . $row["url"] . "'>" . $row["url"] . "</a></td><td>" . $row["status"]. "</td></tr>";
		}
	
		echo "</table>";
	}
}

$conn->close();