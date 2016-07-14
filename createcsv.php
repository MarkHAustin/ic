<?php

include('inc/dbconnect.php');
$hdr1 = "Ad group state";
$hdr2 = "Ad group ID";

$sql = "SELECT accountname FROM advertisers where active = 1";
$result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
			
			//Write the output to  buffer
			$data = fopen('csv/' . str_replace(' ','',strtolower($row['accountname'])) . '.csv', 'w');
			 
			//Output Column Headings
			fputcsv($data,array($hdr1,$hdr2));
			 
			//Retrieve the data from database
			$sql = "
			
					
					SELECT  Adgroupstate, adgroupid AS AdgroupID
					FROM (
					
					SELECT DISTINCT IF( 
					STATUS =  'good url',  'active',  'paused' ) AS  'Adgroupstate', adgroupid AS  'AdgroupID'
					FROM gs_url_dd
					WHERE STATUS !=  ''
					AND adgroup !=  ''
					AND DATE_FORMAT( recdate,  '%Y-%m-%d' ) = CURDATE( ) 
					and accountname = '" . $row['accountname'] . "')result
					GROUP BY adgroupid
					HAVING COUNT( * ) = 1
					
					UNION
					
					SELECT  'paused' AS Adgroupstate, adgroupid AS AdgroupID
					FROM (
					
					SELECT DISTINCT IF( 
					STATUS =  'good url',  'active',  'paused' ) AS  'Adgroupstate', adgroupid AS  'AdgroupID'
					FROM gs_url_dd
					WHERE STATUS !=  ''
					AND adgroup !=  ''
					AND DATE_FORMAT( recdate,  '%Y-%m-%d' ) = CURDATE( ) 	
					and accountname = '" . $row['accountname'] . "')result
					GROUP BY adgroupid
					HAVING COUNT( * ) >1
			
					";
			$result1 = $conn->query($sql);

			//Loop through the data to store them inside CSV
			while($row1 = $result1->fetch_assoc()) {
				
				fputcsv($data, array( $row1['Adgroupstate'],$row1['AdgroupID']));
				
			}

	}



// CSV FILES WITH THE GOOGLE ID AS THE FILENAME

			$sql = "SELECT * from advertisers where active = 1";
			$result2 = $conn->query($sql);



    while($row = $result2->fetch_assoc()) {
	
			//Write the output to  buffer
			$data = fopen('csv/' . str_replace('-','',$row['googleid']) . '.csv', 'w');
			 
			//Output Column Headings
			fputcsv($data,array($hdr1,$hdr2));
			 
			//Retrieve the data from database
			$sql = "SELECT distinct if(u.status='good url','active','paused') as 'Adgroupstate',u.adgroupid as 'AdgroupID' 
						FROM gs_url_dd u left join advertisers a on u.aid = a.id 
					where 
					
						u.status != '' 
						and u.adgroup != '' 
						AND DATE_FORMAT( u.recdate, '%Y-%m-%d' ) = CURDATE( ) 
						and a.googleid = '" . $row['googleid'] . "'";
					
					#echo $sql . "<hr>";
			
			$result3 = $conn->query($sql);
			
			//Loop through the data to store them inside CSV
			while($row3 = $result3->fetch_assoc()) {
				
				fputcsv($data, array( $row3['Adgroupstate'],$row3['AdgroupID']));
				
			}

	}
fclose($data);


?>
