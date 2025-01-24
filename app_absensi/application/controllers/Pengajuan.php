<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Pengajuan extends Wfh_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('M_pengajuan');
	}

	// Default method that redirects to absensi
	public function index()
	{
		$this->absensi();
	}

	// Method to render the attendance view
	public function absensi()
	{
		$data = array();
		$data['vharilibur'] = $this->getHariLibur(); // Get holidays from database
		$data['vinfopegawai'] = $this->getInfoPegawai(); // Employee info
		$data['vinfoatasan'] = $this->getInfoAtasan(); // Supervisor info
		$data['vopendate'] = $this->getOpenDate(); // Open date info
		$data['pages'] = "pengajuan";

		// // Log the action
		// $this->M_pengajuan->addLogs();

		// // Load the view
		$this->load->view("pengajuan/v_absensi", $data);
		// die(var_dump($data));
	}

	// Retrieve the open dates for applications
	public function getOpenDate()
	{
		$results = $this->M_pengajuan->getOpenDate();
		return json_encode(array_column($results, 'nik'));
	}

	// Get information about the employee
	public function getInfoPegawai()
	{
		$pegawaiid = $this->session->userdata('pegawaiid');
		$result = $this->M_pengajuan->getInfoPegawai($pegawaiid);
		return $result[0];
	}

	// Get information about the supervisor and verifier
	public function getInfoAtasan()
	{
		$atasanid = $this->session->userdata('atasanid');
		$verifid = $this->session->userdata('verifid');
		$rAtasan = $this->M_pengajuan->getAppVer($atasanid);
		$rVerify = !empty($verifid) ? $this->M_pengajuan->getAppVer($verifid) : array();

		return array(
			'verifikatorid' => null,
			'verifikatornik' => null,
			'verifikatornama' => null,
			'verifikatorjab' => null,
			'verifikatoremail' => null,
			'atasanid' => !empty($rVerify[0]['pegawaiid']) ? $rVerify[0]['pegawaiid'] : $rAtasan[0]['pegawaiid'],
			'atasannik' => !empty($rVerify[0]['nik']) ? $rVerify[0]['nik'] : $rAtasan[0]['nik'],
			'atasannama' => !empty($rVerify[0]['nama']) ? $rVerify[0]['nama'] : $rAtasan[0]['nama'],
			'atasanjab' => !empty($rVerify[0]['jabatan']) ? $rVerify[0]['jabatan'] : $rAtasan[0]['jabatan'],
			'atasanemail' => !empty($rVerify[0]['emailkantor']) ? $rVerify[0]['emailkantor'] : $rAtasan[0]['emailkantor'],
		);
	}

	// Get holiday dates
	public function getHariLibur()
	{
		$results = $this->M_pengajuan->getHariLibur();
		return json_encode(array_column($results, 'tgl'));
	}

	// Check attendance application
	public function cekPengajuanKehadiran()
	{
		$act = $this->input->post('act', null);
		$pegawaiid = ($act === 'add') ? $this->session->userdata('pegawaiid') : $this->input->post('pegawaiid', null);
		$jenisid = ($act === 'add') ? $this->input->post('jenisid', null) : $this->input->post('jenisid', null);
		$nourut = ($act === 'add') ? null : $this->input->post('nourut', null);

		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_jenisid' => $jenisid,
			'v_tglmulai' => $this->input->post('tglawal', null),
			'v_nourut' => $nourut,
		);

		$mresult = $this->M_pengajuan->cekPengajuanKehadiran($params);
		echo json_encode(array('success' => true, 'data' => $mresult));
	}

	public function simpanabsensi()
	{
		try {
			// Fetch session data
			$pegawaiid = (int) $this->session->userdata('pegawaiid');
			$userid = (int) $this->session->userdata('userid');
			$nik = $this->session->userdata('nik');
			$nama = $this->session->userdata('nama');
			$aksesid_absensi = $this->session->userdata('aksesid_absensi');

			// Capture input data
			$atasanid = isset($_POST['atasanid']) ? $_POST['atasanid'] : null;
			$atasanemail = isset($_POST['atasanemail']) ? $_POST['atasanemail'] : null;
			$tglpermohonan = date("d/m/Y");
			$daftarabsensi = json_decode($this->input->post('daftarcuti'));

			// Handle file upload
			$files = $_FILES['files'];
			$newfilesname = null;
			$file_ext = null;

			if ($files['error'] == 0 && is_uploaded_file($files['tmp_name'])) {
				$filesname_exp = explode('.', $files['name'], 2);
				$newfilesname = $filesname_exp[0] . '_' . time() . '.' . $filesname_exp[1];

				// Configure upload settings
				$config = array(
					'upload_path' => config_item("absensi_upload_dok_path"),
					'allowed_types' => 'png|jpg|jpeg|pdf|doc|docx',
					'not_allowed_types' => 'php|txt|exe',
					'max_size' => 0,
					'overwrite' => TRUE,
					'file_name' => $newfilesname
				);

				$this->load->library('upload', $config);

				// Handle upload errors
				if (!$this->upload->do_upload('files')) {
					throw new Exception($this->upload->display_errors('', ''));
				}

				$upload_data = $this->upload->data();
				$file_ext = $upload_data['file_ext'];
				$newfilesname = $upload_data['file_name'];
			}

			// Process each attendance request
			$success_count = 0;
			foreach ($daftarabsensi as $r) {
				$params = array(
					'v_jenisid' => isset($r->jeniscutiid) ? (int) $r->jeniscutiid : 0,
					'v_waktu' => $r->tglawal,
					'v_jam' => $r->jamawal,
					'v_keterangan' => isset($r->keterangan) ? $r->keterangan : null,
					'v_status' => 1,
					'v_pegawaiid' => (int) $pegawaiid,
					'v_atasanid' => (int) $atasanid,
					'v_files' => $newfilesname,
					'v_filestype' => $file_ext
				);

				if (!$this->M_pengajuan->addPengajuanAbsensi($params)) {
					throw new Exception('Database error: failed to add attendance request.');
				}
				$success_count++;

				// Prepare notification description
				$desc = array(
					'nik' => $nik,
					'nama' => $nama,
					'description' => 'Pengajuan absensi kehadiran pada tanggal ' . $tglpermohonan,
				);

				// Add notification for the manager
				$notif_params = array(
					'v_jenisnotif' => 'Pengajuan Form Kehadiran',
					'v_description' => json_encode($desc),
					'v_penerima' => $atasanid,
					'v_useridfrom' => $userid,
					'v_usergroupidfrom' => $aksesid_absensi,
					'v_pengirim' => $pegawaiid,
					'v_modulid' => '10',
					'v_modul' => 'Modul datang telat / pulang cepat',
				);

				if (!$this->M_pengajuan->addNotif($notif_params)) {
					throw new Exception('Failed to add notification.');
				}

				// Send email to the manager
				if (!$this->sendMail($atasanemail)) {
					log_message('error', 'Failed to send email to ' . $atasanemail);  // Log the error
				}

			}

			if ($success_count > 0) {
				echo json_encode(array(
					'success' => true,
					'message' => 'Data berhasil dikirim'
				));
			} else {
				throw new Exception('No attendance requests were processed.');
			}

		} catch (Exception $e) {
			// Log the error for debugging purposes
			log_message('error', 'Error in simpanabsensi: ' . $e->getMessage());

			// Return failure message
			echo json_encode(array(
				'success' => false,
				'message' => 'An error occurred: ' . $e->getMessage()
			));
		}
	}

	public function sendMail($atasanemail)
	{
		try {
			// Fetch session data
			$nik = $this->session->userdata('nik');
			$nama = $this->session->userdata('nama');
			$tglpermohonan = date("d/m/Y");

			// Load email library
			$this->load->library('email');

			// Link to HRIS system
			$link = 'http://internal.electronic-city.co.id/hris/';

			// SMTP configuration
			$config = array(
				'protocol' => 'smtp',
				'smtp_host' => 'mail.electronic-city.co.id',
				'smtp_port' => 25,
				'smtp_user' => 'hris-ec@electronic-city-internal.co.id',
				'smtp_pass' => 'L0nt0n9',
				'mailtype' => 'html',
				'charset' => 'utf-8'
			);

			// Check if the manager's email is provided
			if (empty($atasanemail)) {
				log_message('warning', 'No email provided for sending mail.');
				return false;
			}

			// Initialize email settings
			$this->email->initialize($config);
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");
			$this->email->to($atasanemail);
			$this->email->from('hris-ec@electronic-city-internal.co.id', 'HRIS Electronic City');

			// Prepare email content
			$htmlContent = '<table border="1" cellpadding="0" cellspacing="0" width="100%">';
			$htmlContent .= '<tr><th>Nik</th><th>Nama</th><th>Jenis Notifikasi</th><th>Tanggal Pengajuan</th><th>Link</th></tr>';
			$htmlContent .= '<tr><td>' . $nik . '</td><td>' . $nama . '</td><td>Pengajuan Form Kehadiran</td><td>' . $tglpermohonan . '</td><td><a href="' . $link . '">HRIS</a></td></tr>';
			$htmlContent .= '</table>';

			// Set email subject and message
			$this->email->subject('Pengajuan Form Kehadiran');
			$this->email->message($htmlContent);

			// Send the email
			if (!$this->email->send()) {
				throw new Exception('Failed to send email: ' . $this->email->print_debugger());
			}

			// Log success
			log_message('info', 'Email sent successfully to ' . $atasanemail);
			return true;

		} catch (Exception $e) {
			// Log the error
			log_message('error', 'Error in sendMail: ' . $e->getMessage());

			// Return false if an error occurs
			return false;
		}
	}


}

