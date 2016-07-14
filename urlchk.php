<?php

$time_start = microtime(true);
include('inc/functions.php');
include('inc/dbconnect.php');
ini_set('safe_mode','1');

// Report all errors except E_WARNING
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);


//set php script timeout, 0 to disable 
set_time_limit(0); 



	// retry all PAGE LOAD ERROR status
	
	$sql = "update gs_url_dd set status = '' where DATE_FORMAT( recdate,  '%Y-%m-%d' ) = CURDATE( )  and status = 'PAGE LOAD ERROR'";
	$result33 = $conn->query($sql);



	// if there are no more records to process then DIE....
	
	 $sql = "select count(*) as cnt from gs_url_dd where DATE_FORMAT( recdate,  '%Y-%m-%d' ) = date_format( CURDATE( ),'%Y-%m-%d')  and status = ''";
		$result33 = $conn->query($sql);
		
	 while($row = $result33->fetch_assoc()) {
			
			if ($row['cnt'] == 0) {
			
				die();

			}
		}


	// load all URLs and No INVENTORY TEXT to process...
	
	$sql = "select distinct u.url, a.noinvtxt, a.404txt from gs_url_dd u left join advertisers a on u.accountname = a.accountname where u.status = '' and u.url != ''";
	$result = $conn->query($sql);
	
	 while($row = $result->fetch_assoc()) {
		
		$status = '';
				
		$row['url'] = trim($row['url']);		
		$originalurl = $row['url'];
		//  add 'www' if missing in the url to check
		
			IF (strpos($row['url'],'www') == 0 && strpos($row['url'],'http://') > -1) {
				
			$row['url'] = str_replace('http://','http://www.',$row['url']);
		}
		
		// get contents of webpage
		
			$web = get_data($row['url']);

			#echo $row['url'] . "<br>";

		// make entire webpage lower case

			$web = strtolower($web);

		// establish status 

				if (strstr($web,strtolower($row['noinvtxt']))) {
					$status = "no inventory";
				} 
			
				if ($status == '') {
				
					$status = 'good url';					
					include('vehiclecnt.php');
				}

		// if webpage did not load then mark status as 'page load error'

				if (strlen($web) == 0) {
					$status = "PAGE LOAD ERROR";
				}
			
		// update ALL reocrds in URL table with status for this url
		
			$sql = "update gs_url_dd 
			
						set status = '" . $status . "', 
						minprice = '" . $minprice . "', 
						vehiclecnt = '" . $vehiclecnt . "', 
						chktime = DATE_SUB(NOW(),INTERVAL -2 HOUR) 
					where 
					
						 DATE_FORMAT( recdate,  '%Y-%m-%d' ) =  curdate() and 
						 trim(url) = '" . trim($originalurl) . "'";
			
			$result1 = $conn->query($sql);


}  // end  loop


	// if there are no more records to process then create the CSV files....
	
			 $sql = "select count(*) as cnt from gs_url_dd where DATE_FORMAT( recdate,  '%Y-%m-%d' ) = CURDATE( ) and status = ''";
				$result22 = $conn->query($sql);
				
			 while($row = $result22->fetch_assoc()) {
					
					if ($row['url'] == 0) {
					
						include('createcsv.php');
					}
				}
		
		
			$time_end = microtime(true);
			$time = $time_end - $time_start;
  
?>