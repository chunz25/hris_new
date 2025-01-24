<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
// require_once "cetakexcel.php";

class pegawai extends SIAP_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pegawai');
	}

	/* Panel Identitas */
	function getListPegawai()
	{
		$params = array(
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', ''),
			'v_nik' => ifunsetempty($_POST, 'nik', null),
			'v_nama' => ifunsetempty($_POST, 'nama', null),
			'v_statuspegawai' => ifunsetempty($_POST, 'statuspegawai', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_tglmulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_tglselesai' => ifunsetempty($_POST, 'tglselesai', null),
			'v_start' => ifunsetempty($_POST, 'start', 0),
			'v_limit' => ifunsetempty($_POST, 'limit', config_item('PAGESIZE')),
		);
		$mresult = $this->m_pegawai->getListPegawai($params);
		echo json_encode($mresult);
	}

	public function get_data_by_kodepos()
	{
		// Check if the request is a POST request
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$kodepos = $this->input->post('kodepos');


			if (!empty($kodepos)) {
				$data = $this->m_pegawai->get_address_by_kodepos($kodepos);
				if ($data) {
					// Return success response with data
					echo json_encode([
						'success' => true,
						'data' => $data
					]);
				} else {
					// Return failure response if no data found
					echo json_encode([
						'success' => false,
						'message' => 'Data tidak ditemukan untuk kode pos: ' . $kodepos
					]);
				}
			} else {
				// Return failure response if kode pos is empty
				echo json_encode([
					'success' => false,
					'message' => 'Kode pos tidak boleh kosong.'
				]);
			}
		} else {
			// Return failure response for invalid request method
			echo json_encode([
				'success' => false,
				'message' => 'Invalid request method.'
			]);
		}
	}

	function tambahPegawai()
	{
		$foto = $_FILES['foto']['tmp_name'];
		$fotoname = $_FILES['foto']['error'] > 0 ? null : $_FILES['foto']['name'];

		$nik = ifunsetempty($_POST, 'nik', null);
		if (is_uploaded_file($foto)) {
			$config['upload_path'] = $this->config->item("siap_upload_foto_path");
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['not_allowed_types'] = 'php|txt|exe';
			$config['max_size'] = '1000';
			$config['overwrite'] = TRUE;
			$config['file_name'] = $fotoname;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('foto')) {
				$error = array('error' => $this->upload->display_errors());
				$output = array(
					'success' => false,
					'message' => $this->upload->display_errors('', '')
				);
				echo json_encode($output);
				return;
			} else {
				$data = array('upload_data' => $this->upload->data());
			}
		}

		$params = array(
			'v_nik' => ifunsetempty($_POST, 'nik', null),
			'v_namadepan' => ifunsetempty($_POST, 'namadepan', null),
			'v_namabelakang' => ifunsetempty($_POST, 'namabelakang', null),
			'v_namakeluarga' => ifunsetempty($_POST, 'namakeluarga', null),
			'v_tempatlahir' => ifunsetempty($_POST, 'tempatlahir', null),
			'v_tgllahir' => ifunsetempty($_POST, 'tgllahir', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_alamatktp' => ifunsetempty($_POST, 'alamatktp', null),
			'v_kelurahanktp' => ifunsetempty($_POST, 'kelurahanktp', null),
			'v_kecamatanktp' => ifunsetempty($_POST, 'kecamatanktp', null),
			'v_kotaktp' => ifunsetempty($_POST, 'kotaktp', null),
			'v_kodeposktp' => ifunsetempty($_POST, 'kodeposktp', null),
			'v_alamat' => ifunsetempty($_POST, 'alamat', null),
			'v_kelurahan' => ifunsetempty($_POST, 'kelurahan', null),
			'v_kecamatan' => ifunsetempty($_POST, 'kecamatan', null),
			'v_kota' => ifunsetempty($_POST, 'kota', null),
			'v_kodepos' => ifunsetempty($_POST, 'kodepos', null),
			'v_kewarganegaraan' => ifunsetempty($_POST, 'kewarganegaraan', null),
			'v_goldarah' => ifunsetempty($_POST, 'goldarah', null),
			'v_rhesus' => ifunsetempty($_POST, 'rhesus', null),
			'v_agamaid' => ifunsetempty($_POST, 'agama', null),
			'v_telp' => ifunsetempty($_POST, 'telp', null),
			'v_hp' => ifunsetempty($_POST, 'hp', null),
			'v_email' => ifunsetempty($_POST, 'email', null),
			'v_emailkantor' => ifunsetempty($_POST, 'emailkantor', null),
			'v_noktp' => ifunsetempty($_POST, 'noktp', null),
			'v_masaberlakuktp' => ifunsetempty($_POST, 'masaberlakuktp', null),
			'v_npwp' => ifunsetempty($_POST, 'npwp', null),
			'v_bpjskes' => ifunsetempty($_POST, 'bpjskes', null),
			'v_bpjsnaker' => ifunsetempty($_POST, 'bpjsnaker', null),
			'v_askes' => ifunsetempty($_POST, 'askes', null),
			'v_paspor' => ifunsetempty($_POST, 'paspor', null),
			'v_nokk' => ifunsetempty($_POST, 'nokk', null),
			'v_statusnikah' => ifunsetempty($_POST, 'statusnikah', null),
			'v_tglnikah' => ifunsetempty($_POST, 'tglnikah', null),
			'v_beratbadan' => ifunsetempty($_POST, 'beratbadan', null),
			'v_tinggibadan' => ifunsetempty($_POST, 'tinggibadan', null),
			'v_namakontakdarurat' => ifunsetempty($_POST, 'namakontakdarurat', null),
			'v_telpkontakdarurat' => ifunsetempty($_POST, 'telpkontakdarurat', null),
			'v_relasikontakdarurat' => ifunsetempty($_POST, 'relasikontakdarurat', null),
			'v_hobby' => ifunsetempty($_POST, 'hobby', null),
			'v_shio' => ifunsetempty($_POST, 'shio', null),
			'v_unsur' => ifunsetempty($_POST, 'unsur', null),
			'v_sizebaju' => ifunsetempty($_POST, 'sizebaju', null),
			'v_sizecelana' => ifunsetempty($_POST, 'sizecelana', null),
			'v_sizesepatu' => ifunsetempty($_POST, 'sizesepatu', null),
			'v_sizerompi' => ifunsetempty($_POST, 'sizerompi', null),
			'v_statuspegawaiid' => ifunsetempty($_POST, 'statuspegawai', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_jabatanid' => ifunsetempty($_POST, 'jabatanID', null),
			'v_levelid' => ifunsetempty($_POST, 'levelid', null),
			'v_foto' => $fotoname,
		);

		$mresult = $this->m_pegawai->tambahPegawai($params);
		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function getPegawaiByID()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getPegawaiByID($params);

		$data = array();
		foreach ($mresult['data'] as $r) {
			$r['fotoname'] = $r['foto'];
			$imagePath = config_item('siap_upload_foto_path') . $r['foto'];
			if (file_exists($imagePath) && is_file($imagePath)) {
				$r['fotonew'] = config_item('siap_upload_foto_url') . $r['foto'];
			} else {
				$r['fotonew'] = config_item('no_image_person_url');
			}
			$data = $r;
		}

		$result = array('success' => true, 'data' => $data);
		echo json_encode($result);
	}

	function ubahPegawai()
	{
		try {
			$foto = $_FILES['foto']['tmp_name'];
			$foto2 = $_FILES['foto']['name'];
			$errorfoto = $_FILES['foto']['error'];
			$fotoname2 = ifunsetempty($_POST, 'fotoname', null);
			// Chuno Tambahin
			$nik = ifunsetempty($_POST, 'nik', null);
			$temp = explode(".", $foto2);
			$fotonew = $nik . '_1' . '.' . end($temp);
			$fotoname = $errorfoto > 0 ? null : $fotonew;
			$pathfile = $this->config->item("siap_upload_foto_path");

			// if (move_uploaded_file($foto, $pathfile . $fotonew)) {
			if (is_uploaded_file($foto)) {
				$config['upload_path'] = $pathfile;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['not_allowed_types'] = 'php|txt|exe';
				$config['max_size']	= '2000';
				$config['overwrite']  = TRUE;
				$config['file_name']  = $fotoname2;
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('foto')) {
					$error = array('error' => $this->upload->display_errors());
					$output = array(
						'success' => false,
						'message' => $this->upload->display_errors('', '')
					);
					echo json_encode($output);
					return;
				} else if ($fotoname2 == null) {
					$data = array('upload_data' => $this->upload->data());
				} else {
					unlink($pathfile . $fotoname);
					$data = array('upload_data' => $this->upload->data());
				}
			} else {
				$fotoname = $fotoname2;
			}


			$params = array(
				'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
				'v_nik' => ifunsetempty($_POST, 'nik', null),
				'v_namadepan' => ifunsetempty($_POST, 'namadepan', null),
				'v_namabelakang' => ifunsetempty($_POST, 'namabelakang', null),
				'v_namakeluarga' => ifunsetempty($_POST, 'namakeluarga', null),
				'v_tempatlahir' => ifunsetempty($_POST, 'tempatlahir', null),
				'v_tgllahir' => ifunsetempty($_POST, 'tgllahir', null),
				'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
				'v_alamatktp' => ifunsetempty($_POST, 'alamatktp', null),
				'v_pkktp' => ifunsetempty($_POST, 'pkktp', null),
				'v_alamat' => ifunsetempty($_POST, 'alamat', null),
				'v_pkdom' => ifunsetempty($_POST, 'pk', null),
				'v_kewarganegaraan' => ifunsetempty($_POST, 'kewarganegaraan', null),
				'v_goldarah' => ifunsetempty($_POST, 'goldarahid', null),
				'v_agamaid' => ifunsetempty($_POST, 'agama', null),
				'v_telp' => ifunsetempty($_POST, 'telp', null),
				'v_hp' => ifunsetempty($_POST, 'hp', null),
				'v_email' => ifunsetempty($_POST, 'email', null),
				'v_emailkantor' => ifunsetempty($_POST, 'emailkantor', null),
				'v_noktp' => ifunsetempty($_POST, 'noktp', null),
				'v_npwp' => ifunsetempty($_POST, 'npwp', null),
				'v_bpjskes' => ifunsetempty($_POST, 'bpjskes', null),
				'v_bpjsnaker' => ifunsetempty($_POST, 'bpjsnaker', null),
				'v_askes' => ifunsetempty($_POST, 'askes', null),
				'v_paspor' => ifunsetempty($_POST, 'paspor', null),
				'v_nokk' => ifunsetempty($_POST, 'nokk', null),
				'v_statusnikah' => ifunsetempty($_POST, 'statusnikahid', null),
				'v_tglnikah' => ifunsetempty($_POST, 'tglnikah', null),
				'v_beratbadan' => ifunsetempty($_POST, 'beratbadan', null),
				'v_tinggibadan' => ifunsetempty($_POST, 'tinggibadan', null),
				'v_namakontakdarurat' => ifunsetempty($_POST, 'namakontakdarurat', null),
				'v_telpkontakdarurat' => ifunsetempty($_POST, 'telpkontakdarurat', null),
				'v_relasikontakdarurat' => ifunsetempty($_POST, 'relasikontakdarurat', null),
				'v_hobby' => ifunsetempty($_POST, 'hobby', null),
				'v_shio' => ifunsetempty($_POST, 'shioid', null),
				'v_sizebaju' => ifunsetempty($_POST, 'sizebaju', null),
				'v_sizecelana' => ifunsetempty($_POST, 'sizecelana', null),
				'v_sizesepatu' => ifunsetempty($_POST, 'sizesepatu', null),
				'v_sizerompi' => ifunsetempty($_POST, 'sizerompi', null),
				'v_statuspegawaiid' => ifunsetempty($_POST, 'statuspegawaiid', null),
				'v_foto' => $fotoname
			);

			// die(var_dump(json_encode($params)));

			$mresult = $this->m_pegawai->ubahPegawai($params);
			if ($mresult) {
				move_uploaded_file($foto, $pathfile . $fotoname);
				$result = array('success' => true, 'msg' => 'Data berhasil diubah');
			} else {
				$result = array('success' => false, 'msg' => 'Data gagal diubah');
			}

			echo json_encode($result);
		} catch (Exception $e) {
			$result = array('success' => false, 'msg' => 'Error: ' . $e->getMessage());
			echo json_encode($result);
		}
	}
	/* Panel Identitas */

	/* Riwayat Jabatan */
	function getRiwayatJabatan()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatJabatan($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatJabatan()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => 1,
			'v_statuspegawaiid' => ifunsetempty($_POST, 'statuspegawaiid', null),
			'v_jabatanid' => ifunsetempty($_POST, 'jabatanid', null),
			'v_levelid' => ifunsetempty($_POST, 'levelid', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid', null),
			'v_masakerjath' => ifunsetempty($_POST, 'masakerjath', null),
			'v_masakerjabl' => ifunsetempty($_POST, 'masakerjabl', null),
			'v_tglmulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_tglselesai' => ifunsetempty($_POST, 'tglselesai', null),
			'v_tglakhirkontrak' => ifunsetempty($_POST, 'tglakhirkontrak', null),
			'v_tglpermanent' => ifunsetempty($_POST, 'tglpermanent', null),
			'v_keterangan' => ifunsetempty($_POST, 'keterangan', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasiid', 1)
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatJabatan($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatJabatan($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatJabatan()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatJabatan($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Riwayat Jabatan */

	/* Data Keluarga */
	function getRiwayatKeluarga()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatKeluarga($params);

		$data = array();
		foreach ($mresult['data'] as $r) {
			$imagePath = config_item('siap_upload_foto_path') . $r['foto'];
			if (file_exists($imagePath) && is_file($imagePath)) {
				$r['fotourl'] = config_item('siap_upload_foto_url') . $r['foto'];
			} else {
				$r['fotourl'] = config_item('no_image_person_url');
			}

			$data[] = $r;
		}

		$result = array('success' => true, 'data' => $data);
		echo json_encode($result);
	}

	function crudRiwayatKeluarga()
	{
		$foto = $_FILES['foto']['tmp_name'];
		$foto2 = $_FILES['foto']['name'];
		$errorfoto = $_FILES['foto']['error'];
		$relasi = str_replace(' ', '', ifunsetempty($_POST, 'relasi', null));
		$nik = ifunsetempty($_POST, 'pegawaiid', null);
		$temp = explode(".", $foto2);
		$fotonew2 = strval($nik) . '_' . $relasi . '.' . end($temp);
		$fotoname = $errorfoto > 0 ? null : $fotonew2;
		$pathfile = $this->config->item("siap_upload_foto_path");
		$flag = ifunsetempty($_POST, 'flag', '1');

		if ($fotoname != NULL) {
			if (is_uploaded_file($foto)) {
				$config['upload_path'] = $pathfile;
				$config['allowed_types'] = 'jpg|png|jpeg';
				$config['not_allowed_types'] = 'php|txt|exe';
				$config['max_size'] = '2000';
				$config['overwrite'] = TRUE;
				$config['file_name'] = $fotoname;
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('foto')) {
					$error = array('error' => $this->upload->display_errors());
					$output = array(
						'success' => false,
						'message' => $this->upload->display_errors('', '')
					);
					echo json_encode($output);
					return;
				} else {
					$data = array('upload_data' => $this->upload->data());
					if ($flag == '2') {
						unlink($pathfile . $fotoname);
					}
				}
			} else {
				if ($flag == '1') {
					$fotoname = null;
				}
			}
		}

		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_relasi' => isset($_POST['relasiid']) && $_POST['relasiid'] !== null ? $_POST['relasiid'] : (isset($_POST['relasi']) ? $_POST['relasi'] : null),
			'v_nama' => ifunsetempty($_POST, 'nama', null),
			'v_jeniskelamin' => ifunsetempty($_POST, 'jeniskelamin', null),
			'v_tgllahir' => ifunsetempty($_POST, 'tgllahir', null),
			'v_pendidikan' => ifunsetempty($_POST, 'pendidikan', null),
			'v_pekerjaan' => ifunsetempty($_POST, 'pekerjaan', null),
			'v_foto' => $fotoname,
			'v_tmptlahir' => ifunsetempty($_POST, 'tmptlahir', null),
			'v_alamat' => ifunsetempty($_POST, 'alamat', null)
		);

		// die(var_dump($params));

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatKeluarga($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatKeluarga($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
			move_uploaded_file($foto, $pathfile . $fotoname);
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatKeluarga()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			if (!empty($r['foto'])) {
				$imagePath = config_item('siap_upload_foto_path') . $r['foto'];
				if (file_exists($imagePath) && is_file($imagePath)) {
					unlink(config_item('siap_upload_foto_path') . $r['foto']);
				}
			}
			$mresult = $this->m_pegawai->delRiwayatKeluarga($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Keluarga */

	/* Data Pendidikan */
	function getRiwayatPendidikan()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatPendidikan($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatPendidikan()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_pendidikan' => ifunsetempty($_POST, 'pendidikanid', null),
			'v_jurusan' => ifunsetempty($_POST, 'jurusan', null),
			'v_namasekolah' => ifunsetempty($_POST, 'namasekolah', null),
			'v_kota' => ifunsetempty($_POST, 'kota', null),
			'v_tahunmasuk' => ifunsetempty($_POST, 'tahunmasuk', null),
			'v_tahunkeluar' => ifunsetempty($_POST, 'tahunkeluar', null),
			'v_ipk' => ifunsetempty($_POST, 'ipk', null),
			'v_ijazah' => ifunsetempty($_POST, 'ijazah', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatPendidikan($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatPendidikan($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatPendidikan()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatPendidikan($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Pendidikan */

	/* Data Pengalaman Kerja */
	function getRiwayatPengalamanKerja()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatPengalamanKerja($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatPengalamanKerja()
	{

		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_perusahaan' => ifunsetempty($_POST, 'perusahaan', null),
			'v_alamat' => ifunsetempty($_POST, 'alamat', null),
			'v_bidangusaha' => ifunsetempty($_POST, 'bidangusaha', null),
			'v_jabatan' => ifunsetempty($_POST, 'jabatan', null),
			'v_jobdesc' => ifunsetempty($_POST, 'jobdesc', null),
			'v_tahunmasuk' => ifunsetempty($_POST, 'tahunmasuk', null),
			'v_tahunkeluar' => ifunsetempty($_POST, 'tahunkeluar', null),
			'v_alasankeluar' => ifunsetempty($_POST, 'alasankeluar', null),
			'v_gaji' => ifunsetempty($_POST, 'gaji', null),
			'v_atasan' => ifunsetempty($_POST, 'atasan', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatPengalamanKerja($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatPengalamanKerja($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatPengalamanKerja()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatPengalamanKerja($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Pengalaman Kerja */

	/* Data Rekening */
	function getRiwayatRekening()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatRekening($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatRekening()
	{

		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_nama' => ifunsetempty($_POST, 'nama', null),
			'v_norek' => ifunsetempty($_POST, 'norek', null),
			'v_kodebank' => ifunsetempty($_POST, 'kodebank', null),
			'v_bank' => ifunsetempty($_POST, 'bank', null),
			'v_cabang' => ifunsetempty($_POST, 'cabang', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatRekening($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatRekening($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatRekening()
	{

		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatRekening($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Rekening */

	/* Data Training */
	function getRiwayatKursus()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatKursus($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatKursus()
	{

		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_jeniskursus' => ifunsetempty($_POST, 'jeniskursus', null),
			'v_tahun' => ifunsetempty($_POST, 'tahun', null),
			'v_lama' => ifunsetempty($_POST, 'lama', null),
			'v_noijazah' => ifunsetempty($_POST, 'noijazah', null),
			'v_dibiayai' => ifunsetempty($_POST, 'dibiayai', null),
			'v_trainer' => ifunsetempty($_POST, 'trainer', null),
			'v_deskripsi' => ifunsetempty($_POST, 'deskripsi', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatKursus($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatKursus($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatKursus()
	{

		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatKursus($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Training */

	/* Data Bahasa */
	function getRiwayatBahasa()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatBahasa($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatBahasa()
	{

		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_bahasa' => ifunsetempty($_POST, 'bahasa', null),
			'v_tertulis' => ifunsetempty($_POST, 'tertulis', null),
			'v_lisan' => ifunsetempty($_POST, 'lisan', null),
			'v_baca' => ifunsetempty($_POST, 'baca', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatBahasa($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatBahasa($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatBahasa()
	{

		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatBahasa($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Bahasa */

	/* Data Penyakit / Alergi */
	function getRiwayatPenyakit()
	{
		try {
			$params = array(
				'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
			);

			if (!$params['v_pegawaiid']) {
				throw new Exception('Pegawai ID is required.');
			}

			$mresult = $this->m_pegawai->getRiwayatPenyakit($params);

			if (empty($mresult['data'])) {
				throw new Exception('No data found.');
			}

			$result = array('success' => true, 'data' => $mresult['data']);
		} catch (Exception $e) {
			$result = array('success' => false, 'message' => $e->getMessage());
		}

		echo json_encode($result);
	}

	function crudRiwayatPenyakit()
	{
		try {
			$flag = ifunsetempty($_POST, 'flag', '1');
			$jenispenyakit = ifunsetempty($_POST, 'jenispenyakit', null);

			if ($jenispenyakit == 'Sakit') {
				$jenispenyakit = 1;
			}

			if ($jenispenyakit == 'Alergi') {
				$jenispenyakit = 2;
			}

			$params = array(
				'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
				'v_nourut' => ifunsetempty($_POST, 'nourut', null),
				'v_jenispenyakit' => $jenispenyakit,
				'v_namapenyakit' => ifunsetempty($_POST, 'namapenyakit', null),
				'v_rawatinap' => ifunsetempty($_POST, 'rawatinap', null),
				'v_tahunrawat' => ifunsetempty($_POST, 'tahunrawat', null),
			);

			if (!$params['v_pegawaiid']) {
				throw new Exception('Pegawai ID is required.');
			}

			if ($flag == '1') { // Add operation
				unset($params['v_nourut']); // Ensure v_nourut is not sent for adding
				$mresult = $this->m_pegawai->addRiwayatPenyakit($params);
			} else { // Update operation
				if (!$params['v_nourut']) {
					throw new Exception('NoUrut is required for update.');
				}
				$mresult = $this->m_pegawai->updRiwayatPenyakit($params);
			}

			if ($mresult) {
				$result = array('success' => true, 'message' => 'Data berhasil diproses.');
			} else {
				throw new Exception('Database operation failed.');
			}
		} catch (Exception $e) {
			$result = array('success' => false, 'message' => $e->getMessage());
		}

		echo json_encode($result);
	}

	function delRiwayatPenyakit()
	{
		try {
			$params = json_decode($this->input->post('params'), true);

			if (empty($params) || !is_array($params)) {
				throw new Exception('Invalid parameters.');
			}

			$o = 0; // Count successful deletions
			foreach ($params as $r) {
				if (!isset($r['nourut']) || !isset($r['pegawaiid'])) {
					throw new Exception('Pegawai ID and NoUrut are required for deletion.');
				}

				$mresult = $this->m_pegawai->delRiwayatPenyakit($r);
				if ($mresult) {
					$o++;
				}
			}

			if ($o > 0) {
				$result = array('success' => true, 'message' => 'Data berhasil dihapus.');
			} else {
				throw new Exception('No records were deleted.');
			}
		} catch (Exception $e) {
			$result = array('success' => false, 'message' => $e->getMessage());
		}

		echo json_encode($result);
	}

	/* Data Penyakit / Alergi */

	/* Data Kegiatan AGP */
	function getRiwayatAGP()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatAGP($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatAGP()
	{

		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_kegiatan' => ifunsetempty($_POST, 'kegiatan', null),
			'v_lokasi' => ifunsetempty($_POST, 'lokasi', null),
			'v_tgl' => ifunsetempty($_POST, 'tahun', null),
			'v_jabatan' => ifunsetempty($_POST, 'jabatan', null),
			'v_keterangan' => ifunsetempty($_POST, 'keterangan', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatAGP($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatAGP($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatAGP()
	{

		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatAGP($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Kegiatan AGP */

	/* Data Indisipliner */
	function getRiwayatIndiplisiner()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatIndiplisiner($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatIndiplisiner()
	{

		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_nosurat' => ifunsetempty($_POST, 'nosurat', null),
			'v_peringatan' => ifunsetempty($_POST, 'peringatanke', null),
			'v_tglmulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_pelanggaran' => ifunsetempty($_POST, 'pelanggaran', null),
			'v_acuan' => ifunsetempty($_POST, 'acuan', null),
			'v_atasan' => ifunsetempty($_POST, 'atasan', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatIndiplisiner($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatIndiplisiner($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatIndiplisiner()
	{

		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatIndiplisiner($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Indisipliner */

	/* Data KPI */
	function getRiwayatPA()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatPA($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatPA()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_tahun' => ifunsetempty($_POST, 'tahun', null),
			'v_totms' => ifunsetempty($_POST, 'totms', '0'),
			'v_totdg' => ifunsetempty($_POST, 'totdg', '0'),
			'v_totwa' => ifunsetempty($_POST, 'totwa', '0'),
			'v_sp' => ifunsetempty($_POST, 'sp', '0'),
			'v_absensi' => ifunsetempty($_POST, 'absensi', '0'),
			'v_kpicompany' => ifunsetempty($_POST, 'kpicompany', '0'),
			'v_kpiindividu' => ifunsetempty($_POST, 'kpiindividu', '0'),
			'v_paindividu' => ifunsetempty($_POST, 'paindividu', '0'),
			'v_total' => ifunsetempty($_POST, 'total2', '0'),
			'v_pr' => ifunsetempty($_POST, 'pr', '0'),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatPA($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatPA($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatPA()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatPA($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data KPI */

	/* Data Catatan Tambahan */
	function getRiwayatCatatanTambahan()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatCatatanTambahan($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatCatatanTambahan()
	{

		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_keterangan' => ifunsetempty($_POST, 'keterangan', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatCatatanTambahan($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatCatatanTambahan($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatCatatanTambahan()
	{

		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatCatatanTambahan($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Catatan Tambahan */

	/* Data Riwayat Keahlian */
	function getRiwayatKeahlian()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getRiwayatKeahlian($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatKeahlian()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_keahlian' => ifunsetempty($_POST, 'keahlian', null),
			'v_keterangan' => ifunsetempty($_POST, 'keterangan', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatKeahlian($params);
		} else {
			$mresult = $this->m_pegawai->updRiwayatKeahlian($params);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatKeahlian()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatKeahlian($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Riwayat Keahlian */

	/* Data Acting As */
	function getActingAs()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getActingAs($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function getNik()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', ''),
		);
		$mresult = $this->m_pegawai->getNik($params);
		echo json_encode($mresult);
	}

	function crudRiwayatActingAs()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => ifunsetempty($_POST, 'nourut', null),
			'v_jabatan1' => ifunsetempty($_POST, 'jabatanid1', null),
			'v_levelid1' => ifunsetempty($_POST, 'levelid1', null),
			'v_golongan1' => ifunsetempty($_POST, 'golongan1', null),
			'v_satkerid1' => ifunsetempty($_POST, 'satkerid1', null),
			'v_lokasikerja1' => ifunsetempty($_POST, 'lokasiid1', null),
			'v_jabatan2' => ifunsetempty($_POST, 'jabatan2ID', null),
			'v_levelid2' => ifunsetempty($_POST, 'levelid2', null),
			'v_golongan2' => ifunsetempty($_POST, 'golongan2', null),
			'v_satkerid2' => ifunsetempty($_POST, 'satkerid2', null),
			'v_lokasikerja2' => ifunsetempty($_POST, 'lokasiid2', null),
			'v_tglmulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_tglakhir' => ifunsetempty($_POST, 'tglakhir', null),
			'v_keterangan' => ifunsetempty($_POST, 'keterangan', null),
			'v_actingas' => ifunsetempty($_POST, 'actingas', null),
		);

		$params2 = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null),
			'v_nourut' => 1,
			'v_jabatan' => ifunsetempty($_POST, 'jabatan2ID', null),
			'v_levelid' => ifunsetempty($_POST, 'levelid2', null),
			'v_golongan' => ifunsetempty($_POST, 'golongan2', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid2', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasiid2', null),
		);

		if ($flag == '1') {
			unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatActingAs($params);
			$mresult = $this->m_pegawai->updRiwayatJabatanAct($params2);
		} else {
			$mresult = $this->m_pegawai->updRiwayatActingAs($params);
			$mresult = $this->m_pegawai->updRiwayatJabatanAct($params2);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatActingAs()
	{

		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatActingAs($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Acting As */

	/* Data Mutasi Promosi */
	function getMutasiPromosi()
	{
		$params = array(
			'v_pegawaiid' => ifunsetempty($_POST, 'pegawaiid', null)
		);
		$mresult = $this->m_pegawai->getMutasiPromosi($params);
		$result = array('success' => true, 'data' => $mresult['data']);
		echo json_encode($result);
	}

	function crudRiwayatMutasiPromosi()
	{
		$flag = ifunsetempty($_POST, 'flag', '1');
		$pegawaiid = ifunsetempty($_POST, 'pegawaiid', null);
		$params = array(
			'v_pegawaiid' => $pegawaiid,
			'v_jabatan1' => ifunsetempty($_POST, 'jabatanid1', null),
			'v_levelid1' => ifunsetempty($_POST, 'levelid1', null),
			'v_golongan1' => ifunsetempty($_POST, 'golongan1', null),
			'v_satkerid1' => ifunsetempty($_POST, 'satkerid1', null),
			'v_lokasikerja1' => ifunsetempty($_POST, 'lokasiid1', null),
			'v_jabatan2' => ifunsetempty($_POST, 'jabatanid2', null),
			'v_levelid2' => ifunsetempty($_POST, 'levelid2', null),
			'v_golongan2' => ifunsetempty($_POST, 'golongan2', null),
			'v_satkerid2' => ifunsetempty($_POST, 'satkerid2', null),
			'v_lokasikerja2' => ifunsetempty($_POST, 'lokasiid2', null),
			'v_tglmulai' => ifunsetempty($_POST, 'tglmulai', null),
			'v_tglakhir' => ifunsetempty($_POST, 'tglakhir', null),
			'v_keterangan' => ifunsetempty($_POST, 'keterangan', null),
			'v_mutasipromosi' => ifunsetempty($_POST, 'mutasipromosi', null),
		);

		$params2 = array(
			'v_pegawaiid' => $pegawaiid,
			'v_nourut' => 1,
			'v_jabatan' => ifunsetempty($_POST, 'jabatanid2', null),
			'v_levelid' => ifunsetempty($_POST, 'levelid2', null),
			'v_golongan' => ifunsetempty($_POST, 'golongan2', null),
			'v_satkerid' => ifunsetempty($_POST, 'satkerid2', null),
			'v_lokasiid' => ifunsetempty($_POST, 'lokasiid2', null),
		);

		if ($flag == '1') {
			// unset($params['v_nourut']);
			$mresult = $this->m_pegawai->addRiwayatMutasiPromosi($params);
			$mresult = $this->m_pegawai->updRiwayatJabatanMP($params2);
		} else {
			$mresult = $this->m_pegawai->updRiwayatMutasiPromosi($params);
			$mresult = $this->m_pegawai->updRiwayatJabatanMP($params2);
		}

		if ($mresult) {
			$result = array('success' => true, 'message' => 'Data berhasil ditambah');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal ditambah');
		}
		echo json_encode($result);
	}

	function delRiwayatMutasiPromosi()
	{
		$params = array();
		$params = json_decode($this->input->post('params'), true);

		$o = 0;
		foreach ($params as $r) {
			$mresult = $this->m_pegawai->delRiwayatMutasiPromosi($r);
			if ($mresult)
				$o++;
		}
		if ($o > 0) {
			$result = array('success' => true, 'message' => 'Data berhasil dihapus');
		} else {
			$result = array('success' => false, 'message' => 'Data gagal dihapus');
		}
		echo json_encode($result);
	}
	/* Data Mutasi Promosi */
	function cetak($opt)
	{
		if ($opt == 'daftar') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_nik' => ifunsetemptybase64($_GET, 'nik', null),
				'v_nama' => ifunsetemptybase64($_GET, 'nama', null),
				'v_statuspegawai' => ifunsetemptybase64($_GET, 'statuspegawai', null),
				'v_jeniskelamin' => ifunsetemptybase64($_GET, 'jeniskelamin', null),
				'v_tglmulai' => ifunsetemptybase64($_GET, 'tglmulai', null),
				'v_tglselesai' => ifunsetemptybase64($_GET, 'tglselesai', null),
				'v_start' => '0',
				'v_limit' => '100000000',
			);
			$mresult = $this->m_pegawai->getListPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "DAFTARPEGAWAI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "DAFTARPEGAWAI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'identitas') {
			$params = array(
				'v_pegawaiid' => ifunsetemptybase64($_GET, 'pegawaiid', ''),
			);
			$mresult = $this->m_pegawai->getreportpegawaiByID($params);
			$mresult_rp = $this->m_pegawai->getRiwayatPendidikan($params);
			$mresult_kel = $this->m_pegawai->getRiwayatKeluarga($params);
			$mresult_rk = $this->m_pegawai->getRiwayatPengalamanKerja($params);
			$mresult_as = $this->m_pegawai->getMutasiPromosi($params);
			$mresult_rpen = $this->m_pegawai->getRiwayatPenyakit($params);
			$mresult_ragp = $this->m_pegawai->getRiwayatAGP($params);
			$mresult_rid = $this->m_pegawai->getRiwayatIndiplisiner($params);
			$mresult_rpa = $this->m_pegawai->getRiwayatPA($params);
			$mresult_rkursus = $this->m_pegawai->getRiwayatKursus($params);
			$mresult_rkea = $this->m_pegawai->getRiwayatKeahlian($params);
			$mresult_rkct = $this->m_pegawai->getRiwayatCatatanTambahan($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "IDENTITASPEGAWAI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('peg', $mresult['data']);
			$TBS->MergeBlock('rec_rp', $mresult_rp['data']);
			$TBS->MergeBlock('rec_kel', $mresult_kel['data']);
			$TBS->MergeBlock('rec_rk', $mresult_rk['data']);
			$TBS->MergeBlock('rec_as', $mresult_as['data']);
			$TBS->MergeBlock('rec_rpen', $mresult_rpen['data']);
			$TBS->MergeBlock('rec_ragp', $mresult_ragp['data']);
			$TBS->MergeBlock('rec_rid', $mresult_rid['data']);
			$TBS->MergeBlock('rec_rpa', $mresult_rpa['data']);
			$TBS->MergeBlock('rec_rkursus', $mresult_rkursus['data']);
			$TBS->MergeBlock('rec_rkea', $mresult_rkea['data']);
			$TBS->MergeBlock('rec_rkct', $mresult_rkct['data']);

			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "IDENTITASPEGAWAI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'identitasword') {
			$params = array(
				'v_pegawaiid' => ifunsetemptybase64($_GET, 'pegawaiid', ''),
			);
			$identitaspegawai = $this->m_pegawai->getPegawaiByID($params);
			$mresult = $this->m_pegawai->getreportpegawaiByID($params);
			$mresult_rp = $this->m_pegawai->getRiwayatPendidikan($params);
			// riwayat keluarga
			$mresult_kelinti = $this->m_pegawai->getRiwayatKeluargaInti($params);
			$mresult_kelbesar = $this->m_pegawai->getRiwayatKeluargaBesar($params);
			// riwayat pengalaman kerja
			$mresult_rk = $this->m_pegawai->getRiwayatPengalamanKerja($params);
			$mresult_as = $this->m_pegawai->getMutasiPromosi($params);
			$mresult_rpen = $this->m_pegawai->getRiwayatPenyakit($params);
			$mresult_ragp = $this->m_pegawai->getRiwayatAGP($params);
			$mresult_rid = $this->m_pegawai->getRiwayatIndiplisiner($params);
			$mresult_rpa = $this->m_pegawai->getRiwayatPA($params);
			$mresult_rkursus = $this->m_pegawai->getRiwayatKursus($params);
			$mresult_rkea = $this->m_pegawai->getRiwayatKeahlian($params);
			$mresult_rkct = $this->m_pegawai->getRiwayatCatatanTambahan($params);
			// Update 04.05.2021
			$mresult_rjbt = $this->m_pegawai->getRiwayatJabatan($params);
			$mresult_rrek = $this->m_pegawai->getRiwayatRekening($params);
			$mresult_rbhs = $this->m_pegawai->getRiwayatBahasa($params);
			$mresult_rskt = $this->m_pegawai->getRiwayatPenyakit($params);

			// die(var_dump($mresult_rskt));

			$path = config_item('siap_upload_foto_path');
			$foto = $identitaspegawai['firstrow']['foto'];
			$image = '';
			if (!empty($foto)) {
				if (file_exists($path . $foto)) {
					$image = $path . $foto;
				}
			}

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "IDENTITASPEGAWAI_WR.docx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';

			$TBS->MergeField('header', array(
				'foto' => $image,
			));

			$TBS->MergeField('header', array());
			$TBS->MergeBlock('peg', $mresult['data']);
			$TBS->MergeBlock('rec_rp', $mresult_rp['data']);
			$TBS->MergeBlock('rec_kelinti', $mresult_kelinti['data']);
			$TBS->MergeBlock('rec_kelbesar', $mresult_kelbesar['data']);
			$TBS->MergeBlock('rec_rk', $mresult_rk['data']);
			$TBS->MergeBlock('rec_as', $mresult_as['data']);
			$TBS->MergeBlock('rec_rpen', $mresult_rpen['data']);
			$TBS->MergeBlock('rec_ragp', $mresult_ragp['data']);
			$TBS->MergeBlock('rec_rid', $mresult_rid['data']);
			$TBS->MergeBlock('rec_rpa', $mresult_rpa['data']);
			$TBS->MergeBlock('rec_rkursus', $mresult_rkursus['data']);
			$TBS->MergeBlock('rec_rkea', $mresult_rkea['data']);
			$TBS->MergeBlock('rec_rkct', $mresult_rkct['data']);
			// Update 04.05.2021
			$TBS->MergeBlock('rec_rjbt', $mresult_rjbt['data']);
			$TBS->MergeBlock('rec_rrek', $mresult_rrek['data']);
			$TBS->MergeBlock('rec_rbhs', $mresult_rbhs['data']);
			$TBS->MergeBlock('rec_rskt', $mresult_rskt['data']);


			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "IDENTITASPEGAWAI_WR.docx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'identitasexcel') {
			$params = array(
				'v_pegawaiid' => ifunsetemptybase64($_GET, 'pegawaiid', ''),
			);
			$identitaspegawai = $this->m_pegawai->getPegawaiByID($params);
			$mresult = $this->m_pegawai->getreportpegawaiByID($params);
			$mresult_rp = $this->m_pegawai->getRiwayatPendidikan($params);
			// riwayat keluarga
			$mresult_kelinti = $this->m_pegawai->getRiwayatKeluargaInti($params);
			$mresult_kelbesar = $this->m_pegawai->getRiwayatKeluargaBesar($params);
			// riwayat pengalaman kerja
			$mresult_rk = $this->m_pegawai->getRiwayatPengalamanKerja($params);
			$mresult_as = $this->m_pegawai->getMutasiPromosi($params);
			$mresult_rpen = $this->m_pegawai->getRiwayatPenyakit($params);
			$mresult_ragp = $this->m_pegawai->getRiwayatAGP($params);
			$mresult_rid = $this->m_pegawai->getRiwayatIndiplisiner($params);
			$mresult_rpa = $this->m_pegawai->getRiwayatPA($params);
			$mresult_rkursus = $this->m_pegawai->getRiwayatKursus($params);
			$mresult_rkea = $this->m_pegawai->getRiwayatKeahlian($params);
			$mresult_rkct = $this->m_pegawai->getRiwayatCatatanTambahan($params);
			// Update 04.05.2021
			$mresult_rjbt = $this->m_pegawai->getRiwayatJabatan($params);
			$mresult_rrek = $this->m_pegawai->getRiwayatRekening($params);
			$mresult_rbhs = $this->m_pegawai->getRiwayatBahasa($params);
			$mresult_rskt = $this->m_pegawai->getRiwayatPenyakit($params);

			$path = config_item('siap_upload_foto_path');
			$foto = $identitaspegawai['firstrow']['foto'];
			$image = '';
			if (!empty($foto)) {
				if (file_exists($path . $foto)) {
					$image = $path . $foto;
				}
			}

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "IDENTITASPEGAWAI_EXE.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';

			$TBS->MergeField('foto', array(
				'foto' => $image,
			));

			$gambar = $TBS->MergeField('foto', array(
				'foto' => $image,
			));

			// $workbook = new COM("EasyXLS.ExcelDocument");
			// $workbook->easy_getSheetAt(0)->easy_addImage_5($gambar, "E15");

			$TBS->MergeField('header', array());
			$TBS->MergeBlock('peg', $mresult['data']);
			$TBS->MergeBlock('rec_rp', $mresult_rp['data']);
			$TBS->MergeBlock('rec_kelinti', $mresult_kelinti['data']);
			$TBS->MergeBlock('rec_kelbesar', $mresult_kelbesar['data']);
			$TBS->MergeBlock('rec_rk', $mresult_rk['data']);
			$TBS->MergeBlock('rec_as', $mresult_as['data']);
			$TBS->MergeBlock('rec_rpen', $mresult_rpen['data']);
			$TBS->MergeBlock('rec_ragp', $mresult_ragp['data']);
			$TBS->MergeBlock('rec_rid', $mresult_rid['data']);
			$TBS->MergeBlock('rec_rpa', $mresult_rpa['data']);
			$TBS->MergeBlock('rec_rkursus', $mresult_rkursus['data']);
			$TBS->MergeBlock('rec_rkea', $mresult_rkea['data']);
			$TBS->MergeBlock('rec_rkct', $mresult_rkct['data']);
			// Update 04.05.2021
			$TBS->MergeBlock('rec_rjbt', $mresult_rjbt['data']);
			$TBS->MergeBlock('rec_rrek', $mresult_rrek['data']);
			$TBS->MergeBlock('rec_rbhs', $mresult_rbhs['data']);
			$TBS->MergeBlock('rec_rskt', $mresult_rskt['data']);

			$nama1 = $identitaspegawai['firstrow']['namadepan'];
			$nama2 = $identitaspegawai['firstrow']['namabelakang'];
			$nama3 = $identitaspegawai['firstrow']['namakeluarga'];
			$nama = str_replace(' ', '', $nama1 . $nama2 . $nama3);

			// $file_name = str_replace('.', '_' . $nama . '_' . date('Y-m-d') . '.', "IDENTITASPEGAWAI_EXE_New.xlsx");
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "IDENTITASPEGAWAI_EXE.xlsx");
			$file_name = str_replace('.', $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		} else if ($opt == 'cetakexcel') {
			// $q = $this->session->userdata;
			// var_dump($q);

			$params = array(
				'v_pegawaiid' => ifunsetemptybase64($_GET, 'pegawaiid', ''),
			);
			$params2 = array(
				'v_pegawaiid' => $this->session->userdata('pegawaiid'),
			);
			$identitaspegawai = $this->m_pegawai->getPegawaiByID($params);
			$mresult = $this->m_pegawai->getreportpegawaiByID($params);
			$mresult_rp = $this->m_pegawai->getRiwayatPendidikan($params);
			// riwayat keluarga
			$mresult_kelinti = $this->m_pegawai->getRiwayatKeluargaInti($params);
			$mresult_kelbesar = $this->m_pegawai->getRiwayatKeluargaBesar($params);
			// riwayat pengalaman kerja
			$mresult_rk = $this->m_pegawai->getRiwayatPengalamanKerja($params);
			$mresult_as = $this->m_pegawai->getMutasiPromosi($params);
			$mresult_rpen = $this->m_pegawai->getRiwayatPenyakit($params);
			$mresult_ragp = $this->m_pegawai->getRiwayatAGP($params);
			$mresult_rid = $this->m_pegawai->getRiwayatIndiplisiner($params);
			$mresult_rpa = $this->m_pegawai->getRiwayatPA($params);
			$mresult_rkursus = $this->m_pegawai->getRiwayatKursus($params);
			$mresult_rkea = $this->m_pegawai->getRiwayatKeahlian($params);
			$mresult_rkct = $this->m_pegawai->getRiwayatCatatanTambahan($params);
			$mresult_by = $this->m_pegawai->getdataprint($params2);
			// $mresult_by = $this->session->userdata;
			// Update 04.05.2021
			$mresult_rjbt = $this->m_pegawai->getRiwayatJabatan($params);
			$mresult_rrek = $this->m_pegawai->getRiwayatRekening($params);
			$mresult_rbhs = $this->m_pegawai->getRiwayatBahasa($params);
			$mresult_rskt = $this->m_pegawai->getRiwayatPenyakit($params);
			$path = config_item('siap_upload_foto_path');
			$foto = $identitaspegawai['firstrow']['foto'];
			$shio = $identitaspegawai['firstrow']['namashio'];
			$image2 = $path . 'no_image.jpg';
			$image = '';
			if (!empty($foto)) {
				if (file_exists($path . $foto)) {
					$image = $path . $foto;
				}
			}
			$imageshio = '';
			if (!empty($shio)) {
				if (file_exists($path . 'gambarshio/' . $shio . '.png')) {
					$imageshio = $path . 'gambarshio/' . $shio . '.png';
				}
			}

			// Tambah Gambar Pegawai
			$objPHPExcel = PHPExcel_IOFactory::load(config_item("siap_tpl_path") . "IDENTITASPEGAWAI_EXE_NewShio.xlsx"); //Template Asli jangan tiban
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing2 = new PHPExcel_Worksheet_Drawing();
			if ($image == NULL) {
				$objDrawing->setPath($image2);
			} else {
				$objDrawing->setPath($image);
			}
			if ($imageshio == NULL) {
				$objDrawing2->setPath($image2);
			} else {
				$objDrawing2->setPath($imageshio);
			}
			$objDrawing->setCoordinates('F14');
			$objDrawing2->setCoordinates('H5');
			$objDrawing->setResizeProportional(true);
			$objDrawing2->setResizeProportional(true);
			$objDrawing->setWidth(650);
			$objDrawing2->setWidth(400);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			$objDrawing2->setWorksheet($objPHPExcel->getActiveSheet());

			$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
			$objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(config_item("siap_tpl_path") . "pegawai.xlsx");

			// Tambah Data Pegawai, Template yang akan dipakai, sudah ada gambar Pegawai & Shio
			$tempexcel = config_item("siap_tpl_path") . "pegawai.xlsx";
			$TBS = new clsTinyButStrong;
			$TBS->Plugin(TBS_INSTALL, 'clsOpenTBS');
			$TBS->LoadTemplate($tempexcel);
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';

			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec_by', $mresult_by['data']);
			// $TBS->MergeBlock('by', );
			$TBS->MergeBlock('peg', $mresult['data']);
			$TBS->MergeBlock('rec_rp', $mresult_rp['data']);
			$TBS->MergeBlock('rec_kelinti', $mresult_kelinti['data']);
			$TBS->MergeBlock('rec_kelbesar', $mresult_kelbesar['data']);
			$TBS->MergeBlock('rec_rk', $mresult_rk['data']);
			$TBS->MergeBlock('rec_as', $mresult_as['data']);
			$TBS->MergeBlock('rec_rpen', $mresult_rpen['data']);
			$TBS->MergeBlock('rec_ragp', $mresult_ragp['data']);
			$TBS->MergeBlock('rec_rid', $mresult_rid['data']);
			$TBS->MergeBlock('rec_rpa', $mresult_rpa['data']);
			$TBS->MergeBlock('rec_rkursus', $mresult_rkursus['data']);
			$TBS->MergeBlock('rec_rkea', $mresult_rkea['data']);
			$TBS->MergeBlock('rec_rkct', $mresult_rkct['data']);
			// Update 04.05.2021
			$TBS->MergeBlock('rec_rjbt', $mresult_rjbt['data']);
			$TBS->MergeBlock('rec_rrek', $mresult_rrek['data']);
			$TBS->MergeBlock('rec_rbhs', $mresult_rbhs['data']);
			$TBS->MergeBlock('rec_rskt', $mresult_rskt['data']);

			$nama1 = $identitaspegawai['firstrow']['namadepan'];
			$nama2 = $identitaspegawai['firstrow']['namabelakang'];
			$nama3 = $identitaspegawai['firstrow']['namakeluarga'];
			$nama = str_replace(' ', '', $nama1 . $nama2 . $nama3);

			$file_name = str_replace('pegawai', $nama . '_' . date('Y-m-d'), "pegawai.xlsx");
			$file_name = str_replace('.', $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		}
	}

	function cetakCuti($opt)
	{

		if ($opt == 'daftar') {
			$params = array(
				'v_satkerid' => ifunsetemptybase64($_GET, 'satkerid', ''),
				'v_nik' => ifunsetemptybase64($_GET, 'nik', null),
				'v_nama' => ifunsetemptybase64($_GET, 'nama', null),
				'v_statuspegawai' => ifunsetemptybase64($_GET, 'statuspegawai', null),
				'v_jeniskelamin' => ifunsetemptybase64($_GET, 'jeniskelamin', null),
				'v_tglmulai' => ifunsetemptybase64($_GET, 'tglmulai', null),
				'v_tglselesai' => ifunsetemptybase64($_GET, 'tglselesai', null),
				'v_start' => '0',
				'v_limit' => '100000000',
			);
			$mresult = $this->m_pegawai->getListPegawai($params);

			$TBS = $this->template_cetak->createNew('xlsx', config_item("siap_tpl_path") . "DAFTARPEGAWAI.xlsx");
			$suffix = (isset($_POST['suffix']) && (trim($_POST['suffix']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['suffix']) : '';
			$TBS->MergeField('header', array());
			$TBS->MergeBlock('rec', $mresult['data']);
			$file_name = str_replace('.', '_' . date('Y-m-d') . '.', "DAFTARCUTI.xlsx");
			$file_name = str_replace('.', '_' . $suffix . '.', $file_name);
			$TBS->Show(OPENTBS_DOWNLOAD, $file_name);
		}
	}

	function getMutasiPromosiByNik()
	{
		$params = array(
			'v_nik' => ifunsetempty($_POST, 'nik', ''),
		);
		$mresult = $this->m_pegawai->getMutasiPromosiByNik($params);
		echo json_encode($mresult);
	}
}
