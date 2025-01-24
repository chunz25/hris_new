<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_goldarah extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_goldarah');
	}
	function get_goldarah()
	{
		$mresult = $this->m_goldarah->get_goldarah();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}
