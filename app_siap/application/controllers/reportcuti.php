<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class reportcuti extends SIAP_Controller
{
	private $warna = array('#406a9b', '#9d403e', '#809948', '#6a5085', '#da9f00', '#3c8da3', '#cd7c38');

	function __construct()
	{
		parent::__construct();
		$this->load->model('m_reportcuti');
		$this->load->model('m_pegawai');
	}

	function getReportListStatusPegawai()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_tahun' => date("Y"),
			'v_nama' => ifunsetempty($_POST, 'nama', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);

		$mresult = $this->m_reportcuti->reportListStatusPegawai($params);
		echo json_encode($mresult);
	}

	function getReportListBatalCutiBersama()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_date' => ifunsetempty($_POST, 'date', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);

		$mresult = $this->m_reportcuti->getReportListBatalCutiBersama($params);
		echo json_encode($mresult);
	}

	function getListCutiPegawai()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_mulai' => ifunsetempty($_POST, 'tglmulai', ''),
			'v_selesai' => ifunsetempty($_POST, 'tglselesai', ''),
			'v_nstatus' => ifunsetempty($_POST, 'statusid', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_reportcuti->getListCutiPegawai($params);
		echo json_encode($mresult);
	}
	
	function getreporthistorycuti()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', ''),
			'v_mulai' => ifunsetempty($_POST, 'tglmulai', ''),
			'v_selesai' => ifunsetempty($_POST, 'tglselesai', ''),
			'v_nstatus' => ifunsetempty($_POST, 'statusid', ''),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE'))
		);
		$mresult = $this->m_reportcuti->getreporthistorycuti($params);
		echo json_encode($mresult);
	}

	function cetakdokumen($opt)
	{
		if ($opt == 'sisacuti') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_tahun' => date("Y"),
				'v_nama' => ifunsetemptybase64($_GET, 'nama', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_reportcuti->reportListStatusPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_SISACUTI_KARYAWAN.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_SISACUTI_KARYAWAN.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'batalcutibersama') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_date' => ifunsetemptybase64($_GET, 'date', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_reportcuti->getReportListBatalCutiBersama($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "REPORT_CUTIBERSAMA.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "REPORT_CUTIBERSAMA.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'cutikaryawan') {
			$params = array(
				'v_mulai' => ifunsetemptybase64($_GET, 'tglmulai', ''),
				'v_selesai' => ifunsetemptybase64($_GET, 'tglselesai', ''),
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_nstatus' => ifunsetemptybase64($_GET, 'statusid', ''),
				'v_start' => '0',
				'v_limit' => '1000000000'
			);
			$mresult = $this->m_reportcuti->getListCutiPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "DAFTARCUTI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "DAFTARCUTI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		}
	}
}
