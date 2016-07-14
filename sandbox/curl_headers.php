<?
$updatesql = '';
$start = microtime(true);
#ini_set('user_agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1');
ini_set("user_agent","Opera/9.80 (Windows NT 6.1; U; Edition Campaign 21; en-GB) Presto/2.7.62 Version/11.00");

include ('../inc/dbconnect.php');
include ('../inc/functions.php');

$x = 0;

$sql = "select CEILING(count(*)/ 20 + 1) as cnt from gs_url_dd 
				where 
				accountname = 'koa sandbox' and 

				
				httpcode = '' and date_format(recdate,'%Y-%m-%d') = curdate()";
		
		$result = $conn->query($sql);
		
		while($row = $result->fetch_assoc()) {
			$loopcnt = $row['cnt'];
		}

echo $loopcnt . "<br>";
#for ($v=0;$v<$loopcnt;$v++) {
for ($v=0;$v<1;$v++) {
		
		$sql = "select distinct a.noinvurl, u.url 
				
				from gs_url_dd u left join advertisers a on u.aid = a.id  
				
				where 
					
					date_format(u.recdate,'%Y-%m-%d') =  curdate() 
					and (u.httpcode = 0 or u.httpcode = 403)
					and a.cmsid = 1 
					
					limit 400
				
		
				";
		  
			#		and u.httpcode = ''
		
		
		$result = $conn->query($sql);
		
		while($row = $result->fetch_assoc()) {
			$x++;
			$urls[$x] = $row['url'];
			#$urls[$x] = str_replace('http://www.','http://',$row['url']);
			$noinvurl[$x] = $row['noinvurl'];
		}	
			
			
			$b = getHead($urls);
			#echo "<pre>";
			#print_r($b);
			
			for ($y=0;$y<=$x;$y++) {
				echo  $urls[$y] . "<br>";
				echo  $noinvurl[$x] . "<br>";
				echo  $b[$urls[$y]][http_code] . "<br>";
				echo  $b[$urls[$y]][redirect_url] . "<br>";
				
				
			
				if (strpos($b[$urls[$y]][redirect_url],$noinvurl[$y]) !=0) {
					
					$status = 'no inventory';
				
				}
		
				if ($b[$urls[$y]][http_code] == '404') {
					
					$status = '404 error';
				
				}
				
				
				if ($b[$urls[$y]][http_code] == '403') {
					
					$status = 'Forbidden';
				
				}
		
				if (!$status) {
					
					$status = 'good url';
				
				}
		
				echo "status: $status<br>";
			
				$updatesql = "update gs_url_dd set httpcode = '" . $b[$urls[$y]][http_code] . "', status = '$status' where url = '" . $urls[$y] . "' and date_format(recdate,'%Y-%m-%d') = curdate();";
				$update_result = $conn->query($updatesql);
				echo  mysqli_error($conn);	
				echo "<hr>";
				$status = '';
			}
			
			
	}
	

			echo "<pre>";
			print_r($b);
				
				
  echo  microtime(true) - $start;
  ?>