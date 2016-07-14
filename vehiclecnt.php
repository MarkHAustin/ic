<?php

	unset($pricearray, $priceamt);
	$webdata = $web;
	$webdata = str_replace('"',"'",$webdata);
	$webdata = str_replace('<',"[",$webdata);
	$webdata = str_replace('>',"]",$webdata);

	// determine the count of vehicles
		$pricearray = strpos_all($webdata, ']price[');
		
		$vehiclecnt = preg_replace("/[^0-9\.]/", "",substr($webdata,strpos($webdata,"class='vehicle-count'")+22,3));
		
			// remove 'alt' tags that are in the way.

		$webdata = str_replace("alt='Carfax","",$webdata);	
		$webdata = str_replace("alt='Click to instant message","",$webdata);	

		

		
	// loop throug price array and remove commas (,) and dollar signs ($)
		
		for($x = 0; $x < count($pricearray); $x++) {
			$priceamt[$x] = preg_replace("/[^0-9\.]/", "",substr($webdata,$pricearray[$x]+65,7));
		}
		
	// determine the minimum price of the array
	
		$minprice = min(array_values( array_filter($priceamt)));
		
	// old code to determine the name of the cars....		
		
		#$cars = strpos_all($webdata, "' alt='");
		#$cars1 = substr($webdata,$cars[$a]+7,100);
		#$cars1 = substr($cars1,0,strpos($cars1,"'"));

	