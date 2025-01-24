<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_relasikeluarga extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_relasikeluarga');
	}
	function getRelasiKeluarga()
	{
		$mresult = $this->m_relasikeluarga->getRelasiKeluarga();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}