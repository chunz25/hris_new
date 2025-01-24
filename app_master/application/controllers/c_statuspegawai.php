<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_statuspegawai extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_statuspegawai');
	}
	function get_statuspegawai()
	{
		$mresult = $this->m_statuspegawai->get_statuspegawai();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
	function crudEndOfContract()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'statuspegawaiid' => ifunsetempty($_POST, 'statuspegawaiid', null),
			'tglakhirkontrak' => ifunsetempty($_POST, 'tglakhirkontrak', null),
			'tglpermanent' => ifunsetempty($_POST, 'tglpermanent', null),
		);
		if ($flag == '1') {
			$mresult = $this->m_statuspegawai->ubah($params);
		}
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}
}