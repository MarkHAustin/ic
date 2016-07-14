<?PHP
include('import_tsv.php');
include('urlchk.php');
include('createcsv.php');

$sql = "SELECT accountname, status, count(*) as cnt FROM gs_url_dd group by accountname, status";
$result1 = $conn->query($sql);

if ($result1->num_rows > 0) {
 	$msg .= "<table border='1'>";
	
    while($row = $result1->fetch_assoc()) {
        $msg .=  "<tr><td>" . $row["accountname"]. "</td><td>" . $row["status"]. "</td><td>" . $row["cnt"] . "</td></tr>";
    }

	$msg .=  "</table>";
}

mail('m.c.holcomb@gmail.com','dealer cron',$msg);




?>