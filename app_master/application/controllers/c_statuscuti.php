<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_statuscuti extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_statuscuti');
	}
	function getStatusCuti()
	{
		$mresult = $this->m_statuscuti->getStatusCuti();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}