<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class listapprove extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_approve');
	}

	public function index()
	{
		$this->listapprove();
	}

	public function listapprove()
	{
		$data = array();
		$data['pages'] = "listapprove";
		$content = "listapproval/v_listapproval";

		$this->load->view($content, $data);
	}

	public function getListApprovalCuti()
	{
		$params = array(
			'v_atasanid' => (int) $this->session->userdata('pegawaiid'),
			'v_status' => null,
			'v_mulai' => ifunsetempty($_GET, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_GET, 'tglselesai', null),
			'v_satkerid' => '',
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);

		// die(var_dump($params));

		$mresult = $this->m_approve->getListApprovalCuti($params);
		echo json_encode($mresult);
	}

	function detail()
	{
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
		$data['daftarcuti'] = $mGetDetailPengajuanCuti;
		$data['vharilibur'] = $this->getHariLibur();
		$data['pages'] = "listapprove";

		$this->load->view($content, $data);
	}

	public function approveHadirbulk()
	{
		$this->m_approve->addlogs();
		$params = explode(",", ifunsetempty($_GET, 'vals', null));
		$count = count($params);
		for ($i = 0; $i < $count; $i++) {
			$this->m_approve->approvebulk($params[$i]);
		}
		redirect(site_url() . '/listapprove');
	}

	public function approveAbsensi()
	{
		// $this->m_approve->addlogs();
		$penerima = ifunsetempty($_POST, 'atasanid', null);
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$atasanemail = ifunsetempty($_POST, 'atasanemail', null);
		$name = ifunsetempty($_POST, 'nama', null);
		$username = str_replace('_', ' ', $name);
		$nik = ifunsetempty($_POST, 'nik', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$action = ifunsetempty($_POST, 'action', null);

		$status = null;
		$keterangan = '';
		if ($action == '1') {
			$status = '2';
			$keterangan = 'Disetujui Approval';
		}

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_status' => $status,
			'v_alasan' => null
		);
		// die(var_dump($params));

		$mresult = $this->m_approve->updStatusAbsensi($params);
		if ($mresult) {
			for ($x = 0; $x <= 2; $x++) {
				$desc = array(
					'nik' => $this->session->userdata('nik'),
					'nama' => $this->session->userdata('nama'),
					'desctription' => 'Disetujui approval pengajuan absensi kehadiran pada tanggal ' . $tglpermohonan,
				);
				if ($x == '0') {
					if ($status == '2') {
						$penerima = '928'; //Diisi dengan id HR Agnes Novita
						$atasanemail = 'hr.adm3@electronic-city.co.id';
						$keterangan = 'Disetujui Approval';
					}
				} else if ($x == '1') {
					if ($status == '2') {
						$penerima = '159'; //Diisi dengan id HR Sapti Rahayu
						$atasanemail = 'hr.adm2@electronic-city.co.id';
						$keterangan = 'Disetujui Approval';
					}
				} else if ($x == '2') {
					$penerima = $pegawaiid;
					$atasanemail = $email;
					if ($status == '2') {
						$keterangan = 'Disetujui Approval';
					}
				}
				$params = array(
					'v_jenisnotif' => $keterangan,
					'v_description' => json_encode($desc),
					'v_penerima' => $penerima,
					'v_useridfrom' => $this->session->userdata('userid'),
					'v_usergroupidfrom' => $this->session->userdata('aksesid_absensi'),
					'v_pengirim' => $pegawaiid,
					'v_modulid' => $this->session->userdata('id_absensi'),
					'v_modul' => 'Modul datang telat / pulang cepat',
				);
				$this->m_approve->addNotif($params);
				$this->sendMail($nik, $atasanemail, $username, $keterangan);
			}
			$result = array('success' => true, 'message' => 'Data berhasil dikirim');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dikirim');
		}
		echo json_encode($result);
	}

	public function rejectAbsensi()
	{
		// $this->m_approve->addlogs();
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$atasanemail = ifunsetempty($_POST, 'atasanemail', null);
		$pengajuemail = ifunsetempty($_POST, 'pengajuemail', null);
		$name = ifunsetempty($_POST, 'nama', null);
		$username = str_replace('_', ' ', $name);
		$nik = ifunsetempty($_POST, 'nik', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$action = ifunsetempty($_POST, 'action', null);
		$atasanid = ifunsetempty($_POST, 'atasanid', null);
		$batalalasan = ifunsetempty($_POST, 'batalalasan', null);

		$status = null;
		$keterangan = '';
		if ($action == '2') {
			$status = '3';
			$keterangan = 'Ditolak Approval';
		}

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_status' => $status,
			'v_alasan' => ifunsetempty($_POST, 'batalalasan', null)
		);
		$mresult = $this->m_approve->updStatusAbsensi($params);

		if ($mresult) {
			$desc = array(
				'nik' => $this->session->userdata('nik'),
				'nama' => $this->session->userdata('nama'),
				'desctription' => 'Ditolak pengajuan absensi kehadiran pada tanggal ' . $tglpermohonan,
			);
			$params = array(
				'v_jenisnotif' => 'Ditolak Pengajuan Absensi',
				'v_description' => json_encode($desc),
				'v_penerima' => $pegawaiid,
				'v_useridfrom' => $this->session->userdata('userid'),
				'v_usergroupidfrom' => $this->session->userdata('aksesid_absensi'),
				'v_pengirim' => $atasanid,
				'v_modulid' => '10',
				'v_modul' => 'Modul datang telat / pulang cepat',
			);
			$this->m_approve->addNotif($params);
			$this->rejectsendMail($nik, $atasanemail, $pengajuemail,  $username, $keterangan, $batalalasan);

			$result = array('success' => true, 'message' => 'Data berhasil dikirim');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dikirim');
		}
		echo json_encode($result);
	}

	public function sendMail($nik, $atasanemail, $username, $keterangan, $batalalasan)
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
			// $this->email->to('khotama.brata@electronic-city.co.id'); // DIISI OLEH ADMIN HRD hr.adm2@electronic-city.co.id
			$this->email->to('hr.adm2@electronic-city.co.id');
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Status Notifikasi</th><th>Tanggal Pengajuan</th><th>Link</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $username . '</td><td>' . $keterangan . '</td><td>' . $tglpermohonan . '</td><td>' . $link . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Status Pengajuan Form Kehadiran');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}

	public function rejectsendMail($nik, $atasanemail, $pengajuemail,  $username, $keterangan, $batalalasan)
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
		if ($pengajuemail == null) {
			// Tidak mengirim email apabila email id kosong
		} else {
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			// $this->email->to('khotama.brata@electronic-city.co.id');
			$this->email->to($pengajuemail); //DIISI OLEH USER PENGAJU
			$this->email->from('hris-ec@electronic-city-internal.co.id');
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Status Notifikasi</th><th>Tanggal Pengajuan</th><th>Alasan</th><th>Link</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $username . '</td><td>' . $keterangan . '</td><td>' . $tglpermohonan . '</td><td>' . $batalalasan . '</td><td>' . $link . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Status Pengajuan Form Kehadiran');
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

	public function download()
	{
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('absensi_upload_dok_path');

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
