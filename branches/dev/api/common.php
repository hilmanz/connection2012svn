<?php
/*==================================
Get url content and response headers (given a url, follows all redirections on it and returned content and response headers of final url)

@return    array[0]    content
        array[1]    array of response headers
==================================*/
function get_url( $url,  $javascript_loop = 0, $timeout = 5 )
{
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec( $ch );
    $response = curl_getinfo( $ch );
    curl_close ( $ch );

    if ($response['http_code'] == 301 || $response['http_code'] == 302)
    {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) )
        {
            foreach( $headers as $value )
            {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                    return get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }

    if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) &&
            $javascript_loop < 5
    )
    {
        return get_url( $value[1], $javascript_loop+1 );
    }
    else
    {
        return array( $content, $response );
    }
}

function xml2ary(&$string) {
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parse_into_struct($parser, $string, $vals, $index);
    xml_parser_free($parser);

    $mnary=array();
    $ary=&$mnary;
    foreach ($vals as $r) {
        $t=$r['tag'];
        if ($r['type']=='open') {
            if (isset($ary[$t])) {
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_c']=array();
            $cv['_c']['_p']=&$ary;
            $ary=&$cv['_c'];

        } elseif ($r['type']=='complete') {
            if (isset($ary[$t])) { // same as open
                if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
                $cv=&$ary[$t][count($ary[$t])-1];
            } else $cv=&$ary[$t];
            if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
            $cv['_v']=(isset($r['value']) ? $r['value'] : '');

        } elseif ($r['type']=='close') {
            $ary=&$ary['_p'];
        }
    }    
    
    _del_p($mnary);
    return $mnary;
}

// _Internal: Remove recursion in result array
function _del_p(&$ary) {
    foreach ($ary as $k=>$v) {
        if ($k==='_p') unset($ary[$k]);
        elseif (is_array($ary[$k])) _del_p($ary[$k]);
    }
}

// Array to XML
function ary2xml($cary, $d=0, $forcetag='') {
    $res=array();
    foreach ($cary as $tag=>$r) {
        if (isset($r[0])) {
            $res[]=ary2xml($r, $d, $tag);
        } else {
            if ($forcetag) $tag=$forcetag;
            $sp=str_repeat("\t", $d);
            $res[]="$sp<$tag";
            if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";}
            $res[]=">".((isset($r['_c'])) ? "\n" : '');
            if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
            elseif (isset($r['_v'])) $res[]=$r['_v'];
            $res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
        }
        
    }
    return implode('', $res);
}

// Insert element into array
function ins2ary(&$ary, $element, $pos) {
    $ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
    $ary=array_merge($ar1, array_slice($ary, $pos));
}

function open_db($n){
	global $DATABASE;
	$host = $DATABASE[$n]['host'];
	$user = $DATABASE[$n]['user'];
	$password = $DATABASE[$n]['password'];
	$conn = mysql_connect($host,$user,$password);
	return $conn;
}
function close_db($conn){
	mysql_close($conn);
}
function retrieve_coordinate($google_location){
	$str = str_replace('ÜT:','',$google_location);
	$arr = explode(",",$str);
	if(sizeof($arr)==2){
		if(eregi("([0-9\.]+)",$arr[0])&&eregi("([0-9\.]+)",$arr[1])){
			return array(trim($arr[0]),trim($arr[1]));
		}
	}
}
function base_auth_get($url,$username,$password,$timeout=3600){
	$ch = curl_init (); 
	curl_setopt($ch,CURLOPT_URL,$url); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	//curl_setopt($ch,CURLOPT_MUTE,1); 
	curl_setopt($ch,CURLOPT_USERPWD,"$username:$password"); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); 
	$result = curl_exec ($ch); 
	$info = curl_getinfo($ch);
	curl_close($ch); 
	return array($result,$info);
}
function base_auth_stream($url,$username,$password,$timeout=20){
	$ch = curl_init (); 
	curl_setopt($ch,CURLOPT_URL,$url); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); 
	//curl_setopt($ch,CURLOPT_MUTE,1); 
	curl_setopt($ch,CURLOPT_USERPWD,"$username:$password"); 
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); 

	curl_setopt($ch, CURLOPT_WRITEFUNCTION, "stream_progress");
	curl_exec ($ch); 
	//$info = curl_getinfo($ch);
	curl_close($ch); 
	//return array($result,$info);
	return 1;
}
function stream_progress($ch,$str){
	print "yey\n";
	print $str."\n\n";
}
function fetch($sql,$conn){
	$q = mysql_query($sql,$conn);
	$f = mysql_fetch_assoc($q);
	mysql_free_result($q);
	return $f;
}
function fetch_many($sql,$conn){
	$q = mysql_query($sql,$conn);
	$rs = array();
	while($f = mysql_fetch_assoc($q)){
		$rs[] = $f;
	}
	mysql_free_result($q);
	return $rs;
}
function merge_sorted_arrays_by_field ($merge_arrays, $sort_field, $sort_desc = false, $limit = 0) 
{ 
    $array_count = count($merge_arrays); 
    
    // fast special cases... 
    switch ($array_count) 
    { 
        case 0: return array(); 
        case 1: return $limit ? array_slice(reset($merge_arrays), 0, $limit) : reset($merge_arrays); 
    } 
    
    if ($limit === 0) 
        $limit = PHP_INT_MAX; 
    
    // rekey merge_arrays array 0->N 
    $merge_arrays = array_values($merge_arrays); 

    $best_array = false; 
    $best_value = false; 
    
    $results = array(); 
    
    // move sort order logic outside the inner loop to speed things up 
    if ($sort_desc) 
    { 
        for ($i = 0; $i < $limit; ++$i) 
        { 
            for ($j = 0; $j < $array_count; ++$j) 
            { 
                // if the array $merge_arrays[$j] is empty, skip to next 
                if (false === ($current_value = current($merge_arrays[$j]))) 
                    continue; 
                
                // if we don't have a value for this round, or if the current value is bigger... 
                if ($best_value === false || $current_value[$sort_field] > $best_value[$sort_field]) 
                { 
                    $best_array = $j; 
                    $best_value = $current_value; 
                } 
            } 
            
            // all arrays empty? 
            if ($best_value === false) 
                break; 
            
            $results[] = $best_value; 
            $best_value = false; 
            next($merge_arrays[$best_array]); 
        } 
    } 
    else 
    { 
        for ($i = 0; $i < $limit; ++$i) 
        { 
            for ($j = 0; $j < $array_count; ++$j) 
            { 
                if (false === ($current_value = current($merge_arrays[$j]))) 
                    continue; 
                
                // if we don't have a value for this round, or if the current value is smaller... 
                if ($best_value === false || $current_value[$sort_field] < $best_value[$sort_field]) 
                { 
                    $best_array = $j; 
                    $best_value = $current_value; 
                } 
            } 
            
            // all arrays empty? 
            if ($best_value === false) 
                break; 
            
            $results[] = $best_value; 
            $best_value = false; 
            next($merge_arrays[$best_array]); 
        } 
    } 
    
    return $results; 
}
function array_sort($array, $on, $order='SORT_DESC') 
    { 
      $new_array = array(); 
      $sortable_array = array(); 
  
      if (count($array) > 0) { 
          foreach ($array as $k => $v) { 
              if (is_array($v)) { 
                  foreach ($v as $k2 => $v2) { 
                      if ($k2 == $on) { 
                          $sortable_array[$k] = $v2; 
                      } 
                  } 
              } else { 
                  $sortable_array[$k] = $v; 
              } 
          } 
  
          switch($order) 
          { 
              case 'SORT_ASC':    
                 // echo "ASC"; 
                  asort($sortable_array); 
              break; 
              case 'SORT_DESC': 
                 // echo "DESC"; 
                  arsort($sortable_array); 
              break; 
          } 
  
          foreach($sortable_array as $k => $v) { 
              $new_array[] = $array[$k]; 
          } 
      } 
      return $new_array; 
    } 
?>
