<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class history extends EServices_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_history');
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->historycuti();
	}

	public function historycuti()
	{
		// $this->m_pengajuan->addlogs();
		$data = array();
		$data['vpegawaiid'] = $this->session->userdata('pegawaiid');
		$data['vstatuscuti'] = $this->m_history->getComboStatusCuti();
		$data['pages'] = "history";
		$content = "history/v_historycutihome";

		$this->load->view($content, $data);
	}

	function detaillistcuti()
	{
		// $this->m_pengajuan-> ();
		$data = array();
		$data['vpegawaiid'] = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$data['vstatuscuti'] = $this->m_history->getComboStatusCuti();
		$data['pages'] = "history";
		$content = "history/v_historycuti";

		$this->load->view($content, $data);
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
		$mGetDetailCuti = $this->m_history->getCutiById($params);
		
		$pengajuanid = $mGetDetailCuti['pengajuanid'];
		$mGetDetailPengajuanCuti = $this->m_history->getDetailPengajuanCuti($pengajuanid);
		$content = "history/detail/v_historycuti";
		
		$data['info'] = $mGetDetailCuti;
		$data['vsisacuti'] = $this->m_pengajuan->getInfoSisaCuti($pegawaiid);
		$data['daftarcuti'] = $mGetDetailPengajuanCuti;
		$data['vharilibur'] = $this->getHariLibur();
		$data['vjeniscuti'] = $this->m_history->getComboJenisCuti();
		$data['infopegawai'] = $this->getInfoPegawai();
		$data['vinfoatasan'] = $this->infoAtasan();
		$data['pages'] = "history";
		$this->load->view($content, $data);
	}

	public function getListPegawai()
	{
		// $this->m_pengajuan->addlogs();
		$nik = null;
		$usergroupid = $this->session->userdata('aksesid_eservices');
		$satkerid = $this->session->userdata('satkerdisp');

		if ($usergroupid == '11') {
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
	}

	public function getListHistoryCuti()
	{
		// $this->m_pengajuan->addlogs();
		$pegawaiid = ifunsetempty($_GET, 'pegawaiid', null);
		$usergroupid = $this->session->userdata('aksesid_eservices');
		$satkerid = $this->session->userdata('satkerdisp');

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_status' => null,
			'v_mulai' => ifunsetempty($_GET, 'tglmulai', null),
			'v_selesai' => ifunsetempty($_GET, 'tglselesai', null),
			'v_satkerid' => $satkerid,
			'v_keyword' => ifunsetempty($_GET, 'keyword', ''),
			'v_nstatus' => ifunsetempty($_GET, 'nstatus', ''),
			'v_usergroupid' => $usergroupid,
			'v_lokasiid' => $this->session->userdata('lokasiid'),
			'v_start' => ifunsetempty($_GET, 'start', 0),
			'v_limit' => ifunsetempty($_GET, 'limit', config_item('PAGESIZE')),
		);
		$mresult = $this->m_history->getListHistoryCuti($params);
		echo json_encode($mresult);
	}

	public function deleteCuti()
	{
		// $this->m_pengajuan->addlogs();
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$verifikator = ifunsetempty($_POST, 'verifikatorid', null);
		$approval = ifunsetempty($_POST, 'approvalid', null);
		$atasan1email = ifunsetempty($_POST, 'verifikatoremail', null);
		$atasan2email = ifunsetempty($_POST, 'approvallemail', null);

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_pengajuanid' => ifunsetempty($_POST, 'pengajuanid', null),
		);

		$mresult = $this->m_history->deleteCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);

		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$atasanid = '';
		$atasanemail = '';

		if ($verifikator == '') {
			$atasanid = $approval;
			$atasanemail = $atasan2email;
		} else {
			$atasanid = $verifikator;
			$atasanemail = $atasan1email;
		}
		$keterangan = 'Cancel cuti';
		$desc = array(
			'nik' => $this->session->userdata('nik'),
			'nama' => $this->session->userdata('nama'),
			'desctription' => 'Cancel Cuti pada tanggal ' . $tglpermohonan,
		);

		$params = array(
			'v_jenisnotif' => 'Cancel Cuti',
			'v_description' => json_encode($desc),
			'v_penerima' => $atasanid,
			'v_useridfrom' => $this->session->userdata('userid'),
			'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
			'v_pengirim' => $pegawaiid,
			'v_modulid' => '2',
			'v_modul' => null,
		);
		$this->tp_notification->addNotif($params);
		$this->sendMail($atasanemail, $keterangan);
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


	public function getHariLibur()
	{
		$mresult = $this->m_history->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function batalCuti()
	{
		// $this->m_pengajuan->addlogs();
		$pegawaiid = $this->session->userdata('pegawaiid');
		$batalCuti = ifunsetempty($_POST, 'alasan', null);
		$pengajuanid = ifunsetempty($_POST, 'pengajuanid', null);
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$verifikator = ifunsetempty($_POST, 'verifikatorid', null);
		$approval = ifunsetempty($_POST, 'approvalid', null);
		$atasan1email = ifunsetempty($_POST, 'verifikatoremail', null);
		$atasan2email = ifunsetempty($_POST, 'approvallemail', null);
		$atasanid = '';
		$atasanemail = '';
		$keterangan = 'Pengajuan Batal cuti';

		if ($verifikator == '') {
			$atasanid = $approval;
			$atasanemail = $atasan2email;
		} else {
			$atasanid = $verifikator;
			$atasanemail = $atasan1email;
		}

		$params = array(
			'pengajuanid' => $pengajuanid,
			'status' => "9",
			'atasan1' => $verifikator,
			'atasan2' => $approval,
			'tglpermohonan' => $tglpermohonan,
		);
		
		$this->m_history->addAlasan($batalCuti, $pengajuanid);
		$mresult = $this->m_history->updStatusCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diubah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diubah');
		}
		echo json_encode($result);

		$desc = array(
			'nik' => $this->session->userdata('nik'),
			'nama' => $this->session->userdata('nama'),
			'desctription' => 'Pengajuan Batal Cuti pada tanggal ' . $tglpermohonan,
		);

		$params = array(
			'v_jenisnotif' => 'Pengajuan Batal Cuti',
			'v_description' => json_encode($desc),
			'v_penerima' => $atasanid,
			'v_useridfrom' => $this->session->userdata('userid'),
			'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
			'v_pengirim' => $pegawaiid,
			'v_modulid' => '2',
			'v_modul' => null,
		);
		$this->tp_notification->addNotif($params);
		$this->sendMail($atasanemail, $keterangan);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_history->getInfoPegawai($params);
		return $mresult['firstrow'];
	}

	public function sendMail($atasanemail, $keterangan)
	{
		$nik = $this->session->userdata('nik');
		$nama = $this->session->userdata('nama');
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
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Jenis Notifikasi</th><th>Tanggal Pengajuan</th><th>Link</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>' . $keterangan . '</td><td>' . $tglpermohonan . '</td><td>' . $link . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Pengajuan Cuti');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}

	function infoAtasan()
	{
		$pegawaiid = ifunsetemptybase64($_GET, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_GET, 'nourut', null);
		$tahun = date("Y");
		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_tahun' => $tahun,
		);
		$mGetDetailCuti = $this->m_history->getCutiById($params);
		$pengajuan = $mGetDetailCuti['pengajuanid'];
		$rlogin = $this->m_pengajuan->getcutiid($pegawaiid, $pengajuan);
		$verifikatorid = $rlogin[0]['atasan1'];
		$atasanid = $rlogin[0]['atasan2'];
		$ratasan = $this->m_pengajuan->getAppVer($atasanid);
		$rverify = '';
		if (!empty($verifikatorid)) {
			$rverify = $this->m_pengajuan->getAppVer($verifikatorid);
		}

		$result = array(
			'verifikatorid' => !empty($rverify[0]['pegawaiid']) ? $rverify[0]['pegawaiid'] : null,
			'verifikatornik' => !empty($rverify[0]['nik']) ? $rverify[0]['nik'] : null,
			'verifikatornama' => !empty($rverify[0]['nama']) ? $rverify[0]['nama'] : null,
			'verifikatorjab' => !empty($rverify[0]['jabatan']) ? $rverify[0]['jabatan'] : null,
			'verifikatoremail' => !empty($rverify[0]['emailkantor']) ? $rverify[0]['emailkantor'] : null,
			'atasanid' => $ratasan[0]['pegawaiid'],
			'atasannik' => $ratasan[0]['nik'],
			'atasannama' => $ratasan[0]['nama'],
			'atasanjab' => $ratasan[0]['jabatan'],
			'atasanemail' => $ratasan[0]['emailkantor'],
		);

		return $result;
	}
}
