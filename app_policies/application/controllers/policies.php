<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class policies extends Policies_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->policies();
	}

	public function policies()
	{
		$data = array();
		$data['vnamadokumen'] = $this->m_pengajuan->getComboNamaDokumen();
		$data['vsatker'] = $this->m_pengajuan->getComboDivisi();
		$data['pages'] = "pengajuan";
		$content = "pengajuan/v_policies";

		$this->load->view($content, $data);
	}

	public function getComboDept()
	{
		$params = array(
			'v_dept' => ifunsetempty($_POST, 'jeniscutiid', null)
		);
		$mresult = $this->m_pengajuan->getComboDept($params);
		$result = array('success' => true, 'data' => $mresult);
		echo json_encode($result);
	}

	public function upload()
	{
		$filesold = ifunsetempty($_POST, 'filesold', null);
		$files = $_FILES['files'];
		$newfilesname = null;
		$newfilesname2 = null;

		if ($files['error'] == 0) {
			$filesname_exp = explode('.', $files['name'], 2);
			$newfilesname = $filesname_exp[0] . '_' . time() . '.' . $filesname_exp[1];
		}

		if (is_uploaded_file($files['tmp_name'])) {
			$config['upload_path'] = config_item("eservices_upload_dok_path");
			$config['allowed_types'] = 'png|jpg|jpeg|pdf|doc|docx';
			$config['not_allowed_types'] = 'php|txt|exe';
			$config['max_size'] = 0;
			$config['overwrite'] = TRUE;
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('files')) {
				$error = array('error' => $this->upload->display_errors());
				$output = array('success' => false, 'message' => $this->upload->display_errors('', ''));
				echo json_encode($output);
				return;
			} else {
				$data = array('upload_data' => $this->upload->data());
				if (!empty($filesold)) {
					$filePath = config_item('eservices_upload_dok_path') . $filesold;
					if (file_exists($filePath) && is_file($filePath)) {
						unlink($filePath);
					}
				}
				$file_ext = $data['upload_data']['file_ext'];
				$newfilesname2 = $data['upload_data']['file_name'];
				;
			}
		} else {
			$newfilesname2 = $filesold;
			$file_ext = '';
		}

		$nama = $this->input->post('nama');
		$deskripsi = $this->input->post('deskripsi');
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$date = date('Y-m-d');
		$admin = $this->session->userdata('nik');
		$jenisform = ifunsetempty($_POST, 'jenisform', null);
		$status = ifunsetempty($_POST, 'status', null);
		$divisi = ifunsetempty($_POST, 'divisi', null);
		$departemen = ifunsetempty($_POST, 'departemen', null);
		$lokasi = ifunsetempty($_POST, 'lokasi', null);
		$satker = '';
		$satkerold = ifunsetempty($_POST, 'satkerold', null);
		$statusold = ifunsetempty($_POST, 'statusold', null);
		$lokasiold = ifunsetempty($_POST, 'lokasiold', null);
		// jika status sama dengan 1 maka divisi null
		if ($status == '') {
			$status = $statusold;
			$satker = $satkerold;
		} else {
			if ($status == '1') {
				$satker = null;
			} else {
				if ($departemen == '') {
					$satker = $divisi;
				} else {
					$satker = $departemen;
				}
			}
		}

		// update lokasi
		if ($lokasi == '') {
			$lokasi = $lokasiold;
		} else {
			$lokasi = $lokasi;
		}

		// apabila bukan update, berarti insert
		$x = $this->input->post('hidden');
		if ($x == "update") {
			$data = array(
				'v_id' => ifunsetempty($_POST, 'update', null),
				'v_nama' => $nama,
				'v_deskripsi' => $deskripsi,
				'v_date' => $date,
				'v_files' => $newfilesname2,
				'v_status' => $status,
				'v_createby' => $admin,
				'v_divisi' => !empty($satker) ? $satker : null,
				'v_jenisform' => !empty($jenisform) ? $jenisform : null,
				'v_lokasi' => $lokasi,
			);
			$this->m_pengajuan->updPolicies($data);
		} else {
			$data = array(
				'v_nama' => $nama,
				'v_deskripsi' => $deskripsi,
				'v_date' => $date,
				'v_files' => $newfilesname2,
				'v_status' => $status,
				'v_createby' => $admin,
				'v_divisi' => !empty($satker) ? $satker : null,
				'v_jenisform' => !empty($jenisform) ? $jenisform : null,
				'v_lokasi' => $lokasi,
			);
			$this->m_pengajuan->addPolicies($data);
		}
		redirect('policies', 'refresh');
	}

	function success()
	{
		if ($this->session->userdata('logged_in')) {
			// session
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			//load     
			$data['pages'] = "dashboard";
			$this->load->view('v_success', $data);
		} else {
			//If no session, redirect to login page
			redirect('policies', 'refresh');
		}
	}
}

