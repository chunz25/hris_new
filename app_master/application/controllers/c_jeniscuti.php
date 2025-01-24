<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_jeniscuti extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_jeniscuti');
	}
	function getJenisCuti()
	{
		$mresult = $this->m_jeniscuti->getJenisCuti();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function getDetailJenisCuti()
	{
		$jeniscutiid = ifunsetempty($_GET, 'jeniscutiid', null);
		$mresult = $this->m_jeniscuti->getDetailJenisCuti($jeniscutiid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}