<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class tp_tcpdf{
	function tp_tcpdf(){
		require_once('tcpdf/config/lang/eng.php');
		require_once('tcpdf/tcpdf.php');
	}
}
?>