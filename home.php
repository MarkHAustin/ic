<?php
session_start();
include_once 'inc/dbconnectuser.php';

if(!isset($_SESSION['clientid']))
{
 header("Location: login.php");
}

include('inc/style.css');
echo "<br><br><br><br><br>";

if(isset($_POST['updatead'])){
	
	$sql = "update advertisers set
	
		location = '" . $_POST['location'] . "',
		googleid = '" . $_POST['googleid'] . "',
		bingid = '" . $_POST['bingid'] . "',
		
		noinvurl = '" . $_POST['noinvurl'] . "',
		404txt = '" . $_POST['404txt'] . "',
		active = '" . $_POST['active'] . "'
			where id = " . $_POST['id'];				
	#noinvtxt = '" . $_POST['noinvtxt'] . "',
	$result = $conn->query($sql);
	unset($_GET['actn']);
	
}



if (!$_GET['actn']) {

		$sql = "
		
				select advertisers.*, pageloaderror.cnt as pageloaderror, urlgood.cnt as goodurl, noinv.cnt as noinv, 404tot.cnt as 404cnt from 
				
					
					(SELECT a.id, c.clientname, a.accountname, a.googleid, IF(a.active=1,'Yes','No') as active 
					FROM client c LEFT JOIN  `advertisers` a ON c.id = a.clientid where c.id = " .$_SESSION['clientid'] .") advertisers
					
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS , COUNT( * ) AS cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL 0
					DAY ) and `status` = 'good url' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) urlgood
					
					on advertisers.accountname = urlgood.accountname
				
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS ,IFNULL(COUNT(*),0) AS Cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL 0
					DAY ) and `status` = 'no inventory' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) noinv
					
					on advertisers.accountname = noinv.accountname
				
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS ,IFNULL(COUNT(*),0) AS Cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL 0
					DAY ) and `httpcode` = '404' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) 404tot
					
					on advertisers.accountname = 404tot.accountname
					
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS ,IFNULL(COUNT(*),0) AS Cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL 0
					DAY ) and `status` = 'PAGE LOAD ERROR' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) pageloaderror
					
					on advertisers.accountname = pageloaderror.accountname
					
		order by advertisers.accountname
		";
		
		$result = $conn->query($sql);
		#echo $sql;
		include('inc/header.php');
		echo "<table align='center' class='imagetable'>";
		
		echo "<tr><th colspan = '9' align='left'>" . $_SESSION['clientname'] . "</td></tr>";
		echo "<tr><th colspan='3'>Account Name</th><th>Google<br>Account ID</th><th>Active</th><th>Activated<br>Ad Groups</th><th>No Results<br>Found</th><th>Page Errors</th><th>404 Errors</th></tr>";
		
		while($row = $result->fetch_assoc()) {
		
			$row['noinv'] = $row['noinv'] == '' ? '0' : $row['noinv'];
			$row['404cnt'] = $row['404cnt'] =='' ? '0' : $row['404cnt'];
			$row['goodurl'] = $row['goodurl'] =='' ? '0' : $row['goodurl'];
			$row['pageloaderror'] = $row['pageloaderror'] =='' ? '0' : $row['pageloaderror'];

		
			echo "<tr><td>" . $row['accountname'] . "</td>";
			echo "<td align = 'center'><a href= 'home.php?actn=editad&aid=". $row['id'] . "'>" . "edit" . "</a></td>";	
			echo "<td align = 'center'><a href= 'home.php?actn=today&aid=". $row['id'] . "'>" . "reports" . "</a></td>";	
			echo "<td align = 'center'>" . $row['googleid'] . "</td>";	
			echo "<td align = 'center'>" . $row['active'] . "</td>";	
			echo "<td align = 'right'>" . $row['goodurl'] . "</td>";	
			echo "<td align = 'right'>" . $row['noinv'] . "</td>";	
			echo "<td align = 'right'>" . $row['pageloaderror'] . "</td>";	
			echo "<td align = 'right'>" . $row['404cnt'] . "</td></tr>";	
		}
		
		echo "</table>";

} // END DEFAULT REPORT



if ($_GET['actn']=='yest'  || $_GET['actn']=='today' ) {

		if ($_GET['actn']=='yest') {
		
			$daysago = -1;
		}

		if ($_GET['actn']=='today') {
		
			$daysago = 0;
		}

		$sql = "
		
				select  DATE_ADD( CURDATE( ) , INTERVAL " . $daysago . " DAY) as LastRunDate, advertisers.*, pageloaderror.cnt as pageloaderror, urlgood.cnt as goodurl, noinv.cnt as noinv, 404tot.cnt as 404cnt from 
				
					(SELECT c.clientname, a.accountname, a.googleid, IF(a.active=1,'Yes','No') as active 
					FROM client c LEFT JOIN  `advertisers` a ON c.id = a.clientid where c.id = " .$_SESSION['clientid'] . " and a.id = " . $_GET['aid'] . ") advertisers
					
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS , COUNT( * ) AS cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL " . $daysago . " 
					DAY ) and `status` = 'good url' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) urlgood
					
					on advertisers.accountname = urlgood.accountname
				
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS ,IFNULL(COUNT(*),0) AS Cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL " . $daysago . " 
					DAY ) and `status` = 'no inventory' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) noinv
					
					on advertisers.accountname = noinv.accountname
				
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS ,IFNULL(COUNT(*),0) AS Cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL " . $daysago . " 
					DAY ) and `httpcode` = '404' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) 404tot
					
					on advertisers.accountname = 404tot.accountname
					
									
				left join
					
					(SELECT accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) AS recdate, 
					STATUS ,IFNULL(COUNT(*),0) AS Cnt
					FROM gs_url_dd
					WHERE DATE_FORMAT( recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL  " . $daysago . " 
					DAY ) and `status` = 'PAGE LOAD ERROR' 
					GROUP BY accountname, DATE_FORMAT( recdate,  '%Y-%m-%d' ) , STATUS) pageloaderror
					
					on advertisers.accountname = pageloaderror.accountname
		";
		#echo $sql;
		$result = $conn->query($sql);
		 
		include('inc/header.php');
		echo "<table width = '600' align='center' class='imagetable'>";
		
		while($row = $result->fetch_assoc()) {


		#display breadcrumbs....

		$x++;
		if ($x==1) {
			echo "<tr><th colspan = '7' align='left'><a href='home.php'>Dashboard</a> > " . $row['accountname'] . " > Reports</th></tr>";
			echo "<tr><td colspan='22'><hr></td></tr>";
		
		}
		
		#deal with MySQL NULL totals....

			$row['noinv'] = $row['noinv'] == '' ? '0' : $row['noinv'];
			$row['404cnt'] = $row['404cnt'] =='' ? '0' : $row['404cnt'];
			$row['goodurl'] = $row['goodurl'] =='' ? '0' : $row['goodurl'];
			$row['pageloaderror'] = $row['pageloaderror'] =='' ? '0' : $row['pageloaderror'];
		
		# display rows...
		
			echo "<tr><td>Last Run Date</td><td align='right'>" . $row['LastRunDate'] . "</td><td></td>";
			echo "<tr><td>Activated AD Groups</td><td align='right'>" . $row['goodurl'] . "</td><td><a href = 'home.php?actn=detail&cat=goodurl&adid=" . $_GET['aid'] . "'>view</a></td>";
			echo "<tr><td>No Results Found</td><td align='right'>" . $row['noinv'] . "</td><td><a href = 'home.php?actn=detail&cat=noinv&adid=" . $_GET['aid'] . "'>view</a></td>";
			echo "<tr><td>Error Responses</td><td align='right'>" . $row['pageloaderror'] . "</td><td><a href = 'home.php?actn=detail&cat=pageloaderror&adid=" . $_GET['aid'] . "'>view</a></td>";
			echo "<tr><td>Status Changes - Last Run</td><td align='right'>xxx</td><td><a href = 'home.php?actn=detail&cat=change&adid=" . $_GET['aid'] . "'>view</a></td>";

		}
		
		echo "</table>";

} // END YESTERDAY REPORT


//  DETAIL SCREEN....

if ($_GET['actn']=='detail') {

	switch ($_GET['cat']) {
	
		case 'noinv':
			$_GET['cat'] = 'no inventory';
			break;
	
		case 'goodurl':
			$_GET['cat'] = 'good url';
			break;
	
		case '404':
			$_GET['cat'] = '404 error';
			break;	
		case 'pageloaderror':
			$_GET['cat'] = 'page load error';
			break;
	
	}


		$sql = "
					
			SELECT u.accountname, u.campaign, u.adgroup, u.adgroupid, u.url, u.status , u.minprice, u.vehiclecnt, u.httpcode 
			
			FROM client c LEFT JOIN  advertisers a on c.id = a.clientid
			LEFT JOIN gs_url_dd u on a.accountname = u.accountname 
			
			where u.status = '" . $_GET['cat'] . "' and c.id = " .$_SESSION['clientid'] . " and a.id = " . $_GET['adid']. "
			and  DATE_FORMAT( u.recdate,  '%Y-%m-%d' ) = DATE_ADD( CURDATE( ) , INTERVAL 0
								DAY ) 
								
			order by u.campaign, u.adgroup, u.url, u.status
								";
		$result = $conn->query($sql);
		 
		include('inc/header.php');
		echo "<table align='center' class='imagetable'>";

		while($row = $result->fetch_assoc()) {
			$x++;

		if ($x==1) {		
		echo "<tr><th colspan = '8' align='left'><a href='home.php'>Dashboard</a> > " . $row['accountname'] . " > Summary</th></tr>";
		echo "<tr>
				<th align='center'>Campaign<br>Name</th>
				<th align='center'>Adgroup<br>Name</th>
				<th align='center'>Adgroup<br>ID</th>
				<th align='center'>URL</th>
				<th align='center'>Status</th>
				<th align='center'>HTTP Code</th>
				<th align='center'>Vehicle<br>Count</th>
				<th align='center'>Lowest<br>Price</th>
			</tr>";
		}
			echo "<tr><td align = 'left'>" . $row['campaign'] . "</td>";	
			echo "<td align = 'left'>" . $row['adgroup'] . "</td>";	
			echo "<td align = 'left'>" . $row['adgroupid'] . "</td>";	
			echo "<td align = 'left'><a href='" . $row['url'] . "' target='_blank'>".$row['url'] ."</td>";	
			echo "<td align = 'left'>" . $row['status'] . "</td>";	
			echo "<td align = 'center'>" . $row['httpcode'] . "</td>";	
			echo "<td align = 'center'>" . $row['vehiclecnt'] . "</td>";	
			echo "<td align = 'center'>" . $row['minprice'] . "</td></tr>";	


		}
		
		echo "<tr><td colspan='8' align = 'right'>record count: $x</td></tr></table>";

} // END DEFAULT REPORT


//////////////////////////////////////////////
//
//	edit advertiser
//
//////////////////////////////////////////////

if ($_GET['actn'] == 'editad') {

		$sql = "
		
				select * from advertisers where id = " . $_GET['aid'];
	#echo $sql;
		
		$result = $conn->query($sql);
		 


		
		
		while($row = $result->fetch_assoc()) {
			$x++;
			
			if ($x==1) {
			
				echo "<table align='center' class='imagetable'>";		
				echo "<tr><th colspan = '7' align='left'><a href='home.php'>Dashboard</a> > " . $row['accountname'] . " > Edit Advertiser Record</th></tr>";
			}
		
			echo "<form actn='#' method='post' id='updatead' name='updatead'>";
				echo "<input type='hidden' name='id' value = '" . $row['id'] . "'>";
				echo "<tr><td>location</td><td><input size='80' name='location' value = '" . $row['location']  . "'></td></tr>";
				echo "<tr><td>google id</td><td><input name='googleid' value = '" . $row['googleid']  . "'></td></tr>";
				echo "<tr><td>bing id</td><td><input name='bingid' value = '" . $row['bingid']  . "'></td></tr>";
				#echo "<tr><td>no inventory<br>text</td><td><input name='noinvtxt' value = '" . $row['noinvtxt']  . "'></td></tr>";
				echo "<tr><td>no inventory<br>url</td><td><input name='noinvurl' value = '" . $row['noinvurl']  . "'></td></tr>";
				echo "<tr><td>404<br>text</td><td><input name='404txt' value = '" . $row['404text']  . "'></td></tr>";
				echo "<tr><td>active?</td><td>
						<select name='active'>
							<option "; 
							if ($row['active'] == 1 ) echo 'selected';
						 echo " value='1'>Yes</option>
							<option "; 
							if ($row['active'] == 0 ) echo 'selected';
						 echo " value='0'>No</option>
						</select>";							
						echo "</td></tr>";
						
				echo "<tr><td align = 'center' colspan='2'><input type='submit' name='updatead' value = 'Update " . $row['accountname'] . "'></td></tr>";
							
			echo "</form>";
		}
		
		echo "</table>";

} // END DEFAULT REPORT




/////////////////////////////////////////
//
//	end edit advertiser
//
////////////////////////////////////////


include('inc/footer.php');

?>


</body>
</html>

