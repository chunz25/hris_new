<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class cuti extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_cuti');
		$this->load->model('m_pegawai');
	}

	function getListCuti()
	{
		$params = array(
			'v_pegawaiid' => null,
			'v_status' => null,
			'v_mulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_POST, 'tglselesai', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_keyword' => ifunsetempty($_POST, 'keyword', ''),
			'v_nstatus' => ifunsetempty($_POST, 'statusid', null),
			'v_usergroupid' => $this->session->userdata('aksesid_siap'),
			'v_lokasiid' => $this->session->userdata('lokasiid'),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);

		$mresult = $this->m_cuti->getListCuti($params);
		echo json_encode($mresult);
	}

	function getDetailCutiPegawai()
	{
		$params = array(
			'v_pegawaiid' => ifunsetemptybase64($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetemptybase64($_POST, 'nourut', null),
			'v_tahun' => ifunsetemptybase64($_POST, 'tahun', null),
		);
		$mresult = $this->m_cuti->getCutiById($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function getDetailPengajuanCuti()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_cuti->getDetailPengajuanCuti($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function getDetailPengajuanCutiHidden()
	{
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$mresult = $this->m_cuti->getDetailPengajuanCutiHidden($pengajuanid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	function approveCuti()
	{
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		$nik = ifunsetempty($_POST, 'nik', null);
		$nama = ifunsetempty($_POST, 'nama', null);
		$tglpermohonan = ifunsetempty($_POST, 'tglpermohonan', null);
		$email = ifunsetempty($_POST, 'email', null);
		$statusid = ifunsetempty($_POST, 'statusid', null);
		//cuti hr db
		$fingerid = ifunsetempty($_POST, 'fingerid', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		//get data from store 
		$detailallcuti = array();
		$detailallcuti = json_decode(ifunsetempty($_POST, 'detailallcuti', null));
		//end
		$newstatusid = null;
		$newstatustext = '';

		// Jika disetujui approval maka ubah statusid = 7
		if ($statusid == '5') {
			$newstatusid = '7';
			$newstatustext = 'Disetujui HR';
		}
		// Jika Pengajuan pembatalan disetujui approval maka ubah statusid = 14
		else if ($statusid == '12') {
			$newstatusid = '14';
			$newstatustext = 'Pengajuan pembatalan disetujui hr';
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_notes' => null,
			'v_hrdid' => $this->session->userdata('pegawaiid'),
		);

		$mresult = $this->m_cuti->updStatusCuti($params);
		$this->m_cuti->setLooping($pegawaiid);

		if ($mresult) {
			$desc = array(
				'nik' => $nik,
				'nama' => $nama,
				'desctription' => 'Pengajuan Cuti pada tanggal ' . $tglpermohonan . ' ' . $newstatustext,
			);

			$sendParams = array(
				'v_jenisnotif' => $newstatustext,
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_siap'),
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '2',
				'v_modul' => null,
			);

			$this->tp_notification->addNotif($sendParams);
			$this->sendMail($nik, $email, $nama, $newstatustext);

			// insert into mesin absensi
			if ($newstatusid == '7') {
				$timezone = "Asia/Jakarta";
				if (function_exists('date_default_timezone_set'))
					date_default_timezone_set($timezone);
				$tglpermohonan = date('Y-m-d H:i:s');

				foreach ($detailallcuti as $r) {
					$cutiid = $r->cutiid;
					if ($cutiid == '4' || $cutiid == '5' || $cutiid == '10' || $cutiid == '12' || $cutiid == '13' || $cutiid == '14') {
						if ($statusid == '5') {
							$params = array(
								'USERID' => $fingerid,
								'STARTSPECDAY' => $r->tglawal,
								'ENDSPECDAY' => $r->tglakhir,
								'DATEID' => $cutiid,
								'YUANYING' => $r->alasancuti,
								'DATE' => $tglpermohonan,
							);
							$this->db = $this->load->database('hrd', TRUE);
							$this->db->query("
								DELETE FROM USER_SPEDAY WHERE USERID = '" . $params['USERID'] . "'
								AND STARTSPECDAY = '" . $params['STARTSPECDAY'] . "'
								AND DATEID = '" . $params['DATEID'] . "'");
							$sql = " 
								INSERT INTO USER_SPEDAY(USERID, STARTSPECDAY, ENDSPECDAY, DATEID, YUANYING, DATE) VALUES (?,?,?,?,?,?) 
								";
							$result = $this->db->query($sql, $params);
						}
					}
				}
			}
			// end insert into mesin absensi

			$result = array('success' => true, 'message' => 'Data berhasil diupdate');
		} else {

			$result = array('success' => false, 'message' => 'Data gagal diupdate');
		}

		echo json_encode($result);
	}

	function approveCuti2()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_tahun' => ifunsetempty($_POST, 'periode', null),
		);
		$tglkerja = ifunsetempty($_POST, 'tglkerja', null);
		$detailcuti = $this->m_cuti->getCutiById($params);
		$nourut = $detailcuti['nourut'];
		$pegawaiid = $detailcuti['pegawaiid'];
		$nik = $detailcuti['nik'];
		$nama = $detailcuti['nama'];
		$tglpermohonan = $detailcuti['tglpermohonan'];
		$statusid = $detailcuti['statusid'];
		$fingerid = $detailcuti['fingerid'];
		$pengajuanid = $detailcuti['pengajuanid'];
		$email = $detailcuti['email'];
		$detailallcuti = $this->m_cuti->getDetailPengajuanCutiHidden($pengajuanid);

		$newstatusid = null;
		$newstatustext = '';

		// Jika disetujui approval maka ubah statusid = 7
		if ($detailcuti['statusid'] == '5') {
			$newstatusid = '7';
			$newstatustext = 'Disetujui HR';
		}
		// Jika Pengajuan pembatalan disetujui approval maka ubah statusid = 14
		else if ($detailcuti['statusid'] == '12') {
			$newstatusid = '14';
			$newstatustext = 'Pengajuan pembatalan disetujui hr';
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_notes' => null,
			'v_hrdid' => $this->session->userdata('pegawaiid'),
		);

		$mresult = $this->m_cuti->updStatusCuti($params);
		$this->m_cuti->setLooping($pegawaiid);

		if ($mresult) {
			$desc = array(
				'nik' => $nik,
				'nama' => $nama,
				'desctription' => 'Pengajuan Cuti pada tanggal ' . $tglpermohonan . ' ' . $newstatustext,
			);

			$sendParams = array(
				'v_jenisnotif' => $newstatustext,
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_siap'),
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '2',
				'v_modul' => null,
			);

			$this->tp_notification->addNotif($sendParams);
			$this->sendMail($nik, $email, $nama, $newstatustext);

			// insert into mesin absensi
			if ($newstatusid == '7') {
				$timezone = "Asia/Jakarta";
				if (function_exists('date_default_timezone_set'))
					date_default_timezone_set($timezone);
				$tglpermohonan = date('Y-m-d H:i:s');

				foreach ($detailallcuti as $r) {
					$cutiid = $r['cutiid'];
					if ($cutiid == '4' || $cutiid == '10' || $cutiid == '12' || $cutiid == '13' || $cutiid == '14') {
						if ($statusid == '5') {
							$params = array(
								'USERID' => $fingerid,
								'STARTSPECDAY' => $r['tglawal'],
								'ENDSPECDAY' => $r['tglakhir'],
								'DATEID' => $cutiid,
								'YUANYING' => $r['alasancuti'],
								'DATE' => $tglpermohonan,
							);
							$this->db = $this->load->database('hrd', TRUE);
							$this->db->query("
								DELETE FROM USER_SPEDAY WHERE USERID = '" . $params['USERID'] . "'
								AND STARTSPECDAY = '" . $params['STARTSPECDAY'] . "'
								AND DATEID = '" . $params['DATEID'] . "'");
							$sql = " 
								INSERT INTO USER_SPEDAY(USERID,STARTSPECDAY,ENDSPECDAY,DATEID,YUANYING,DATE) VALUES (?,?,?,?,?,?) 
								";
							$result = $this->db->query($sql, $params);
						}
					}
				}
			}
			// end insert into mesin absensi

			$result = array('success' => true, 'message' => 'Data berhasil diupdate');
		} else {

			$result = array('success' => false, 'message' => 'Data gagal diupdate');
		}

		echo json_encode($result);
	}

	function rejectCuti()
	{
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		$nik = ifunsetempty($_POST, 'nik', null);
		$nama = ifunsetempty($_POST, 'nama', null);
		$email = ifunsetempty($_POST, 'email', null);
		$tglpermohonan = ifunsetempty($_POST, 'tglpermohonan', null);
		$statusid = ifunsetempty($_POST, 'statusid', null);
		$newstatusid = null;
		$newstatustext = '';

		// Jika Ditolak Approval maka ubah statusid = 6
		if ($statusid == '5') {
			$newstatusid = '8';
			$newstatustext = 'Ditolak HR';
		}
		// Jika Pengajuan pembatalan ditolak approval maka ubah statusid = 15
		else if ($statusid == '12') {
			$newstatusid = '15';
			$newstatustext = 'Pengajuan pembatalan ditolak hr';
		}

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => $newstatusid,
			'v_notes' => ifunsetempty($_POST, 'alasan', null),
			'v_hrdid' => $this->session->userdata('pegawaiid'),
		);

		$mresult = $this->m_cuti->updStatusCuti($params);

		if ($mresult) {
			$desc = array(
				'nik' => $nik,
				'nama' => $nama,
				'desctription' => 'Pengajuan Cuti pada tanggal ' . $tglpermohonan . ' ' . $newstatustext,
			);

			$sendParams = array(
				'v_jenisnotif' => $newstatustext,
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_siap'),
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '2',
				'v_modul' => null,
			);

			$this->tp_notification->addNotif($sendParams);
			$this->sendMail($nik, $email, $nama, $newstatustext);

			$result = array('success' => true, 'message' => 'Data berhasil diupdate');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diupdate');
		}
		echo json_encode($result);
	}

	public function sendMail($nik, $email, $nama, $newstatustext)
	{
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		// SMTP & mail configuration
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'mail.electronic-city.co.id',
			'smtp_port' => 25,
			'smtp_user' => 'hris-ec@electronic-city-internal.co.id',
			'smtp_pass' => 'L0nt0n9',
			'mailtype' => 'html',
			'charset' => 'utf-8'
		);
		if ($email == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to($email);
			//$this->email->bcc('deny.prabawa@electronic-city.co.id');
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Status Notifikasi</th><th>Tanggal Pengajuan</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>' . $newstatustext . '</td><td>' . $tglpermohonan . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Status Cuti HRIS');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}

	public function addPaidHoliday($fingerid, $tglmulai, $tglselesai, $alasancuti, $cutiid)
	{
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');

		$params = array(
			'USERID' => $fingerid,
			'STARTSPECDAY' => $tglmulai,
			'ENDSPECDAY' => $tglselesai,
			'DATEID' => $cutiid,
			'YUANYING' => $alasancuti,
			'DATE' => $tglpermohonan,
		);
		$this->db = $this->load->database('hrd', TRUE);
		$sql = "INSERT INTO USER_SPEDAY(USERID,STARTSPECDAY,ENDSPECDAY,DATEID,YUANYING,DATE) VALUES (?,?,?,?,?,?) ";
		$query = $this->db->query($sql, $params);
	}

	public function download()
	{
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('eservices_upload_dok_path');

		if ($filename == '') {
			echo '<h2>Belum upload file</h2>';
		} else if (file_exists($path . $filename)) {
			$data = file_get_contents($path . $filename);
			force_download($filename, $data);
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}

	public function countLengthCuti()
	{
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$tglmulai = ifunsetempty($_POST, 'tglmulai', null);
		$tglselesai = ifunsetempty($_POST, 'tglselesai', null);

		$mresultHariLibur = $this->m_cuti->getHariLibur();

		$holidays = array();
		foreach ($mresultHariLibur as $key => $value) {
			$holidays[$key] = $value['tgl'];
		}

		$lama_cuti = getJumlahHariKerja($tglmulai, $tglselesai, $holidays);
		$result = array('success' => true, 'data' => $lama_cuti);
		echo json_encode($result);
	}

	function cetakdokumen()
	{
		$params = array(
			'v_mulai' => ifunsetemptybase64($_GET, 'tglmulai', ''),
			'v_selesai' => ifunsetemptybase64($_GET, 'tglselesai', ''),
			'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
			'v_nstatus' => ifunsetemptybase64($_GET, 'statusid', '')
		);
		$mresult = $this->m_cuti->getListCetakCuti($params);

		$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "DAFTARCUTI.xlsx");
		$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
		$TBS->MergeField('header', array());
		$TBS->MergeBlock('rec', $mresult['data']);
		$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "DAFTARCUTI.xlsx");
		$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
	}

	function updStatusCutiKosong()
	{
		$params = array(
			'v_pengajuanid' => ifunsetempty($_POST, 'pengajuanid', null),
			'v_status' => ifunsetempty($_POST, 'statusid', null),
		);
		$mresult = $this->m_cuti->updStatusCutiKosong($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambahkan');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambahkan');
		}
		echo json_encode($result);
	}
}
