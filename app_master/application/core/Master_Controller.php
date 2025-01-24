<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_Controller extends CI_Controller{
	function Master_Controller(){
		parent::__construct();
		$this->lang->load('tp_application', 'indonesia');
	}
}
