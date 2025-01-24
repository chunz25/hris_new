<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_unitkerja extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_unitkerja');
	}

	function get_unitkerja()
	{
		$node = ifunsetempty($_POST, 'node', '');
		$mresult = $this->m_unitkerja->get_unitkerja($node);

		$data = array();
		foreach ($mresult->result_array() as $r) {
			$temp = array();
			$temp['satkerid'] = $r['satkerid'];
			$temp['id'] = $r['id'];
			$temp['text'] = $r['text'];
			$temp['unitkerja'] = $r['unit'];
			$temp['direktorat'] = $r['direktorat'];
			$temp['divisi'] = $r['divisi'];
			$temp['departemen'] = $r['departemen'];
			$temp['seksi'] = $r['seksi'];
			$temp['subseksi'] = $r['subseksi'];
			$temp['kepalanama'] = $r['kepalanama'];

			$mresult2 = $this->m_unitkerja->get_unitkerja($r['id']);
			if ($mresult2->num_rows() > 0) {
				$temp['leaf'] = false;
			} else {
				$temp['leaf'] = true;
			}

			if (empty($node)) {
				$temp['expanded'] = true;
			}

			$data[] = $temp;
		}
		echo json_encode($data);
	}

	function CRUDMasterUnitkerja()
	{
		// die(var_dump($_POST));
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_node' => ifunsetempty($_POST, 'satkerid', '39'),
			'v_text' => ifunsetempty($_POST, 'text', null),
		);

		if ($flag == '1') {
			$mresult = $this->m_unitkerja->tambah($params);
		} else {
			$mresult = $this->m_unitkerja->ubah($params);
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
			$mresult = $this->m_unitkerja->hapus($r['id']);
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
