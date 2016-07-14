<?

/* gets the data from a URL */

function strpos_all($haystack, $needle) {
    $offset = 0;
    $allpos = array();
    while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
        $offset   = $pos + 1;
        $allpos[] = $pos;
    }
    return $allpos;
}




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


function getHead($urls){


    $results = array();
    // make sure the rolling window isn't greater than the # of urls
    $rolling_window = 100;
    $rolling_window = (sizeof($urls) < $rolling_window) ? sizeof($urls) : $rolling_window;
    $master = curl_multi_init();
    // $curl_arr = array();
    // add additional curl options here

    $options = array(
      CURLOPT_FOLLOWLOCATION => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
	  CURLOPT_TIMEOUT => 5,
      CURLOPT_NOBODY => TRUE,
	  CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
    );
    // start the first batch of requests
    for ($i = 0; $i < $rolling_window; $i++) {
      $ch = curl_init();
      $options[CURLOPT_URL] = array_pop($urls);
      curl_setopt_array($ch, $options);
      curl_multi_add_handle($master, $ch);
    }
    do {
      while (($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM) {
        ;
      }
      if ($execrun != CURLM_OK) {
        break;
      }
      // a request was just completed -- find out which one
      while ($done = curl_multi_info_read($master)) {
        $info = curl_getinfo($done['handle']);
        $results[$info['url']] = $info;
        $new_url = array_pop($urls);
        if(isset($new_url)){
          $ch = curl_init();
          $options[CURLOPT_URL] = $new_url;
          curl_setopt_array($ch, $options);
          curl_multi_add_handle($master, $ch);
        }
        // remove the curl handle that just completed
        curl_multi_remove_handle($master, $done['handle']);
      }
    } while ($running);
    curl_multi_close($master);
    return $results;
  }



function poster($url,$fields_string){
$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HEADER, true);

curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_USERAGENT, $ua);
curl_setopt($ch, CURLOPT_COOKIE, 'NID=67=pdjIQN5CUKVn0bRgAlqitBk7WHVivLsbLcr7QOWMn35Pq03N1WMy6kxYBPORtaQUPQrfMK4Yo0vVz8tH97ejX3q7P2lNuPjTOhwqaI2bXCgPGSDKkdFoiYIqXubR0cTJ48hIAaKQqiQi_lpoe6edhMglvOO9ynw; PREF=ID=52aa671013493765:U=0cfb5c96530d04e3:FF=0:LD=en:TM=1370266105:LM=1370341612:GM=1:S=Kcc6KUnZwWfy3cOl; OTZ=1800625_34_34__34_; S=talkgadget=38GaRzFbruDPtFjrghEtRw; SID=DQAAALoAAADHyIbtG3J_u2hwNi4N6UQWgXlwOAQL58VRB_0xQYbDiL2HA5zvefboor5YVmHc8Zt5lcA0LCd2Riv4WsW53ZbNCv8Qu_THhIvtRgdEZfgk26LrKmObye1wU62jESQoNdbapFAfEH_IGHSIA0ZKsZrHiWLGVpujKyUvHHGsZc_XZm4Z4tb2bbYWWYAv02mw2njnf4jiKP2QTxnlnKFK77UvWn4FFcahe-XTk8Jlqblu66AlkTGMZpU0BDlYMValdnU; HSID=A6VT_ZJ0ZSm8NTdFf; SSID=A9_PWUXbZLazoEskE; APISID=RSS_BK5QSEmzBxlS/ApSt2fMy1g36vrYvk; SAPISID=ZIMOP9lJ_E8SLdkL/A32W20hPpwgd5Kg1J');

curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
curl_setopt($ch,CURLOPT_POST, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

$result = curl_exec($ch);
$last = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);
return array($result,$last);
}


?>