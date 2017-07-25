<?php 

class dateHelper extends Application{

	var $Request;
	
	function __construct($req){
	$this->Request = $req;
	
	}
	
	function dateDiff($pubDate){
		$today = strtotime(date('Y-m-d H:i:s'));
		$pubDate = strtotime($pubDate);
		//detik,menit,jam,hari
		$seconds = floor(abs($today-$pubDate));
		$minutes = floor(abs($today-$pubDate)/60);
		$hours = floor(abs($today-$pubDate)/60/60);
		$days = floor(abs($today-$pubDate)/60/60/24);
		$weeks = floor(abs($today-$pubDate)/60/60/24/7);
		$months = floor(abs($today-$pubDate)/60/60/24/7/4);
		$years = floor(abs($today-$pubDate)/60/60/24/7/4/12);
			
		$formatDiff = array($seconds,$minutes,$hours,$days,$weeks,$months,$years);
		
		if($seconds < 60) return $formatDiff[0].' seconds ago';
		if($seconds > 60 && $minutes < 60) return $formatDiff[1].' minutes ago';
		if($minutes > 60 && $hours < 24) return $formatDiff[2].' hours ago';
		if($hours > 24 && $days < 7) return $formatDiff[3].' days ago';
		if($days > 7 && $weeks < 4) return $formatDiff[4].' weeks ago';
		if($weeks > 4 && $months < 12) return $formatDiff[5].' months ago';
		if($months > 12 ) return $formatDiff[6].' years ago';
		
	}
	
	
}	

?>

