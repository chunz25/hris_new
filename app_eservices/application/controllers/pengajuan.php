<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class pengajuan extends EServices_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->cuti();
	}

	public function cuti()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		$data['vjeniscuti'] = $this->m_pengajuan->getComboJenisCuti();
		$data['vharilibur'] = $this->getHariLibur();
		$data['vinfopegawai'] = $this->getInfoPegawai();
		$data['vinfoatasan'] = $this->getInfoAtasan();
		$data['vinfocuti'] = $this->m_pengajuan->getInfoCuti($pegawaiid);
		$data['vstatuscuti'] = $this->m_pengajuan->getStatusCuti($pegawaiid);
		$data['vsisacuti'] = $this->getInfoSisaCuti();
		$data['vnikadmin'] = array('15080236', '1309800', '411589');
		$data['pages'] = "pengajuan";
		$content = "pengajuan/v_cuti";

		$this->load->view($content, $data);
	}

	public function getDetailJenisCuti()
	{
		$jeniscutiid = ifunsetempty($_POST, 'jeniscutiid', null);
		$mresult = $this->m_pengajuan->getComboDetailJenisCuti($jeniscutiid);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	public function getInfoPegawai()
	{
		$params = array(
			'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			'v_tahun' => date("Y")
		);

		$mresult = $this->m_pengajuan->getInfoPegawai($params);
		return $mresult['firstrow'];
	}

	public function getInfoSisaCuti()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		$mresult = $this->m_pengajuan->getInfoSisaCuti($pegawaiid);

		return $mresult;
	}

	public function getInfoAtasan()
	{
		$atasanid = $this->session->userdata('atasanid');
		$verifid = ifunsetempty($this->session->userdata('verifid'), null);
		$rAtasan = $this->m_pengajuan->getAppVer($atasanid);
		$rVerify = !empty($verifid) ? $this->m_pengajuan->getAppVer($verifid) : array();

		return array(
			'verifikatorid' => !empty($rVerify[0]['pegawaiid']) ? $rVerify[0]['pegawaiid'] : null,
			'verifikatornik' => !empty($rVerify[0]['nik']) ? $rVerify[0]['nik'] : null,
			'verifikatornama' => !empty($rVerify[0]['nama']) ? $rVerify[0]['nama'] : null,
			'verifikatorjab' => !empty($rVerify[0]['jabatan']) ? $rVerify[0]['jabatan'] : null,
			'verifikatoremail' => !empty($rVerify[0]['emailkantor']) ? $rVerify[0]['emailkantor'] : null,
			'atasanid' => !empty($rVerify[0]['pegawaiid']) ? $rVerify[0]['pegawaiid'] : $rAtasan[0]['pegawaiid'],
			'atasannik' => !empty($rVerify[0]['nik']) ? $rVerify[0]['nik'] : $rAtasan[0]['nik'],
			'atasannama' => !empty($rVerify[0]['nama']) ? $rVerify[0]['nama'] : $rAtasan[0]['nama'],
			'atasanjab' => !empty($rVerify[0]['jabatan']) ? $rVerify[0]['jabatan'] : $rAtasan[0]['jabatan'],
			'atasanemail' => !empty($rVerify[0]['emailkantor']) ? $rVerify[0]['emailkantor'] : $rAtasan[0]['emailkantor'],
		);
	}

	public function getHariLibur()
	{
		$mresult = $this->m_pengajuan->getHariLibur();
		$data = array();
		foreach ($mresult as $r) {
			$data[] = $r['tgl'];
		}
		return json_encode($data);
	}

	public function getListRekan()
	{
		$params = array(
			'v_pegawaiid' => (int) $this->session->userdata('pegawaiid'),
			'v_satkerid' => $this->session->userdata('satkerdisp'),
			'v_keyword' => ifunsetempty($_GET, 'keyword', ''),
			'v_usergroupid' => (int) $this->session->userdata('aksesid_eservices'),
		);

		$mresult = $this->m_pengajuan->getListRekan($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	public function hitungLamaCuti()
	{
		$tglMulai = ifunsetempty($_POST, 'tglawal', null);
		$tglSelesai = ifunsetempty($_POST, 'tglakhir', null);

		$mresultHariLibur = $this->m_pengajuan->getHariLibur();

		$holidays = array();
		foreach ($mresultHariLibur as $key => $value) {
			$holidays[$key] = $value['tgl'];
		}

		$lama_cuti = getJumlahHariKerja($tglMulai, $tglSelesai, $holidays);
		$result = array('success' => true, 'data' => $lama_cuti);
		echo json_encode($result);
	}

	public function cekPengajuanCuti()
	{
		// $this->m_pengajuan->addlogs();
		$act = ifunsetempty($_POST, 'act', null);

		if ($act == 'add') {
			$pegawaiid = $this->session->userdata('pegawaiid');
			$nourut = null;
		} else {
			$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
			$nourut = ifunsetemptybase64($_POST, 'nourut', null);
		}

		$params = array(
			'v_pegawaiid' => (int) $pegawaiid,
			'v_tglmulai' => ifunsetempty($_POST, 'tglawal', null),
			'v_tglselesai' => ifunsetempty($_POST, 'tglakhir', null),
			'v_nourut' => $nourut,
		);

		$mresult = $this->m_pengajuan->cekPengajuanCuti($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	public function simpancuti()
	{
		// $this->m_pengajuan->addlogs();
		$pegawaiid = $this->session->userdata('pegawaiid');
		$current_years = date("Y");
		$hp = ifunsetempty($_POST, 'hp', null);
		$atasan1 = ifunsetempty($_POST, 'atasan1id', null);
		$atasan2 = ifunsetempty($_POST, 'atasan2id', null);

		$atasan1email = ifunsetempty($_POST, 'atasan1email', null);
		$atasan2email = ifunsetempty($_POST, 'atasan2email', null);

		$pelimpahan = ifunsetempty($_POST, 'rekanpegawaiid', null);
		$statusid = '1';
		$act = ifunsetempty($_POST, 'act', 'save');
		$files = $_FILES['files'];
		$newfilesname = null;
		$file_ext = null;
		$tglpermohonan = date("d/m/Y");
		$atasanid = ''; // notif pengajuan apabila atasan langsung approval
		$atasanemail = '';

		if ($atasan1 == '') {
			$atasanid = $atasan2;
			$atasanemail = $atasan2email;
		} else {
			$atasanid = $atasan1;
			$atasanemail = $atasan1email;
		}

		if ($files['error'] == 0) {
			$filesname_exp = explode('.', $files['name'], 2);
			$newfilesname = $filesname_exp[0] . '_' . time() . '.' . $filesname_exp[1];
		}

		if (is_uploaded_file($files['tmp_name'])) {
			$config['upload_path'] = config_item("eservices_upload_dok_path");
			$config['allowed_types'] = 'png|jpg|jpeg|pdf|doc|docx';
			$config['not_allowed_types'] = 'php|txt|exe';
			$config['max_size'] = 1000;
			$config['overwrite'] = TRUE;
			$config['file_name'] = $newfilesname;

			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('files')) {
				$error = array('error' => $this->upload->display_errors());
				$output = array('success' => false, 'message' => $this->upload->display_errors('', ''));
				echo json_encode($output);
				return;
			} else {
				$data = array('upload_data' => $this->upload->data());
				$file_ext = $data['upload_data']['file_ext'];
				$newfilesname = $data['upload_data']['file_name'];
			}
		}

		$daftarcuti = array();
		$daftarcuti = json_decode($this->input->post('daftarcuti'));

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_periode' => $current_years,
			'v_tglpermohonan' => $tglpermohonan,
			'v_atasan1' => $atasan1,
			'v_atasan2' => $atasan2,
			'v_pelimpahan' => $pelimpahan,
			'v_status' => $statusid,
			'v_verifikasinotes' => null,
			'v_files' => $newfilesname,
			'v_filestype' => $file_ext,
			'v_hp' => $hp,
		);

		$mpengajuan = $this->m_pengajuan->addPengajuanCuti($params);

		if (!empty($mpengajuan['pengajuanid'])) {
			$o = 0;
			foreach ($daftarcuti as $r) {
				$sendParams = array(
					'v_pengajuanid' => $mpengajuan['pengajuanid'],
					'v_jeniscutiid' => (string) $r->jeniscutiid,
					'v_detailjeniscutiid' => (string) $r->detailjeniscutiid,
					'v_tglmulai' => $r->tglawal,
					'v_tglselesai' => $r->tglakhir,
					'v_lama' => (string) $r->lamacuti,
					'v_satuan' => 'HARI KERJA',
					'v_sisacuti' => !empty($r->sisacuti) ? (string) $r->sisacuti : '0',
					'v_alasan' => !empty($r->keterangan) ? $r->keterangan : null,
				);
				$mdetailpengajuan = $this->m_pengajuan->addDetailPengajuanCuti($sendParams);
				if ($mdetailpengajuan)
					$o++;
			}
			if ($o > 0) {
				// Jika diajukan maka langsung ubah statusnya menjadi pengajuan baru
				if ($act == 'ajukan') {
					$paramsAjukan = array(
						'v_pegawaiid' => $pegawaiid,
						'v_nourut' => $mpengajuan['nourut'],
						'v_status' => '2',
						'v_notes' => null,
						'v_hrd' => null
					);
					$this->m_pengajuan->updStatusCuti($paramsAjukan);

					$desc = array(
						'nik' => $this->session->userdata('nik'),
						'nama' => $this->session->userdata('nama'),
						'desctription' => 'Pengajuan Cuti pada tanggal ' . $tglpermohonan,
					);

					$params = array(
						'v_jenisnotif' => 'Pengajuan Cuti',
						'v_description' => json_encode($desc),
						'v_penerima' => $atasanid,
						'v_useridfrom' => $this->session->userdata('userid'),
						'v_usergroupidfrom' => $this->session->userdata('aksesid_eservices'),
						'v_pengirim' => $pegawaiid,
						'v_modulid' => '2',
						'v_modul' => null,
					);

					$this->tp_notification->addNotif($params);
					$this->sendMail($atasanemail);
				}

				$result = array('success' => true, 'message' => 'Data berhasil ditambah');
			} else {
				$result = array('success' => false, 'message' => 'Data gagal ditambah');
			}
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	public function sendMail($atasanemail)
	{
		$nik = $this->session->userdata('nik');
		$nama = $this->session->userdata('nama');
		$tglpermohonan = date("d/m/Y");
		$this->load->library('email');	//Load email library
		$link = 'http://10.101.0.85/hris/index.php/login';
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
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>Pengajuan Cuti</td><td>' . $tglpermohonan . '</td><td>' . $link . '</td></tr>';
			$htmlContent .= '</table>';
			$this->email->subject('Pengajuan Cuti');
			$this->email->message($htmlContent);

			//Send email
			$this->email->send();
		}
	}

	function ajukanCuti()
	{
		// $this->m_pengajuan->addlogs();
		$pegawaiid = ifunsetemptybase64($_POST, 'pegawaiid', null);
		$nourut = ifunsetemptybase64($_POST, 'nourut', null);

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => $nourut,
			'v_status' => '2',
			'v_notes' => null,
		);

		$mresult = $this->m_pengajuan->updStatusCuti($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil diubah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal diubah');
		}
		echo json_encode($result);
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
