<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class History extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_history');
		$this->load->model('M_pengajuan');
		$this->load->helper('url'); // Load the URL helper for redirection
	}

	public function index()
	{
		$this->historyabsensi();
	}

	public function historyabsensi()
	{
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['pages'] = "history";
		$this->load->view("history/v_historycutihome", $data);
	}

	public function getListPegawai()
	{
		try {
			$nik = null;
			$usergroupid = $this->session->userdata('akses_absensi');
			$satkerid = $this->session->userdata('satkerdisp');

			if ($usergroupid == 'pegawai') {
				$nik = $this->session->userdata('nik');
				$satkerid = '';
			}

			$params = array(
				'v_satkerid' => $satkerid,
				'v_nik' => $nik,
				'v_nama' => '',
				'v_statuspegawai' => '',
				'v_jeniskelamin' => '',
				'v_tglmulai' => '',
				'v_tglselesai' => '',
				'v_usergroupid' => $usergroupid,
				'v_lokasiid' => $this->session->userdata('lokasiid'),
				'v_keyword' => ifunsetempty($_GET, 'keyword', ''),
				'v_start' => ifunsetempty($_GET, 'start', 0),
				'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
			);

			$mresult = $this->m_history->getListPegawai($params);
			echo json_encode($mresult);
		} catch (Exception $e) {
			log_message('error', 'getListPegawai Error: ' . $e->getMessage());
			echo json_encode(array('success' => false, 'message' => 'An error occurred while fetching data.'));
		}
	}

	public function detaillistkehadiran()
	{
		try {
			// $this->m_pengajuan->addlogs();
			$data['vpegawaiid'] = ifunsetemptybase64($_GET, 'pegawaiid', null);
			$data['vstatuscuti'] = $this->m_history->getComboStatusCuti();
			$data['pages'] = "history";
			$this->load->view("history/v_historycuti", $data);
		} catch (Exception $e) {
			log_message('error', 'detaillistkehadiran Error: ' . $e->getMessage());
			show_error('An error occurred while processing your request.');
		}
	}

	public function getListHistoryKehadiran()
	{
		try {
			$pegawaiid = ifunsetempty($_GET, 'pegawaiid', null);
			$satkerid = $this->session->userdata('satkerdisp');

			$params = array(
				'v_pegawaiid' => (int) $pegawaiid,
				'v_status' => ifunsetempty($_GET, 'status', null),
				'v_mulai' => isset($_GET['tglmulai']) ? $_GET['tglmulai'] : null,
				'v_selesai' => isset($_GET['tglselesai']) ? $_GET['tglselesai'] : null,
				'v_satkerid' => $satkerid,
				'v_keyword' => NULL,
				'v_nstatus' => NULL,
				'v_usergroupid' => NULL,
				'v_lokasiid' => 1,
				'v_start' => isset($_GET['start']) ? $_GET['start'] : 0,
				'v_limit' => config_item('PAGESIZE'),
			);

			$mresult = $this->m_history->getListHistoryKehadiran($params);

			// die(var_dump($mresult));
			echo json_encode($mresult);
		} catch (Exception $e) {
			log_message('error', 'getListHistoryKehadiran Error: ' . $e->getMessage());
			echo json_encode(array('success' => false, 'message' => 'An error occurred while fetching history.'));
		}
	}

	public function deleteAbsen()
	{
		try {
			// Fetch POST data safely
			$pegawaiid = $this->input->post('pegawaiid');
			$pengajuanid = $this->input->post('pengajuanid');

			// Check if required parameters are provided
			if (empty($pegawaiid) || empty($pengajuanid)) {
				throw new Exception('Invalid input: Pegawai ID or Pengajuan ID is missing.');
			}

			// Prepare parameters for deletion
			$params = array(
				'v_pegawaiid' => $pegawaiid,
				'v_pengajuanid' => $pengajuanid
			);

			// Call the delete function
			$mresult = $this->m_history->deleteAbsensi($params);

			// Prepare the response based on the result
			$response = array(
				'success' => $mresult ? true : false,
				'message' => $mresult ? 'Data berhasil dihapus' : 'Data gagal dihapus'
			);

			// Send JSON response
			echo json_encode($response);

		} catch (Exception $e) {
			// Log the error and return a failure response
			log_message('error', 'deleteCuti Error: ' . $e->getMessage());
			echo json_encode(array(
				'success' => false,
				'message' => 'An error occurred while deleting the cuti: ' . $e->getMessage()
			));
		}
	}


	public function download()
	{
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('absensi_upload_dok_path');

		if (empty($filename)) {
			echo '<h2>Belum upload file</h2>';
		} elseif (file_exists($path . $filename)) {
			$data = file_get_contents($path . $filename);
			force_download($filename, $data);
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}

}
