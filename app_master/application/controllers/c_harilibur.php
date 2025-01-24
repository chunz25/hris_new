<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class c_harilibur extends Master_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_harilibur');
	}

	function getListHariLibur()
	{
		$mresult = $this->m_harilibur->getListHariLibur();
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function crudHariLibur()
	{
		try {
			// Validate and sanitize POST inputs
			$flag = ifunsetempty($_POST, 'flag', '1');
			$tgl = ifunsetempty($_POST, 'tgl', null);
			$keterangan = ifunsetempty($_POST, 'keterangan', null);
			$status = ifunsetempty($_POST, 'status', null);
			$jenis = ifunsetempty($_POST, 'jenisid', null);
			$hariliburid = ifunsetempty($_POST, 'hariliburid', null);

			// Check for required fields
			if (empty($tgl) || empty($keterangan) || empty($status) || empty($jenis)) {
				throw new Exception('Required fields are missing');
			}

			// Prepare the parameters
			$params = array(
				'tgl' => $tgl,
				'keterangan' => $keterangan,
				'status' => $status,
				'jenis' => $jenis,
			);

			// Insert or update based on the flag
			if ($flag == '1') {
				// Add operation
				$mresult = $this->m_harilibur->tambah($params);
				$message = 'Data berhasil ditambahkan';
			} else {
				// Update operation
				if (empty($hariliburid)) {
					throw new Exception('hariliburid is required for updating');
				}

				$params['hariliburid'] = $hariliburid;
				$mresult = $this->m_harilibur->ubah($params);
				$message = 'Data berhasil diubah';
			}

			// Check the result of the database operation
			if ($mresult) {
				$result = array('success' => true, 'message' => $message);
			} else {
				throw new Exception('Database operation failed');
			}
		} catch (Exception $e) {
			// Handle any exceptions that occurred
			$result = array('success' => false, 'message' => $e->getMessage());
		}

		// Output the JSON result
		echo json_encode($result);
	}


	function hapus()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_harilibur->hapus($r['id']);
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