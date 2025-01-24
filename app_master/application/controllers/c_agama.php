<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_agama extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_agama');
	}
	function get_agama()
	{
		$mresult = $this->m_agama->get_agama();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}