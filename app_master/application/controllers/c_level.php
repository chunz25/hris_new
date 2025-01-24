<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_level extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_level');
	}
	
	function getLevel()
	{
		$mresult = $this->m_level->getLevel();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function crudLevel()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'levelid' => ifunsetempty($_POST, 'id', null),
			'level' => ifunsetempty($_POST, 'text', null),
			'gol' => ifunsetempty($_POST, 'gol', null),
		);

		if ($flag == '1') {
			$mresult = $this->m_level->tambah($params);
		} else {
			$mresult = $this->m_level->ubah($params);
		}
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}

	function hapus()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_level->hapus($r['id']);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}

	function getComboLevel()
	{
		$mresult = $this->m_level->getLevel();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}
}