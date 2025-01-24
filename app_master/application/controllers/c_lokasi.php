<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_lokasi extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_lokasi');
	}

	function getmasterlokasi()
	{
		$node = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_lokasi->getmasterlokasi($node);

		$data = array();
		foreach ($mresult->result_array() as $r) {
			$temp = array();
			$temp['id'] = $r['id'];
			$temp['text'] = $r['text'];

			$mresult2 = $this->m_lokasi->getmasterlokasi($r['id']);
			$temp['leaf'] = true;
			$data[] = $temp;
		}
		echo json_encode($data);
	}

	function getLokasiKerja()
	{
		$mresult = $this->m_lokasi->getLokasiKerja();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function crudLokasi()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'lokasi' => ifunsetempty($_POST, 'text', null),
			'kodelokasi' => ifunsetempty($_POST, 'kodelokasi', null),
			'lokasiid' => ifunsetempty($_POST, 'id', null),
		);

		if ($flag == '1') {
			unset($params['id']);
			$mresult = $this->m_lokasi->tambah($params);
		} else {
			$mresult = $this->m_lokasi->ubah($params);
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
			$mresult = $this->m_lokasi->hapus($r['id']);
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