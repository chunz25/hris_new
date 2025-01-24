<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include  FCPATH2."system/core/App_Controller.php";
class SIAP_Controller extends App_Controller{
	function SIAP_Controller(){
		parent::__construct();		
	}
	function permission_user(){
		if( !$this->app_check()){
			redirect(config_item('url_cms').'/c_login');
		}
	}	
}
