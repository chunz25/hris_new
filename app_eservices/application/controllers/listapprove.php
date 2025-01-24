<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class listapprove extends EServices_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_approve');
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->listapprove();
	}

	public function listapprove()
	{
		// $this->m_pengajuan->addlogs();
		$data = array();
		$data['pages'] = "listapprove";
		$content = "listapproval/v_listapproval";

		$this->load->view($content, $data);
	}

	public function getListApprovalCuti()
	{
		// $this->m_pengajuan->addlogs();
		$params = array(
			'v_atasanid' => $this->session->userdata('pegawaiid'),
			'v_status' => null,
			'v_mulai' => ifunsetempty($_GET, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_GET, 'tglselesai', null),
			'v_satkerid' => '',
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);

		$mresult = $this->m_approve->getListApprovalCuti($params);
		echo json_encode($mresult);
	}

	function detail()
	{
		// $this->m_pengajuan->addlogs();
		$pegawaiid = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_GET, 'nourut', null);
		$tahun = date("Y");

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_tahun' => $tahun,
		);
		$mGetDetailCuti = $this->m_approve->getCutiById($params);

		$pengajuanid = $mGetDetailCuti['pengajuanid'];
		$mGetDetailPengajuanCuti = $this->m_approve->getDetailPengajuanCuti($pengajuanid);

		$data = array();
		$content = "listapproval/detail/v_listapproval";

		$data['info'] = $mGetDetailCuti;
		$data['vsisacuti'] = $this->m_pengajuan->getInfoSisaCuti($pegawaiid);
		$data['daftarcuti'] = $mGetDetailPengajuanCuti;
		$data['vjeniscuti'] = $this->m_approve->getComboJenisCuti();
		$data['infopegawai'] = $this->getInfoPegawai();
		$data['pages'] = "listapprove";

		$this->load->view($content, $data);
	}

	public function approveCutibulk()
	{
		// $this->m_pengajuan->addlogs();
		$params = explode(",", ifunsetempty($_GET, 'vals', null));
		$count = count($params);
		for ($i = 0; $i < $count; $i++) {
			$this->m_approve->approvebulk($params[$i]);
		}
		redirect(site_url() . '/listapprove');
	}

	public function approveCuti()
	{
		// $this->m_pengajuan->addlogs();
		$useraction = ifunsetempty($_POST, 'useraction', null);
		$action = ifunsetempty($_POST, 'action', null);
		$penerima = ifunsetempty($_POST, 'atasan2id', null);
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$atasanemail = ifunsetempty($_POST, 'atasan2email', null);
		$email = ifunsetempty($_POST, 'email', null);
		$name = ifunsetempty($_POST, 'nama', null);
		$username = str_replace('_', ' ', $name);
		$nik = ifunsetempty($_POST, 'nik', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');

		// $status = null;
		// $keterangan = '';
		if ($useraction == '12') {
			if ($action == '1') {
				$status = '3';
				$keterangan = 'Pengajuan Cuti';
			} else {
				$status = '10';
				$keterangan = 'Pengajuan Batal Cuti';
			}
		} else {
			if ($action == '1') {
				$status = '5';
				$keterangan = 'Pengajuan Cuti';
			} else {
				$status = '12';
				$keterangan = 'Pengajuan Batal Cuti';
			}
		}

		for ($x = 0; $x <= 2; $x++) {
			// Notifikasi saat approve cuti
			$desc = array(
				'nik' => $this->session->userdata('nik'),
				'nama' => $this->session->userdata('nama'),
				'desctription' => 'Pengajuan Cuti pada tanggal ' . $tglpermohonan,
			);
			if ($x == '0') {
				if ($status == '5' || $status == '12') {
					$penerima = 928; //Diisi dengan id HR Agnes Novita
					$atasanemail = 'hr.adm3@electronic-city.co.id';
					$keterangan = 'Disetujui Approval';
				}
			} else if ($x == '1') {
				if ($status == '5' || $status == '12') {
					$penerima = 159; //Diisi dengan id HR Sapti Rahayu
					$atasanemail = 'hr.adm2@electronic-city.co.id';
					$keterangan = 'Disetujui Approval';
				}
			} elseif ($x == '2') {
				$penerima = $pegawaiid;
				$atasanemail = $email;
				if ($status == '3' || $status == '10') {
					$keterangan = 'Disetujui Verifikator';
				} else if ($status == '5' || $status == '12') {
					$keterangan = 'Disetujui Approval';
				}
			}

			$paramsnotif = array(
				'v_jenisnotif' => $keterangan,
				'v_description' => json_encode($desc),
				'v_penerima' => $penerima,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
				'v_pengirim' => $pegawaiid,
				'v_modulid' => '2',
				'v_modul' => null,
			);
			$this->tp_notification->addNotif($paramsnotif);
			$this->sendMail($nik, $atasanemail, $username, $keterangan);
		}

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_status' => $status,
			'v_notes' => null,
			'v_hrd' => null
		);

		// die(var_dump($params));

		$mresult = $this->m_approve->updStatusCuti($params);
		if ($mresult) {

			$result = array('success' => true, 'message' => 'Data berhasil diubah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diubah');
		}
		echo json_encode($result);
	}

	public function rejectCuti()
	{
		// $this->m_pengajuan->addlogs();
		$useraction = ifunsetempty($_POST, 'useraction', null);
		$action = ifunsetempty($_POST, 'action', null);
		$penerima = ifunsetempty($_POST, 'atasan2id', null);
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$atasanemail = ifunsetempty($_POST, 'atasan2email', null);
		$email = ifunsetempty($_POST, 'email', null);
		$name = ifunsetempty($_POST, 'nama', null);
		$username = str_replace('_', ' ', $name);
		$nik = ifunsetempty($_POST, 'nik', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$status = null;

		$keterangan = '';
		if ($useraction == '12') {
			if ($action == '1') {
				$status = '4';
			} else if ($action == '2') {
				$status = '11';
			}
		} else if ($useraction == '13') {
			if ($action == '1') {
				$status = '6';
			} else if ($action == '2') {
				$status = '13';
			}
		}

		// Notifikasi saat reject cuti
		$desc = array(
			'nik' => $this->session->userdata('nik'),
			'nama' => $this->session->userdata('nama'),
			'desctription' => 'Tolak Pengajuan Cuti pada tanggal ' . $tglpermohonan,
		);
		$penerima = $pegawaiid;
		$atasanemail = $email;
		if ($status == '4' || $status == '11') {
			$keterangan = 'Ditolak Verifikator';
		} else if ($status == '6' || $status == '13') {
			$keterangan = 'Ditolak Approval';
		}

		$paramsnotif = array(
			'v_jenisnotif' => $keterangan,
			'v_description' => json_encode($desc),
			'v_penerima' => $penerima,
			'v_useridfrom' => $this->session->userdata('userid'),
			'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
			'v_pengirim' => $pegawaiid,
			'v_modulid' => '2',
			'v_modul' => null,
		);
		$this->tp_notification->addNotif($paramsnotif);
		$this->sendMail($nik, $atasanemail, $username, $keterangan);

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_status' => $status,
			'v_notes' => ifunsetempty($_POST, 'alasan', null),
			'v_hrd' => null
		);
		$mresult = $this->m_approve->updStatusCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diubah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diubah');
		}
		echo json_encode($result);
	}

	public function sendMail($nik, $atasanemail, $username, $keterangan)
	{
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		$link = 'http://10.101.0.85/hris/index.php/login';
		// SMTP & mail configuration
		$config = array(
			'protocol'  => 'smtp',
			'smtp_host' => 'mail.electronic-city.co.id',
			'smtp_port' => 25,
			'smtp_user' => 'hris-ec@electronic-city-internal.co.id',
			'smtp_pass' => 'L0nt0n9',
			'mailtype'  => 'html',
			'charset'   => 'utf-8'
		);
		if ($atasanemail == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to($atasanemail); //hr.adm2@electronic-city.co.id
			//$this->email->bcc('deny.prabawa@electronic-city.co.id');
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Status Notifikasi</th><th>Tanggal Pengajuan</th><th>Link</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $username . '</td><td>' . $keterangan . '</td><td>' . $tglpermohonan . '</td><td>' . $link . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Status Cuti HRIS');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}

	public function getHariLibur()
	{
		$mresult = $this->m_approve->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_approve->getInfoPegawai($params);
		return $mresult['firstrow'];
	}

	public function download()
	{
		// $this->m_pengajuan->addlogs();
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
}
