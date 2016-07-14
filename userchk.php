<form method="post" action="#">
URL to search:<textarea name='urlsearch' rows=20 cols=80><? echo $_POST['urlsearch'] ?></textarea><br>
Text to search:<input name='textsearch'  value="<? echo $_POST['textsearch'] ?>">
<input type='submit'>
</form>


<?



/* gets the data from a URL */
function get_data($url) {
	
	
	$ch = curl_init();
	
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	#curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	
	
	return $data;		
}


if (!$_POST['urlsearch']) {
	die();
}


$urlarray = explode("\n",$_POST['urlsearch']);
$urlarray = array_unique($urlarray);
$urlarray =array_values(array_filter($urlarray));

echo "<pre>";
print_r($urlarray);

echo "count: " . count($urlarray);

echo "<table border = '1'>";
for ($x=0;$x<=count($urlarray)-1;$x++) {
	
	if (strpos($urlarray[$x],'www.')==0) {
	
		$urlarray[$x] = str_replace('http://','http://www.',$urlarray[$x]);
	}
		
	$web = get_data(trim($urlarray[$x]));

	$web = str_replace("<","[",$web);
	$web = str_replace(">","]<hr>",$web);
	if (strstr($web,$_POST['textsearch'])) {
		$foundtxt = 'found';
	
	} else {
		$foundtxt = 'not found';
	
	}
	
	if (strlen($web) == 0) {
		$foundtxt = 'page error';
	}
	
	
	echo "<tr><td> " . $urlarray[$x] . "</td><td>" . $foundtxt . "</td></tr>";
}
echo "</table>";


?>