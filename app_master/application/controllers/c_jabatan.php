<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_jabatan extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_jabatan');
	}

	function getmasterjabatan()
	{
		$node = ifunsetempty($_POST, 'node', '');
		$mresult = $this->m_jabatan->getmasterjabatan($node);

		$data = array();
		foreach ($mresult->result_array() as $r) {
			$temp = array();
			$temp['id'] = $r['jabatanid'];
			$temp['text'] = $r['jabatan'];
			$temp['leaf'] = true;
			$data[] = $temp;
		}
		echo json_encode($data);
	}

	function CRUDMasterJabatan()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_node' => ifunsetempty($_POST, 'id', '0'),
			'v_text' => ifunsetempty($_POST, 'text', null),
		);

		if ($flag == '1') {
			$mresult = $this->m_jabatan->tambah($params);
		} else {
			$mresult = $this->m_jabatan->ubah($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diinput');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diinput');
		}
		echo json_encode($result);
	}

	function hapus()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);
		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_jabatan->hapus($r['id']);
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
}