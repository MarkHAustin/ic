<?PHP
include('inc/dbconnect.php');

$sql = "delete from  gs_url where aid = " . $_GET['aid'] .  " and recdate > curdate()";
$result = $conn->query($sql);
	



$sql = "SELECT * FROM advertisers where active = 1 and googlesheetlocation != '' and id = " . $_GET['aid'];
$result = $conn->query($sql);
if (!$result) {
    throw new Exception("Database Error [{$this->database->errno}] {$this->database->error}");
}

#echo "imported from the following TSV files....<br>";
	
 while($row = $result->fetch_assoc()) {

		#echo $row["googlesheetlocation"] . "<br>";
	
		$web = file_get_contents($row["googlesheetlocation"]);

		$web = str_replace("\n","\t",$web);
		$web = explode("\t",$web);
		#echo "<pre>";
		#print_r($web);
		#die();


		for($x=0;$x<=count($web);$x++) {
		
		$sql = "insert into gs_url (accountname,campaign,adgroup,adgroupid,adgroupstatus,url) values (";
			
			for ($y=0;$y<=5;$y++) {
				
		
				$sql .= "'" .  $web[$x] . "',";
				$x++;
			}
			$x=$x-1;
			$sql =rtrim($sql, ",");
			$sql .= ")";
			#echo $sql . "<br>";
			$insert_result = $conn->query($sql);
		}
 }

	// update advertiser ID in the gs_url table based on advertiser ID in advertisers table
				
		$sql = "UPDATE gs_url u INNER JOIN advertisers a on a.accountname = u.accountname SET u.aid = a.id";
		$updateaid_result = $conn->query($sql);

 
 
	// remove invalid URLs
				
		$sql = "delete from  `gs_url` where url not like '%.%'";
		$trim_result = $conn->query($sql);

	// remove all adgroups that have [!] 
	
		$sql = "delete from  `gs_url` where adgroup like '%[!]%' or campaign like '%[!]%'";
		$trim_result = $conn->query($sql);
		
	// remove remove carriage returns from URL field
	
		$sql = "update `gs_url` set url = REPLACE(url,'\r','')";
		$trim_result = $conn->query($sql);
	
	// delete todays records from the DEDUPED (final) table...
	
		$sql = "DELETE FROM  `gs_url_dd` WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = CURDATE( ) and aid = " . $_GET['aid'];
		$del2_result = $conn->query($sql);
		
	// insert UNIQUE URLs to the DEDUPED table...
	
		$sql = "insert into gs_url_dd (aid,accountname,campaign, adgroup,adgroupid,url) select distinct aid,accountname,campaign, adgroup,adgroupid,url from gs_url where aid = " . $_GET['aid'];
		$del1_result = $conn->query($sql);


?>