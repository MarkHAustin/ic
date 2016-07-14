<?php 
function multiple_threads_request($nodes){ 
        $mh = curl_multi_init(); 
        $curl_array = array(); 
        foreach($nodes as $i => $url) 
        { 
            $curl_array[$i] = curl_init($url); 
            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true); 
            curl_multi_add_handle($mh, $curl_array[$i]); 
        } 
        $running = NULL; 
        do { 
            usleep(10000); 
            curl_multi_exec($mh,$running); 
        } while($running > 0); 
        
        $res = array(); 
        foreach($nodes as $i => $url) 
        { 
            $res[$url] = curl_multi_getcontent($curl_array[$i]); 
        } 
        
        foreach($nodes as $i => $url){ 
            curl_multi_remove_handle($mh, $curl_array[$i]); 
        } 
        curl_multi_close($mh);        
        return $res; 
} 

$urls[0] = 'http://capitolkia.net/new-inventory/index.htm?model=Optima';
$urls[1] = 'http://www.dutchmillerclt.com/searchused.aspx?make=Ford&model=Edge';
$urls[2] = 'http://www.dutchmillerclt.com/searchused.aspx?make=Ford&model=Expedition';
$urls[3] = 'http://www.kiacapitol.com/used-inventory/index.htm?search=BMW+X4';
$urls[4] = 'http://www.kiacapitol.com/used-inventory/indsdfsdex.htm?search=BMW+Z4';
$urls[5] = 'http://capitolkia.net/new-inventory/index.htm?model=Optima';
$urls[6] = 'http://www.dutchmillerclt.com/searchused.aspx?make=Ford&model=Edge';
$urls[7] = 'http://www.dutchmillerclt.com/searchused.aspx?make=Ford&model=Expedition';
$urls[8] = 'http://www.kiacapitol.com/used-inventory/index.htm?search=BMW+X4';
$urls[9] = 'http://www.kiacapitol.com/used-inventory/indsdfsdex.htm?search=BMW+Z4';


print_r(multiple_threads_request($urls));

 
?>