<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_pendidikan extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pendidikan');
	}
	function getDataPendidikan()
	{
		$mresult = $this->m_pendidikan->getDataPendidikan();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}