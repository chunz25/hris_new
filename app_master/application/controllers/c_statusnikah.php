<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_statusnikah extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_statusnikah');
	}
	function getStatusNikah()
	{
		$mresult = $this->m_statusnikah->getStatusNikah();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudStatusNikah()
	{
		$text = ifunsetempty($_POST, 'text', null);
		$mresult = $this->m_statusnikah->tambah($text);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}
}