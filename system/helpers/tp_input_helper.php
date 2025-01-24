<?php
function ifunset($arr, $key, $default) {
	return isset($arr[$key])? $arr[$key] : $default;
}
function ifunsetempty($arr, $key, $default) {
	return !isset($arr[$key]) || empty($arr[$key]) || $arr[$key]=='undefined' ? $default : $arr[$key];
}
function ifunsetemptybase64($arr, $key, $default) {
	return !isset($arr[$key]) || empty($arr[$key])? $default : base64_decode($arr[$key]);
}
function ifempty($val, $default) {
	return empty($val)? $default : $val;
}
function settrim($text){
	return !isset($text) || empty($text) ? '' : preg_replace('/\s+/', '', $text);
}

function echojson($data) {
	header("Content-Type: application/json");
	
	if (!is_string($data)) {
		$data = json_encode($data);
	}
	
	echo $data;
}
function convertArrayKeysToUtf8(array $array){ 
	$convertedArray = array(); 
	foreach($array as $key => $value) { 
		if(!mb_check_encoding($key, 'UTF-8')) $key = utf8_encode($key); 
		if(is_array($value)) $value = convertArrayKeysToUtf8($value); 
		$convertedArray[$key] = $value; 
	} 
	return $convertedArray; 
} 		
function utf8_for_xml($string){
	return preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
}