<?php
function parsing_url($url){
	$url_string = json_decode($url, true);		
	$str = '';
	foreach($url_string as $key => $item){
		foreach($item as $key2 => $item2){
			$str .= $key2 . '=';				
			foreach($item2 as $key3 => $item3){
				$str .= $item3 . ',';
			}	
			if(count($item2) > 0){
				$str = substr($str, 0, 0-strlen(','));
			}				
			$str .= '&';
		}						
	}
	$str = substr($str, 0, 0-strlen('&'));		
	return $str;
}
