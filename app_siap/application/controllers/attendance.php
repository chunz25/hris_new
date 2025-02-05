<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class attendance extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_attendance');
	}

	function getListAbsensi()
	{
		$user = $this->session->userdata('nik');
		$nik = ifunsetempty($_POST, 'nik', '%');
		$tglstr = ifunsetempty($_POST, 'tglstr', '');
		$tglend = ifunsetempty($_POST, 'tglend', '');
		$tglmulai = ifunsetempty($_POST, 'tglmulai', $tglstr);
		$tglselesai = ifunsetempty($_POST, 'tglselesai', $tglend);
		$start = ifunsetempty($_POST, 'start', 0);
		$limit = ifunsetempty($_POST, 'limit', config_item('PAGESIZE'));

		
		$params = array(
			'username' => $user,
			'nik' => $nik,
			'tglmulai' => $tglmulai,
			'tglselesai' => $tglselesai,
			'v_start' => $start,
			'v_limit' => $limit,
		);
		// die(var_dump($params));

		// Get the attendance data from both methods
		$mresult1 = $this->m_attendance->getDataAbsensiAll($params);
		if (!isset($mresult1['data'])) {
			$mresult1['data'] = null;
			$mresult1['count'] = 0;
		}

		// Combine the data from both results and filter out empty arrays
		$dataAbsen['data'] = (array) $mresult1['data'];
		$dataAbsen['count'] = $mresult1['count'];

		echo json_encode($dataAbsen);
	}

	function cetakdokumen()
	{
		$user = $this->session->userdata('nik');
		$nik = ifunsetempty($_POST, 'nik', '%');
		$tglstr = ifunsetemptybase64($_GET, 'tglstr', '');
		$tglend = ifunsetemptybase64($_GET, 'tglend', '');
		$tglmulai = ifunsetempty($_POST, 'tglmulai', $tglstr);
		$tglselesai = ifunsetempty($_POST, 'tglselesai', $tglend);
		$start = ifunsetempty($_POST, 'start', 0);

		$params = array(
			'username' => $user,
			'nik' => $nik,
			'tglmulai' => $tglmulai,
			'tglselesai' => $tglselesai,
			'v_start' => $start,
			'v_limit' => 100000,
		);

		// Get the attendance data from both methods
		$mresult1 = $this->m_attendance->getDataAbsensiAll($params);
		if (!isset($mresult1['data'])) {
			$mresult1['data'] = null;
			$mresult1['count'] = 0;
		}

		
		// Combine the data from both results and filter out empty arrays
		$dataAbsen['data'] = (array) $mresult1['data'];
		$dataAbsen['count'] = $mresult1['count'];
		// die(var_dump($dataAbsen));

		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_ATTENDANCE.xlsx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $dataAbsen['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_ATTENDANCE.xlsx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}
}
