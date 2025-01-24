<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_statuskehadiran extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_statuskehadiran');
	}
	function getStatusKehadiran()
	{
		$mresult = $this->m_statuskehadiran->getStatusKehadiran();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}