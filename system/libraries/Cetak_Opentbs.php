<?php
include_once('FormatStyle.php');
if(isset($_GET['source'])) exit('<!DOCTYPE HTML><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>OpenTBS plug-in for TinyButStrong - demo source</title></head><body>'.highlight_file(__FILE__,true).'</body></html>');
if (version_compare(PHP_VERSION,'5')<0) {
	include_once('tbs/tbs_class_php4.php'); // TinyButStrong template engine for PHP 4
} 
else{
	include_once('tbs/tbs_class_php5.php'); // TinyButStrong template engine
}

if(file_exists('tbs/tbs_plugin_opentbs.php')){
	include_once('tbs/tbs_plugin_opentbs.php');
	include_once('tbs/tbs_plugin_aggregate.php');
} 
else{
	include_once('tbs/tbs_plugin_opentbs.php');
	include_once('tbs/tbs_plugin_aggregate.php');
}

class Cetak_Opentbs{
	function __construct($type=""){}
	function _templateExist($templateName=""){
		$isExist = file_exists($templateName);
		if (!$isExist) {
			show_error('Template tidak ada ('.basename ($templateName).')');
		}
		return $isExist;
	}
	function newTemplate($templateUri){
		$TBS = new clsTinyButStrong;
		$TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
		$this->_templateExist($templateUri);
		$TBS->LoadTemplate($templateUri,OPENTBS_ALREADY_XML);
		return $TBS;		
	}
	function format($string){
		$print = new FormatStyle();
		return $print->format($string);
	}
}
?>
