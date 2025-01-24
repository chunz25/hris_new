<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class report extends SIAP_Controller
{
	private $warna = array('#406a9b', '#9d403e', '#809948', '#6a5085', '#da9f00', '#3c8da3', '#cd7c38');

	function __construct()
	{
		parent::__construct();
		$this->load->model('m_report');
		$this->load->model('m_pegawai');
	}

	/* Report by Divisi */
	function getGraphByDivisi()
	{
		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->statistikDivisi($satkerid);

		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function getReportListDivisi()
	{

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', NULL),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListDivisi($params);
		echo json_encode($mresult);
	}
	/* Report by Divisi */

	/* Report By Status Pegawai */
	function getGraphByStatusPegawai()
	{
		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->statistikStatusPegawai($satkerid);
		echo json_encode($mresult);
	}

	function getReportListStatusPegawai()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_statuspegawaiid' => ifunsetempty($_POST, 'statuspegawaiid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);

		$mresult = $this->m_report->reportListStatusPegawai($params);
		echo json_encode($mresult);
	}
	/* Report By Status Pegawai */

	/* Report By SDM */
	function getReportSDM()
	{

		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->getReportSDM($satkerid);
		echo json_encode($mresult);
	}

	function getReportListSDM()
	{
		$golongan = ifunsetempty($_POST, 'levelid', null);
		$v_golongan = '';
		if ($golongan == 'bod') {
			$v_golongan = 'bod';
		} else {
			if ($golongan == null) {
				$v_golongan = 'null';
			} else {
				$v_golongan = $golongan;
			}
		}
		$params = array(
			'v_golongan' => $v_golongan,
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListSdm($params);
		echo json_encode($mresult);
	}
	/* Report By SDM */

	/* Report by Location */
	function getReportListLocationByID()
	{
		$params = array(
			'v_lokasiid' => ifunsetempty($_POST, 'lokasiid', null),
		);
		$mresult = $this->m_report->getLokasiByID($params);
		echo json_encode($mresult);
	}

	function getReportListLocation()
	{

		$params = array(
			'v_lokasiid' => ifunsetempty($_POST, 'lokasiid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListLocation($params);
		echo json_encode($mresult);
	}
	/* Report by Location */

	/* Report by Level */
	function getGraphByLevel()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
		);

		$mresult = $this->m_report->getGraphByLevel($params);
		echo json_encode($mresult);
	}

	function getReportListLevel()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_levelid' => ifunsetempty($_POST, 'levelid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListLevel($params);
		echo json_encode($mresult);
	}
	/* Report by Level */

	/* Report by New Hired & Turn Over */
	function getGraphByKetPegawai()
	{

		$bulan = ifunsetempty($_POST, 'bulan', null);
		$bulan = $bulan == '' ? '-' : $bulan;

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_month' => $bulan,
			'v_years' => ifunsetempty($_POST, 'tahun', date("Y")),
		);

		$mresult = $this->m_report->getGraphByKetPegawai($params);
		echo json_encode($mresult);
	}

	function getReportListKetPegawai()
	{
		$bulan = ifunsetempty($_POST, 'bulan', null);
		$bulan = $bulan == '' ? '-' : $bulan;
		$ketstatus = ifunsetempty($_POST, 'ketstatus', null);
		$ketstatus = empty($ketstatus) ? null : ((int) $ketstatus);

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_month' => $bulan,
			'v_years' => ifunsetempty($_POST, 'tahun', date("Y")),
			'v_ketstatus' => $ketstatus,
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);

		$mresult = $this->m_report->getReportListKetPegawai($params);
		echo json_encode($mresult);
	}
	/* Report by New Hired & Turn Over */

	/* Report by Remind of Contract */
	function getReportEndOfContract()
	{

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getReportEndOfContract($params);
		echo json_encode($mresult);
	}
	/* Report by Remind of Contract */

	/* Report by Gender */
	function getGraphByJenisKelamin()
	{
		$satkerid = ifunsetempty($_POST, 'satkerid', '');
		$mresult = $this->m_report->statistikJenisKelamin($satkerid);
		echo json_encode($mresult);
	}

	function getReportListJenisKelamin()
	{

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->reportListJenisKelamin($params);
		echo json_encode($mresult);
	}
	/* Report by Gender */

	/* Report by Mutasi Promosi */
	function getMutasiPromosi()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getMutasiPromosi($params);
		echo json_encode($mresult);
	}
	/* Report by Mutasi Promosi */

	/* Report by Usia */
	function getGraphByUsiaPegawai()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '')
		);
		$mresult = $this->m_report->statistikUsiaPegawai($params);
		echo json_encode($mresult);
	}

	function getReportListUsiaPegawai()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_labelid' => ifunsetempty($_POST, 'labelid', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);

		$mresult = $this->m_report->reportListUsiaPegawai($params);
		echo json_encode($mresult);
	}
	/* Report by Usia */

	/* Report by Kader */
	function getReportListKaderPegawai()
	{

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_levelid' => ifunsetempty($_POST, 'level', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasi', null),
			'v_start' => 0,
			'v_limit' => 1000000
		);
		$mresult = $this->m_report->getReportListKader($params);
		echo json_encode($mresult);
	}
	/* Report by Kader */

	/* Report by Kader Group */
	function getReportListKaderGroup()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_levelid' => ifunsetempty($_POST, 'level', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasi', null),
			'v_start' => 0,
			'v_limit' => 1000000
		);
		$mresult = $this->m_report->getReportListKaderGroup($params);
		echo json_encode($mresult);
	}
	/* Report by Kader Group */

	/* Report by Ultah */
	function getReportUlangtahun()
	{
		$month = date('m');
		$bulan = ifunsetempty($_POST, 'bulan', 0);
		$hari = ifunsetempty($_POST, 'hari', 0);

		$a = '';
		if ($bulan != '') {
			$a = $bulan;
		} else {
			$a = $month;
		}

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_hari' => ifunsetempty($_POST, 'hari', '01'),
			'v_bulan' => $a,
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getReportUlangtahun($params);
		echo json_encode($mresult);
	}
	/* Report by Ultah */

	/* Cetak Document */
	function cetakdokumen($opt)
	{

		if ($opt == 'endofcontract') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportEndOfContract($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "ENDOFCONTRACT.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "ENDOFCONTRACT.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'statuspegawai') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_statuspegawaiid' => ifunsetemptybase64($_GET, 'statuspegawaiid', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListStatusPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_STATUS_PEGAWAI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_STATUS_PEGAWAI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'jeniskelamin') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_jeniskelamin' => ifunsetemptybase64($_GET, 'jeniskelamin', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListJenisKelamin($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_GENDER.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_GENDER.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'ketstatus') {
			$bulan = ifunsetemptybase64($_GET, 'bulan', null);
			$bulan = $bulan == '' ? '-' : $bulan;
			$ketstatus = ifunsetemptybase64($_GET, 'ketstatus', null);
			$ketstatus = empty($ketstatus) ? null : ((int) $ketstatus);

			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_month' => $bulan,
				'v_years' => ifunsetemptybase64($_GET, 'tahun', date("Y")),
				'v_ketstatus' => $ketstatus,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);

			$mresult = $this->m_report->getReportListKetPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_KETSTATUS.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_KETSTATUS.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bylevel') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_levelid' => ifunsetemptybase64($_GET, 'levelid', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListLevel($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_LEVEL.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_LEVEL.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bysdm') {
			$satkerid = ifunsetemptybase64($_GET, 'satkerid', '');
			$golongan = ifunsetemptybase64($_GET, 'levelid', null);
			$v_golongan = '';
			if ($golongan == 'bod') {
				$v_golongan = 'bod';
			} else {
				if ($golongan == null) {
					$v_golongan = 'null';
				} else {
					$v_golongan = $golongan;
				}
			}
			$params = array(
				'v_golongan' => $v_golongan,
				'v_satkerid' => $satkerid,
				'v_start' => 0,
				'v_limit' => 100000000000000
			);
			$mresult_peg = $this->m_report->reportListSdm($params);
			$mresult = $this->m_report->getReportSDM($satkerid);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_SDM.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$TBS->MergeBlock('rec_peg', $mresult_peg['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_SDM.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bylocation') {
			$params = array(
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasiid', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListLocation($params);
			$mresultid = $this->m_report->getLokasiByID($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_LOCATION.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec_id', $mresultid['data']);
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_LOCATION.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'bydivisi') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListDivisi($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_DIVISI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_DIVISI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'actingas') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);

			$mresult = $this->m_report->getReportActingAsBySatker($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_ACTINGAS.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec_as', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_ACTINGAS.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'mutasipromosi') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);

			$mresult = $this->m_report->getMutasiPromosi($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_MUTASIPROMOSI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec_as', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_MUTASIPROMOSI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportusia') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_labelid' => ifunsetemptybase64($_GET, 'labelid', null),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->reportListUsiaPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_USIA_PEGAWAI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_USIA_PEGAWAI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportkader') { //reportkader chunz
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_levelid' => ifunsetemptybase64($_GET, 'level', null),
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasi', null),
				'v_pegawaiid' => $pegawaiid,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportListKader($params);
			$path = config_item('siap_upload_foto_path');
			$objPHPExcel = PHPExcel_IOFactory::load(config_item("siap_tpl_path") . "REPORT_KADER.xlsx"); //Template Asli jangan tiban

			// Tambah Gambar Pegawai
			for ($img = 0; $img < count($mresult['data']); $img++) {
				$cell = "B" . (9 + (5 * $img));
				$foto = $mresult['data'][$img]['foto'];
				$image = '';
				if (!empty($foto)) {
					$image = $path . $foto;
				} else {
					$image = $path . 'no_image.jpg';
				}

				// Tambah Gambar Pegawai
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setPath($image);
				$objDrawing->setCoordinates($cell);
				$objDrawing->setResizeProportional(true);
				$objDrawing->setWidth(165);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			}

			$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(config_item("siap_tpl_path") . "kader.xlsx");

			//Cetak Data to Excel
			$tempexcel = config_item("siap_tpl_path") . "kader.xlsx";
			$TBS = new clsTinyButStrong;
			$TBS->Plugin(TBS_INSTALL, 'clsOpenTBS');
			$TBS->LoadTemplate($tempexcel);
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('kader', 'REPORT_KADER_' . date('Y-m-d'), "kader.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportkadergroup') { //reportkadergroup chunz
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_levelid' => ifunsetemptybase64($_GET, 'level', null),
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasi', null),
				'v_pegawaiid' => $pegawaiid,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportListKader($params);
			$path = config_item('siap_upload_foto_path');
			$objPHPExcel = PHPExcel_IOFactory::load(config_item("siap_tpl_path") . "REPORT_KADER_GROUP.xlsx"); //Template Asli jangan tiban

			// Tambah Gambar Pegawai
			for ($img = 0; $img < count($mresult['data']); $img++) {
				$cell = "C" . (3 + $img);
				$foto = $mresult['data'][$img]['foto'];
				$image = '';
				if (!empty($foto)) {
					$image = $path . $foto;
				} else {
					$image = $path . 'no_image.jpg';
				}

				// Tambah Gambar Pegawai
				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setPath($image);
				$objDrawing->setCoordinates($cell);
				$objDrawing->setResizeProportional(true);
				$objDrawing->setWidth(165);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			}

			//var_dump($image);

			$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(config_item("siap_tpl_path") . "kadergroup.xlsx");

			//Cetak Data to Excel
			$tempexcel = config_item("siap_tpl_path") . "kadergroup.xlsx";
			$TBS = new clsTinyButStrong;
			$TBS->Plugin(TBS_INSTALL, 'clsOpenTBS');
			$TBS->LoadTemplate($tempexcel);
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('kadergroup', 'REPORT_KADER_' . date('Y-m-d'), "kadergroup.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportkaderold') {
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);

			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', '01'),
				'v_levelid' => ifunsetemptybase64($_GET, 'level', null),
				'v_lokasiid' => ifunsetemptybase64($_GET, 'lokasi', null),
				'v_pegawaiid' => $pegawaiid,
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportListKader($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_KADER_OLD.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_KADER_OLD.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'reportrealisasi') {
			$pegawaiid = ifunsetemptybase64($_GET, 'fingerid', null);

			$params = array(
				'v_tahun' => '2021',
			);
			$mresult = $this->m_report->getReportRealisasi($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_PERDIN_REALISASI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_PERDIN_REALISASI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'ulangtahun') {

			$month = date('m');
			$bulan = ifunsetemptybase64($_GET, 'bulan', 0);
			$hari = ifunsetemptybase64($_GET, 'hari', 0);

			$a = '';
			if ($bulan != '') {
				$a = $bulan;
			} else {
				$a = $month;
			}

			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_hari' => ifunsetemptybase64($_GET, 'hari', '01'),
				'v_bulan' => $a,
				'v_start' => ifunsetemptybase64($_GET, 'start', 0),
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_report->getReportUlangtahun($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_ULANG_TAHUN.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_ULANG_TAHUN.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		}
	}
	/* Cetak Document */


	/* Report Acting As Jangan Hapus
	function getReportActingAs()
	{

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
		);
		$mresult = $this->m_report->getReportActingAs($params);
		echo json_encode($mresult);
	}

	function getReportActingAsBySatker()
	{

		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', '01'),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_report->getReportActingAsBySatker($params);
		echo json_encode($mresult);
	}
	Report Acting As Jangan Hapus */
}
