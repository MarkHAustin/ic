<html><body>
<head>
<link rel="stylesheet" type="text/css" href="../inc/style.css">

</head>

<h1>DEALER.COM ONLY --- Vehicle Count and Price Search</h1>
<form method="post" action="#">
URL to search:<br><textarea name='urlsearch' rows=10 cols=80><? echo $_POST['urlsearch'] ?></textarea><br>
<input type='submit'>
</form>


<?php

$time_start = microtime(true);
include('../inc/functions.php');
include('../inc/dbconnect.php');
ini_set('safe_mode','1');

// Turn off all error reporting
error_reporting(0);


//set php script timeout, 0 to disable 
set_time_limit(0); 


$urlarray = explode("\n",$_POST['urlsearch']);
$urlarray = array_unique($urlarray);
$urlarray =array_values(array_filter($urlarray));




	for ($x=0;$x<=count($urlarray)-1;$x++) {
	
	if (strpos($urlarray[$x],'www.')==0) {
	
		$urlarray[$x] = str_replace('http://','http://www.',$urlarray[$x]);
	}
	
		
	$web = get_data(trim($urlarray[$x]));

		$web = str_replace('"',"'",$web);
		$web = str_replace('<',"[",$web);
		$web = str_replace('>',"]",$web);
		
		// determine the count of vehicles
		
		$vehiclecnt = preg_replace("/[^0-9\.]/", "",substr($web,strpos($web,"class='vehicle-count'")+22,3));
		
		// remove the majority of 
		
		$web = substr($web,strpos($web,"[div class='media']"),99999999999);

		// remove 'alt' tags that are in the way.

		$web = str_replace("alt='Carfax","",$web);	
		$web = str_replace("alt='Click to instant message","",$web);	

			echo "<hr>";
			echo "I found " .  $vehiclecnt  . " vehicles on this page<br><br>";
			echo "I see " . substr_count($web,']Price[') . " vehicle amounts on this page (looking for 'price' tag)<br><br>";
			
		// locate position of the vehicle prices
		
		$price = strpos_all($web, ']Price[');
		
		
		$cars = strpos_all($web, "' alt='");
		 

			 echo "<table class='imagetable'>";
			for ($a=0;$a<count($cars);$a++) {
			
				$cars1 = substr($web,$cars[$a]+7,100);
				$cars1 = substr($cars1,0,strpos($cars1,"'"));
				
				echo "<tr><td>" . $cars1 . "</td>";
				
				echo "<td>" .  preg_replace("/[^0-9\.]/", "",substr($web,$price[$a]+65,7)) . "</td></tr>";
				
			}
			echo "</table>";			
			
			echo "<br><br><br><br><hr>$web";
			
			
}  // end  loop

	$time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "<hr>Process Time: {$time}";
?>