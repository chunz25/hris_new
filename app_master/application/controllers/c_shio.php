<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_shio extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_shio');
	}
	function get_shio()
	{
		$mresult = $this->m_shio->get_shio();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}
