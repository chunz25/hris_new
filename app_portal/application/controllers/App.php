<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class App extends Portal_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_app');
		$this->load->library(['form_validation', 'PHPExcel/IOFactory', 'pagination']);
	}

	public function index()
	{
		if (empty($this->session->userdata('username'))) {
			redirect(config_item('url_index') . '/login');
		}
		$this->view_content();
	}

	private function view_content()
	{
		$modul = $this->session->userdata('modul');
		$data['modul'] = $modul;

		// die(var_dump($data['modul']));

		$this->load->view('portal/v_portal', $data);
	}

	public function uploadDataPegawai()
	{
		$config = [
			'base_url' => SITE_URL() . "/app/uploadDataPegawai/", // Base URL
			'total_rows' => $this->M_app->record_count(), // Record count
			'per_page' => 25, // Posts per page
			'uri_segment' => 3 // URI segment for pagination
		];

		// Initialize pagination
		$this->pagination->initialize($config);
		$page = $this->uri->segment(3, 0); // Default to 0 if not set
		$data['show'] = $this->M_app->getData($config['per_page'], $page);
		$data['links'] = $this->pagination->create_links();

		$this->load->view('portal/v_csv', $data);
	}

	public function upload()
	{
		$fileName = $_FILES['file']['name'];

		$config['upload_path'] = './assets/';
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'xls|xlsx|csv';
		$config['max_size'] = 10000;

		$this->load->library('upload');
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('file'))
			$this->upload->display_errors();

		$media = $this->upload->data('file');
		$inputFileName = './assets/' . $media['file_name'];

		if (filesize($inputFileName) > 1300000) {
			redirect('app/gagal');
		} else {

			try {
				$inputFileType = IOFactory::identify($inputFileName);
				$objReader = IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			} catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
			}

			$sheet = $objPHPExcel->getSheet(0);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();

			for ($row = 5; $row <= $highestRow; $row++) { //  Read a row of data into an array
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

				//tglpermanent
				$tgl = !empty($rowData[0][18]) ? $rowData[0][18] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tglpermanent = date('d/m/Y', $unix_date);
				} else {
					$tglpermanent = NULL;
				}

				//tglmulai DateOfJoint
				$tgl = !empty($rowData[0][19]) ? $rowData[0][19] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tglmulai = date('d/m/Y', $unix_date);
				} else {
					$tglmulai = NULL;
				}

				//tglakhirkontrak EOC
				$tgl = !empty($rowData[0][20]) ? $rowData[0][20] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tglakhirkontrak = date('d/m/Y', $unix_date);
				} else {
					$tglakhirkontrak = NULL;
				}

				//tglselesai TanggalBerhenti
				$tgl = !empty($rowData[0][21]) ? $rowData[0][21] : null;
				if ($tgl != null) {
					if (gettype($tgl) == 'string') {
						$tglselesai = NULL;
					} else {
						$unix_date = ($tgl - 25569) * 86400;
						$tglselesai = date('d/m/Y', $unix_date);
					}
				} else {
					$tglselesai = NULL;
				}

				//tgllahir TglLahir
				$tgl = !empty($rowData[0][34]) ? $rowData[0][34] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahir = date('d/m/Y', $unix_date);
				} else {
					$tgllahir = NULL;
				}

				//tglnikah
				$tgl = !empty($rowData[0][48]) ? $rowData[0][48] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tglnikah = date('d/m/Y', $unix_date);
				} else {
					$tglnikah = NULL;
				}

				//tgllahirpasangan
				$tgl = !empty($rowData[0][51]) ? $rowData[0][51] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirpasangan = date('d/m/Y', $unix_date);
				} else {
					$tgllahirpasangan = NULL;
				}

				//tgllahiranak1
				$tgl = !empty($rowData[0][57]) ? $rowData[0][57] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahiranak1 = date('d/m/Y', $unix_date);
				} else {
					$tgllahiranak1 = NULL;
				}

				//tgllahiranak2
				$tgl = !empty($rowData[0][62]) ? $rowData[0][62] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahiranak2 = date('d/m/Y', $unix_date);
				} else {
					$tgllahiranak2 = NULL;
				}

				//tgllahiranak3
				$tgl = !empty($rowData[0][67]) ? $rowData[0][67] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahiranak3 = date('d/m/Y', $unix_date);
				} else {
					$tgllahiranak3 = NULL;
				}

				//tgllahiranak4
				$tgl = !empty($rowData[0][72]) ? $rowData[0][72] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahiranak4 = date('d/m/Y', $unix_date);
				} else {
					$tgllahiranak4 = NULL;
				}

				//tgllahiribu
				$tgl = !empty($rowData[0][77]) ? $rowData[0][77] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahiribu = date('d/m/Y', $unix_date);
				} else {
					$tgllahiribu = NULL;
				}

				//tgllahirayah
				$tgl = !empty($rowData[0][83]) ? $rowData[0][83] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirayah = date('d/m/Y', $unix_date);
				} else {
					$tgllahirayah = NULL;
				}

				//tgllahirsdr1
				$tgl = !empty($rowData[0][89]) ? $rowData[0][89] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirsdr1 = date('d/m/Y', $unix_date);
				} else {
					$tgllahirsdr1 = NULL;
				}

				//tgllahirsdr2
				$tgl = !empty($rowData[0][95]) ? $rowData[0][95] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirsdr2 = date('d/m/Y', $unix_date);
				} else {
					$tgllahirsdr2 = NULL;
				}

				//tgllahirsdr3
				$tgl = !empty($rowData[0][101]) ? $rowData[0][101] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirsdr3 = date('d/m/Y', $unix_date);
				} else {
					$tgllahirsdr3 = NULL;
				}

				//tgllahirsdr4
				$tgl = !empty($rowData[0][107]) ? $rowData[0][107] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirsdr4 = date('d/m/Y', $unix_date);
				} else {
					$tgllahirsdr4 = NULL;
				}

				//tgllahirsdr5
				$tgl = !empty($rowData[0][113]) ? $rowData[0][113] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirsdr5 = date('d/m/Y', $unix_date);
				} else {
					$tgllahirsdr5 = NULL;
				}

				//tgllahirsdr6
				$tgl = !empty($rowData[0][119]) ? $rowData[0][119] : null;
				if ($tgl != null) {
					$unix_date = ($tgl - 25569) * 86400;
					$tgllahirsdr6 = date('d/m/Y', $unix_date);
				} else {
					$tgllahirsdr6 = NULL;
				}

				//masaberlakuktp
				$tgl = !empty($rowData[0][130]) ? $rowData[0][130] : null;
				if ($tgl != null) {
					if (gettype($tgl) == 'string') {
						$masaberlakuktp = NULL;
					} else {
						$unix_date = ($tgl - 25569) * 86400;
						$masaberlakuktp = date('d/m/Y', $unix_date);
					}
				} else {
					$masaberlakuktp = NULL;
				}

				$params = array(
					// Data Pegawai
					'v_nik' => !empty($rowData[0][1]) ? $rowData[0][1] : null,
					'v_fullnama' => !empty($rowData[0][2]) ? $rowData[0][2] : null,
					'v_namadepan' => !empty($rowData[0][3]) ? $rowData[0][3] : null,
					'v_namatengah' => !empty($rowData[0][4]) ? $rowData[0][4] : null,
					'v_namabelakang' => !empty($rowData[0][5]) ? $rowData[0][5] : null,

					'v_direktorat' => !empty($rowData[0][6]) ? $rowData[0][6] : null,
					'v_divisi' => !empty($rowData[0][7]) ? $rowData[0][7] : null,
					'v_departement' => !empty($rowData[0][8]) ? $rowData[0][8] : null,
					'v_seksi' => !empty($rowData[0][9]) ? $rowData[0][9] : null,
					'v_subseksi' => !empty($rowData[0][10]) ? $rowData[0][10] : null,

					'v_kodelokasi' => !empty($rowData[0][11]) ? $rowData[0][11] : null,
					'v_lokasikerja' => !empty($rowData[0][12]) ? $rowData[0][12] : null,
					'v_levelid' => !empty($rowData[0][13]) ? $rowData[0][13] : null,
					'v_levelname' => !empty($rowData[0][14]) ? $rowData[0][14] : null,
					'v_jabatanid' => !empty($rowData[0][15]) ? $rowData[0][15] : null,
					'v_actingas' => !empty($rowData[0][16]) ? $rowData[0][16] : null,
					'v_statuspegawaiid' => !empty($rowData[0][17]) ? $rowData[0][17] : null,

					'v_tglpermanent' => $tglpermanent,
					'v_tglmulai' => $tglmulai,
					'v_tglakhirkontrak' => $tglakhirkontrak,
					'v_tglselesai' => $tglselesai,
					'v_lamakerja' => null,

					'v_probation' => null,
					'v_kontrak1' => null,
					'v_kontrak2' => null,
					'v_kontrak3' => null,
					'v_tetap' => null,
					'v_tglpromosi' => null,
					'v_deplama' => null,
					'v_depbaru' => null,

					'v_jeniskelamin' => !empty($rowData[0][31]) ? $rowData[0][31] : null,
					'v_agamaid' => !empty($rowData[0][32]) ? $rowData[0][32] : null,
					'v_tempatlahir' => !empty($rowData[0][33]) ? $rowData[0][33] : null,
					'v_tgllahir' => $tgllahir,
					'v_usia' => null,
					'v_shio' => !empty($rowData[0][36]) ? $rowData[0][36] : null,
					'v_unsur' => !empty($rowData[0][37]) ? $rowData[0][37] : null,
					'v_alamatktp' => !empty($rowData[0][38]) ? $rowData[0][38] : null,
					'v_alamat' => !empty($rowData[0][39]) ? $rowData[0][39] : null,
					'v_telp' => !empty($rowData[0][40]) ? $rowData[0][40] : null,
					'v_hp' => !empty($rowData[0][41]) ? $rowData[0][41] : null,

					'v_pendidikan' => !empty($rowData[0][42]) ? $rowData[0][42] : null,
					'v_namasekolah' => !empty($rowData[0][43]) ? $rowData[0][43] : null,
					'v_jurusan' => !empty($rowData[0][44]) ? $rowData[0][44] : null,
					'v_ipk' => !empty($rowData[0][45]) ? $rowData[0][45] : null,
					'v_tahunkeluar' => !empty($rowData[0][46]) ? $rowData[0][46] : null,

					// Data Keluarga
					'v_statusnikah' => !empty($rowData[0][47]) ? $rowData[0][47] : null,
					'v_tglnikah' => $tglnikah,
					'v_pasangan1' => !empty($rowData[0][49]) ? $rowData[0][49] : null,
					'v_tmptlahirpasangan' => !empty($rowData[0][50]) ? $rowData[0][50] : null,
					'v_tgllahirpasangan' => $tgllahirpasangan,
					'v_pendpasangan' => !empty($rowData[0][52]) ? $rowData[0][52] : null,
					'v_kerjapasangan' => !empty($rowData[0][53]) ? $rowData[0][53] : null,
					'v_alamatpasangan' => !empty($rowData[0][54]) ? $rowData[0][54] : null,

					'v_anak1' => !empty($rowData[0][55]) ? $rowData[0][55] : null,
					'v_tmptlahiranak1' => !empty($rowData[0][56]) ? $rowData[0][56] : null,
					'v_tgllahiranak1' => $tgllahiranak1,
					'v_kerjaanak1' => !empty($rowData[0][58]) ? $rowData[0][58] : null,
					'v_pendanak1' => !empty($rowData[0][59]) ? $rowData[0][59] : null,

					'v_anak2' => !empty($rowData[0][60]) ? $rowData[0][60] : null,
					'v_tmptlahiranak2' => !empty($rowData[0][61]) ? $rowData[0][61] : null,
					'v_tgllahiranak2' => $tgllahiranak2,
					'v_kerjaanak2' => !empty($rowData[0][63]) ? $rowData[0][63] : null,
					'v_pendanak2' => !empty($rowData[0][64]) ? $rowData[0][64] : null,

					'v_anak3' => !empty($rowData[0][65]) ? $rowData[0][65] : null,
					'v_tmptlahiranak3' => !empty($rowData[0][66]) ? $rowData[0][66] : null,
					'v_tgllahiranak3' => $tgllahiranak3,
					'v_kerjaanak3' => !empty($rowData[0][68]) ? $rowData[0][68] : null,
					'v_pendanak3' => !empty($rowData[0][69]) ? $rowData[0][69] : null,

					'v_anak4' => !empty($rowData[0][70]) ? $rowData[0][70] : null,
					'v_tmptlahiranak4' => !empty($rowData[0][71]) ? $rowData[0][71] : null,
					'v_tgllahiranak4' => $tgllahiranak4,
					'v_kerjaanak4' => !empty($rowData[0][73]) ? $rowData[0][73] : null,
					'v_pendanak4' => !empty($rowData[0][74]) ? $rowData[0][74] : null,

					'v_ibu' => !empty($rowData[0][75]) ? $rowData[0][75] : null,
					'v_tmptlahiribu' => !empty($rowData[0][76]) ? $rowData[0][76] : null,
					'v_tgllahiribu' => $tgllahiribu,
					'v_kerjaibu' => !empty($rowData[0][78]) ? $rowData[0][78] : null,
					'v_pendibu' => !empty($rowData[0][79]) ? $rowData[0][79] : null,
					'v_alamatibu' => !empty($rowData[0][80]) ? $rowData[0][80] : null,

					'v_ayah' => !empty($rowData[0][81]) ? $rowData[0][81] : null,
					'v_tmptlahirayah' => !empty($rowData[0][82]) ? $rowData[0][82] : null,
					'v_tgllahirayah' => $tgllahirayah,
					'v_kerjaayah' => !empty($rowData[0][84]) ? $rowData[0][84] : null,
					'v_pendayah' => !empty($rowData[0][85]) ? $rowData[0][85] : null,
					'v_alamatayah' => !empty($rowData[0][86]) ? $rowData[0][86] : null,

					'v_sdr1' => !empty($rowData[0][87]) ? $rowData[0][87] : null,
					'v_tmptlahirsdr1' => !empty($rowData[0][88]) ? $rowData[0][88] : null,
					'v_tgllahirsdr1' => $tgllahirsdr1,
					'v_kerjasdr1' => !empty($rowData[0][90]) ? $rowData[0][90] : null,
					'v_pendsdr1' => !empty($rowData[0][91]) ? $rowData[0][91] : null,
					'v_alamatsdr1' => !empty($rowData[0][92]) ? $rowData[0][92] : null,

					'v_sdr2' => !empty($rowData[0][93]) ? $rowData[0][93] : null,
					'v_tmptlahirsdr2' => !empty($rowData[0][94]) ? $rowData[0][94] : null,
					'v_tgllahirsdr2' => $tgllahirsdr2,
					'v_kerjasdr2' => !empty($rowData[0][96]) ? $rowData[0][96] : null,
					'v_pendsdr2' => !empty($rowData[0][97]) ? $rowData[0][97] : null,
					'v_alamatsdr2' => !empty($rowData[0][98]) ? $rowData[0][98] : null,

					'v_sdr3' => !empty($rowData[0][99]) ? $rowData[0][99] : null,
					'v_tmptlahirsdr3' => !empty($rowData[0][100]) ? $rowData[0][100] : null,
					'v_tgllahirsdr3' => $tgllahirsdr3,
					'v_kerjasdr3' => !empty($rowData[0][102]) ? $rowData[0][102] : null,
					'v_pendsdr3' => !empty($rowData[0][103]) ? $rowData[0][103] : null,
					'v_alamatsdr3' => !empty($rowData[0][104]) ? $rowData[0][104] : null,

					'v_sdr4' => !empty($rowData[0][105]) ? $rowData[0][105] : null,
					'v_tmptlahirsdr4' => !empty($rowData[0][106]) ? $rowData[0][106] : null,
					'v_tgllahirsdr4' => $tgllahirsdr4,
					'v_kerjasdr4' => !empty($rowData[0][108]) ? $rowData[0][108] : null,
					'v_pendsdr4' => !empty($rowData[0][109]) ? $rowData[0][109] : null,
					'v_alamatsdr4' => !empty($rowData[0][110]) ? $rowData[0][110] : null,

					'v_sdr5' => !empty($rowData[0][111]) ? $rowData[0][111] : null,
					'v_tmptlahirsdr5' => !empty($rowData[0][112]) ? $rowData[0][112] : null,
					'v_tgllahirsdr5' => $tgllahirsdr5,
					'v_kerjasdr5' => !empty($rowData[0][114]) ? $rowData[0][114] : null,
					'v_pendsdr5' => !empty($rowData[0][115]) ? $rowData[0][115] : null,
					'v_alamatsdr5' => !empty($rowData[0][116]) ? $rowData[0][116] : null,

					'v_sdr6' => !empty($rowData[0][117]) ? $rowData[0][117] : null,
					'v_tmptlahirsdr6' => !empty($rowData[0][118]) ? $rowData[0][118] : null,
					'v_tgllahirsdr6' => $tgllahirsdr6,
					'v_kerjasdr6' => !empty($rowData[0][120]) ? $rowData[0][120] : null,
					'v_pendsdr6' => !empty($rowData[0][121]) ? $rowData[0][121] : null,
					'v_alamatsdr6' => !empty($rowData[0][122]) ? $rowData[0][122] : null,

					// Data Tambahan Pegawai
					'v_npwp' => !empty($rowData[0][123]) ? $rowData[0][123] : null,
					'v_jamsostek' => !empty($rowData[0][124]) ? $rowData[0][124] : null,
					'v_bpjskes' => !empty($rowData[0][125]) ? $rowData[0][125] : null,
					'v_askes' => !empty($rowData[0][126]) ? $rowData[0][126] : null,
					'v_paspor' => !empty($rowData[0][127]) ? $rowData[0][127] : null,
					'v_nokk' => !empty($rowData[0][128]) ? $rowData[0][128] : null,
					'v_noktp' => !empty($rowData[0][129]) ? $rowData[0][129] : null,
					'v_masaberlakuktp' => $masaberlakuktp,
					'v_namabank' => !empty($rowData[0][131]) ? $rowData[0][131] : null,
					'v_norek' => !empty($rowData[0][132]) ? $rowData[0][132] : null,
					'v_namarek' => !empty($rowData[0][133]) ? $rowData[0][133] : null,
					'v_namakontakdarurat' => !empty($rowData[0][134]) ? $rowData[0][134] : null,
					'v_telpkontakdarurat' => !empty($rowData[0][135]) ? $rowData[0][135] : null,
					'v_relasikontakdarurat' => !empty($rowData[0][136]) ? $rowData[0][136] : null,
					'v_email' => !empty($rowData[0][137]) ? $rowData[0][137] : null,
					'v_emailkantor' => !empty($rowData[0][138]) ? $rowData[0][138] : null,

					// Pengalaman Kerja
					'v_jabkerja1' => !empty($rowData[0][139]) ? $rowData[0][139] : null,
					'v_kantorkerja1' => !empty($rowData[0][140]) ? $rowData[0][140] : null,
					'v_masukkerja1' => !empty($rowData[0][141]) ? $rowData[0][141] : null,
					'v_keluarkerja1' => !empty($rowData[0][142]) ? $rowData[0][142] : null,
					'v_atasankerja1' => !empty($rowData[0][143]) ? $rowData[0][143] : null,
					'v_deskkerja1' => !empty($rowData[0][144]) ? $rowData[0][144] : null,
					'v_alasankerja1' => !empty($rowData[0][145]) ? $rowData[0][145] : null,

					'v_jabkerja2' => !empty($rowData[0][146]) ? $rowData[0][146] : null,
					'v_kantorkerja2' => !empty($rowData[0][147]) ? $rowData[0][147] : null,
					'v_masukkerja2' => !empty($rowData[0][148]) ? $rowData[0][148] : null,
					'v_keluarkerja2' => !empty($rowData[0][149]) ? $rowData[0][149] : null,
					'v_atasankerja2' => !empty($rowData[0][150]) ? $rowData[0][150] : null,
					'v_deskkerja2' => !empty($rowData[0][151]) ? $rowData[0][151] : null,
					'v_alasankerja2' => !empty($rowData[0][152]) ? $rowData[0][152] : null,

					'v_jabkerja3' => !empty($rowData[0][153]) ? $rowData[0][153] : null,
					'v_kantorkerja3' => !empty($rowData[0][154]) ? $rowData[0][154] : null,
					'v_masukkerja3' => !empty($rowData[0][155]) ? $rowData[0][155] : null,
					'v_keluarkerja3' => !empty($rowData[0][156]) ? $rowData[0][156] : null,
					'v_atasankerja3' => !empty($rowData[0][157]) ? $rowData[0][157] : null,
					'v_deskkerja3' => !empty($rowData[0][158]) ? $rowData[0][158] : null,
					'v_alasankerja3' => !empty($rowData[0][159]) ? $rowData[0][159] : null,

					'v_jabkerja4' => !empty($rowData[0][160]) ? $rowData[0][160] : null,
					'v_kantorkerja4' => !empty($rowData[0][161]) ? $rowData[0][161] : null,
					'v_masukkerja4' => !empty($rowData[0][162]) ? $rowData[0][162] : null,
					'v_keluarkerja4' => !empty($rowData[0][163]) ? $rowData[0][163] : null,
					'v_atasankerja4' => !empty($rowData[0][164]) ? $rowData[0][164] : null,
					'v_deskkerja4' => !empty($rowData[0][165]) ? $rowData[0][165] : null,
					'v_alasankerja4' => !empty($rowData[0][166]) ? $rowData[0][166] : null,

					'v_training' => !empty($rowData[0][167]) ? $rowData[0][167] : null,
					'v_periodetraining' => !empty($rowData[0][168]) ? $rowData[0][168] : null,
					'v_durasi' => !empty($rowData[0][169]) ? $rowData[0][169] : null,
					'v_trainer' => !empty($rowData[0][170]) ? $rowData[0][170] : null,
					'v_jenistraining' => !empty($rowData[0][171]) ? $rowData[0][171] : null,
					'v_foto' => null,

					// Keahlian
					'v_englishtulis' => !empty($rowData[0][173]) ? $rowData[0][173] : null,
					'v_englishlisan' => !empty($rowData[0][174]) ? $rowData[0][174] : null,
					'v_englishbaca' => !empty($rowData[0][175]) ? $rowData[0][175] : null,

					'v_mandarintulis' => !empty($rowData[0][176]) ? $rowData[0][176] : null,
					'v_mandarinlisan' => !empty($rowData[0][177]) ? $rowData[0][177] : null,
					'v_mandarinbaca' => !empty($rowData[0][178]) ? $rowData[0][178] : null,

					'v_bhslain' => !empty($rowData[0][179]) ? $rowData[0][179] : null,
					'v_keahlian' => !empty($rowData[0][180]) ? $rowData[0][180] : null,
					'v_keahliandesk' => !empty($rowData[0][181]) ? $rowData[0][181] : null,

					'v_hobby' => !empty($rowData[0][182]) ? $rowData[0][182] : null,
					'v_beratbadan' => !empty($rowData[0][183]) ? $rowData[0][183] : null,
					'v_tinggibadan' => !empty($rowData[0][184]) ? $rowData[0][184] : null,
					'v_goldarah' => !empty($rowData[0][185]) ? $rowData[0][185] : null,

					'v_rhesus' => !empty($rowData[0][186]) ? $rowData[0][186] : null,
					'v_alergi' => !empty($rowData[0][187]) ? $rowData[0][187] : null,
					'v_penyakit' => !empty($rowData[0][188]) ? $rowData[0][188] : null,
					'v_penyakitkel' => !empty($rowData[0][189]) ? $rowData[0][189] : null,

					'v_sizebaju' => !empty($rowData[0][190]) ? $rowData[0][190] : null,
					'v_sizecelana' => !empty($rowData[0][191]) ? $rowData[0][191] : null,
					'v_sizesepatu' => !empty($rowData[0][192]) ? $rowData[0][192] : null,
					'v_sizerompi' => !empty($rowData[0][193]) ? $rowData[0][193] : null,
					'v_novoip' => !empty($rowData[0][194]) ? $rowData[0][194] : null,
					'v_noext' => !empty($rowData[0][195]) ? $rowData[0][195] : null,
				);

				// die(var_dump($params));

				// Start Cek Validasi Data Excel
				$cekdata = null;
				if (gettype($params['v_nik']) == 'float') {
					$cekdata = 'Cek Format NIK ! NIK Tidak Boleh Number';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'B','" . $cekdata . "');");
				}
				if (substr_count($params['v_fullnama'], "'") > 0) {
					$cekdata = 'Cek Format Nama Pegawai ! Full Name Tidak Boleh Menggunakan Kutip Satu';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'C','" . $cekdata . "');");
				}
				if (substr_count($params['v_namadepan'], "'") > 0) {
					$cekdata = 'Cek Format Nama Pegawai ! Nama Depan Tidak Boleh Menggunakan Kutip Satu';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'D','" . $cekdata . "');");
				}
				if (substr_count($params['v_namatengah'], "'") > 0) {
					$cekdata = 'Cek Format Nama Pegawai ! Nama Tengah Tidak Boleh Menggunakan Kutip Satu';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'E','" . $cekdata . "');");
				}
				if (substr_count($params['v_namabelakang'], "'") > 0) {
					$cekdata = 'Cek Format Nama Pegawai ! Nama Akhir Tidak Boleh Menggunakan Kutip Satu';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'F','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.vw_satkertree_hr", array("direktorat" => $params['v_direktorat']))->result() == null) {
					$cekdata = 'Cek Struktur Pegawai! Direktorat ' . $params['v_direktorat'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'G','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.vw_satkertree_hr", array("divisi" => $params['v_divisi']))->result() == null) {
					$cekdata = 'Cek Struktur Pegawai! Divisi ' . $params['v_divisi'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'H','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.vw_satkertree_hr", array("departemen" => $params['v_departement']))->result() == null) {
					$cekdata = 'Cek Struktur Pegawai! Departement ' . $params['v_departement'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'I','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.vw_satkertree_hr", array("seksi" => $params['v_seksi']))->result() == null) {
					$cekdata = 'Cek Struktur Pegawai! Seksi ' . $params['v_seksi'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'J','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.vw_satkertree_hr", array("subseksi" => $params['v_subseksi']))->result() == null) {
					$cekdata = 'Cek Struktur Pegawai! Sub Seksi ' . $params['v_subseksi'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'K','" . $cekdata . "');");
				}
				if ($this->db->get_where("lokasi", array("code" => $params['v_kodelokasi']))->result() == null) {
					$cekdata = 'Cek Kode Store ! Kode Store ' . $params['v_kodelokasi'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'L','" . $cekdata . "');");
				}
				if ($this->db->get_where("lokasi", array("name" => $params['v_lokasikerja']))->result() == null) {
					$cekdata = 'Cek Nama Store ! Nama Store ' . $params['v_lokasikerja'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'M','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.levelgrade", array("gol" => $params['v_levelid']))->result() == null) {
					$cekdata = 'Cek Golongan ! Golongan ' . $params['v_levelid'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'N','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.levelgrade", array("name" => $params['v_levelname']))->result() == null) {
					$cekdata = 'Cek Level Pegawai ! Level ' . $params['v_levelname'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'O','" . $cekdata . "');");
				}
				if ($this->db->get_where("struktur.jabatan", array("name" => $params['v_jabatanid']))->result() == null) {
					$cekdata = 'Cek Jabatan Pegawai ! Jabatan ' . $params['v_jabatanid'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'P','" . $cekdata . "');");
				}
				if ($this->db->get_where("statuspegawai", array("name" => $params['v_statuspegawaiid']))->result() == null) {
					$cekdata = 'Cek Status Pegawai ! Status ' . $params['v_statuspegawaiid'] . ' Tidak Ada';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'R','" . $cekdata . "');");
				}
				if (substr($params['v_tglpermanent'], 2, 1) != '/' && $rowData[0][18] != NULL) {
					$cekdata = 'Cek Format Tanggal Permanent! Format Tanggal Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'S','" . $cekdata . "');");
				}
				if (substr($params['v_tglmulai'], 2, 1) != '/' && $rowData[0][19] != NULL) {
					$cekdata = 'Cek Format Tanggal Mulai Bekerja ! Format Tanggal Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'T','" . $cekdata . "');");
				}
				if (substr($params['v_tglakhirkontrak'], 2, 1) != '/' && $rowData[0][20] != NULL) {
					$cekdata = 'Cek Format Tanggal End Of Contract ! Format Tanggal Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'V','" . $cekdata . "');");
				}
				if (substr($params['v_tglselesai'], 2, 1) != '/' && $rowData[0][21] != 'Cancel Join' && $rowData[0][21] != null) {
					$cekdata = 'Cek Format Tanggal Berhenti ! Format Tanggal Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'U','" . $cekdata . "');");
				}
				if (!in_array($params['v_jeniskelamin'], ['L', 'P'])) {
					$cekdata = 'Cek Jenis Kelamin ! Jenis Kelamin ' . $params['v_jeniskelamin'] . ' Tidak Ada';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AF','" . $cekdata . "');");
				}
				if ($this->db->get_where("agama", array("name" => $params['v_agamaid']))->result() == null && $params['v_agamaid'] != NULL) {
					$cekdata = 'Cek Nama Agama ! Agama ' . $params['v_agamaid'] . ' Belum Terdaftar';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AG','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahir'], 2, 1) != '/' && $rowData[0][34] != null) {
					$cekdata = 'Cek Format Tanggal Lahir Pegawai ! Format Tanggal Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AI','" . $cekdata . "');");
				}
				if ($this->db->get_where("shio", array("namashio" => $params['v_shio']))->result() == null && $params['v_shio'] != NULL) {
					$cekdata = 'Cek Nama Shio ! Shio ' . $params['v_shio'] . ' Tidak Ada';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AJ','" . $cekdata . "');");
				}
				if ($this->db->get_where("shio", array("unsurshio" => $params['v_unsur']))->result() == null && $params['v_unsur'] != NULL) {
					$cekdata = 'Cek Nama Unsur Shio ! Unsur ' . $params['v_unsur'] . ' Tidak Ada';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AK','" . $cekdata . "');");
				}
				if ($this->db->get_where("pendidikan", array("code" => $params['v_pendidikan']))->result() == null && $params['v_pendidikan'] != NULL) {
					$cekdata = 'Cek Jenjang Sekolah ! Sesuaikan Dengan Format Sistem !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AQ','" . $cekdata . "');");
				}
				if (substr_count($params['v_ipk'], "'") > 0 || substr_count($params['v_ipk'], ",") > 0) {
					$cekdata = 'Cek IPK ! Untuk Koma Gunakan Titik !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AT','" . $cekdata . "');");
				}
				if (strlen($params['v_tahunkeluar']) > 4) {
					$cekdata = 'Cek Tahun Kelulusan !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AU','" . $cekdata . "');");
				}
				// Error Case Pasangan
				if ($this->db->get_where("statusnikah", array("name" => $params['v_statusnikah']))->result() == null && $params['v_statusnikah'] != NULL) {
					$cekdata = 'Cek Status Kawin ! Sesuaikan dengan Format Sistem !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AV','" . $cekdata . "');");
				}
				if (substr($params['v_tglnikah'], 2, 1) != '/' && $rowData[0][48] != null) {
					$cekdata = 'Cek Data Pasangan ! Format Tanggal Menikah Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AW','" . $cekdata . "');");
				}
				if (substr_count($params['v_pasangan1'], "'") > 0) {
					$cekdata = 'Cek Data Pasangan ! Nama Pasangan Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AX','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirpasangan'], 2, 1) != '/' && $rowData[0][51] != null) {
					$cekdata = 'Cek Data Pasangan ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'AZ','" . $cekdata . "');");
				}
				// Error Case Anak
				if (substr_count($params['v_anak1'], "'") > 0) {
					$cekdata = 'Cek Data Anak Pertama ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BD','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahiranak1'], 2, 1) != '/' && $rowData[0][57] != null) {
					$cekdata = 'Cek Data Anak Pertama ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BF','" . $cekdata . "');");
				}
				if (substr_count($params['v_anak2'], "'") > 0) {
					$cekdata = 'Cek Data Anak Kedua ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BI','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahiranak2'], 2, 1) != '/' && $rowData[0][62] != null) {
					$cekdata = 'Cek Data Anak Kedua ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BK','" . $cekdata . "');");
				}
				if (substr_count($params['v_anak3'], "'") > 0) {
					$cekdata = 'Cek Data Anak Ketiga ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BN','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahiranak3'], 2, 1) != '/' && $rowData[0][67] != null) {
					$cekdata = 'Cek Data Anak Ketiga ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BP','" . $cekdata . "');");
				}
				if (substr_count($params['v_anak4'], "'") > 0) {
					$cekdata = 'Cek Data Anak Keempat ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BS','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahiranak4'], 2, 1) != '/' && $rowData[0][72] != null) {
					$cekdata = 'Cek Data Anak Keempat ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BU','" . $cekdata . "');");
				}
				//Keluarga
				if (substr_count($params['v_ibu'], "'") > 0) {
					$cekdata = 'Cek Data Ibu ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BX','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahiribu'], 2, 1) != '/' && $rowData[0][77] != null) {
					$cekdata = 'Cek Data Ibu ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'BZ','" . $cekdata . "');");
				}
				if (substr_count($params['v_ayah'], "'") > 0) {
					$cekdata = 'Cek Data Ayah ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CD','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirayah'], 2, 1) != '/' && $rowData[0][83] != null) {
					$cekdata = 'Cek Data Ayah ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CF','" . $cekdata . "');");
				}
				// Data Saudara
				if (substr_count($params['v_sdr1'], "'") > 0) {
					$cekdata = 'Cek Data Saudara Pertama ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CJ','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirsdr1'], 2, 1) != '/' && $rowData[0][89] != null) {
					$cekdata = 'Cek Data Saudara Pertama ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CL','" . $cekdata . "');");
				}
				if (substr_count($params['v_sdr2'], "'") > 0) {
					$cekdata = 'Cek Data Saudara Kedua ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CP','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirsdr2'], 2, 1) != '/' && $rowData[0][95] != null) {
					$cekdata = 'Cek Data Saudara Kedua ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CR','" . $cekdata . "');");
				}
				if (substr_count($params['v_sdr3'], "'") > 0) {
					$cekdata = 'Cek Data Saudara Ketiga ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CV','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirsdr3'], 2, 1) != '/' && $rowData[0][101] != null) {
					$cekdata = 'Cek Data Saudara Ketiga ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'CX','" . $cekdata . "');");
				}
				if (substr_count($params['v_sdr4'], "'") > 0) {
					$cekdata = 'Cek Data Saudara Keempat ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'DB','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirsdr4'], 2, 1) != '/' && $rowData[0][107] != null) {
					$cekdata = 'Cek Data Saudara Keempat ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'DD','" . $cekdata . "');");
				}
				if (substr_count($params['v_sdr5'], "'") > 0) {
					$cekdata = 'Cek Data Saudara Kelima ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'DH','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirsdr5'], 2, 1) != '/' && $rowData[0][113] != null) {
					$cekdata = 'Cek Data Saudara Kelima ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'DJ','" . $cekdata . "');");
				}
				if (substr_count($params['v_sdr6'], "'") > 0) {
					$cekdata = 'Cek Data Saudara Keenam ! Nama Tidak Boleh Kutip Satu !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'DN','" . $cekdata . "');");
				}
				if (substr($params['v_tgllahirsdr6'], 2, 1) != '/' && $rowData[0][119] != null) {
					$cekdata = 'Cek Data Saudara Keenam ! Format Tanggal Lahir Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'DP','" . $cekdata . "');");
				}
				// Data Personal
				if (substr($params['v_masaberlakuktp'], 2, 1) != '/' && strtoupper(substr($rowData[0][130], 0, 12)) != 'SEUMUR HIDUP' && $rowData[0][130] != null) {
					$cekdata = 'Cek Format Tanggal Masa Berlaku KTP ! Format Tanggal Harus Armenian DD/MM/YYYY';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'EA','" . $cekdata . "');");
				}
				if (substr_count($params['v_namakontakdarurat'], "'") > 0 || substr_count($params['v_namakontakdarurat'], "/") > 1) {
					if (substr_count($params['v_namakontakdarurat'], "'") > 0) {
						$cekdata = 'Cek Data Kontak Darurat ! Nama Tidak Boleh Kutip Satu !';
					} else {
						$cekdata = 'Cek Data Kontak Darurat ! Nama Kontak Darurat Max 2 Orang';
					}
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'EE','" . $cekdata . "');");
				}
				if (substr_count($params['v_relasikontakdarurat'], "/") > 1) {
					$cekdata = 'Cek Data Kontak Darurat ! Relasi Kontak Darurat Max 2 Orang';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'EG','" . $cekdata . "');");
				}
				if (substr_count($params['v_email'], "@") == 0 && $params['v_email'] != null) {
					$cekdata = 'Cek Email Pegawai ! Gunakan Format Email Yang Benar !';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'EH','" . $cekdata . "');");
				}
				if (!in_array($params['v_goldarah'], ['A', 'B', 'AB', 'O']) && $params['v_goldarah'] != null) {
					$cekdata = 'Cek Golongan Darah ! Sesuaikan dengan Format Sistem';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'GD','" . $cekdata . "');");
				}
				if (!in_array(strtoupper($params['v_rhesus']), ['POSITIF', 'NEGATIF', '+', '-']) && $params['v_rhesus'] != null) {
					$cekdata = 'Cek Rhesus ! Positif atau Negatif (Jika Tidak Tahu, Kosongkan Saja)';
					$this->load->database();
					$this->db->query("INSERT INTO report.errmsg VALUES ($row,'GE','" . $cekdata . "');");
				}
				// End Cek Validasi Data Excel
				$result = $this->db->get_where("users", array("nik" => strval($rowData[0][1])))->result();
				if (count($result) == 0 && $rowData[0][21] != 'Cancel Join') {
					$this->M_app->deleteStaging();
					$this->M_app->tambahPegawai($params);
				} else if (count($result) > 0) {
					$this->M_app->resetUpload();
				}
			}
			if ($cekdata != null) {
				redirect('app/errorcase');
			} else {
				$q = $this->insertpegawai();
				if ($q) {
					redirect('app/success');
				} else {
					redirect('app/gagal');
				}
			}
		}
	}

	function insertpegawai()
	{
		// Start a transaction to ensure all or nothing
		$this->db->trans_begin();

		try {
			// Grouped uploads for better organization
			$this->M_app->uploadUsers();
			$this->M_app->uploadPegawai();
			$this->M_app->uploadDataPegawai();
			$this->M_app->uploadNamaPegawai();
			$this->M_app->uploadRiwayatJabatan();
			$this->M_app->uploadSatkerPegawai();
			$this->M_app->uploadRiwayatRekening();
			$this->M_app->uploadRiwayatPendidikan();
			$this->M_app->uploadRiwayatKeluarga();
			$this->M_app->uploadRiwayatExp();
			$this->M_app->uploadRiwayatBahasa();
			$this->M_app->uploadRiwayatSkill();
			$this->M_app->uploadRiwayatKursus();
			$this->M_app->uploadRiwayatPenyakit();

			// If all operations are successful, commit the transaction
			if ($this->db->trans_status() === FALSE) {
				// If there was any error, rollback the entire transaction
				$this->db->trans_rollback();
				log_message('error', 'Failed to insertpegawai - transaction rolled back.');
				return false;
			} else {
				// Commit the transaction if successful
				$this->db->trans_commit();
				return true;
			}
		} catch (Exception $e) {
			// Catch any exceptions and rollback the transaction
			$this->db->trans_rollback();
			log_message('error', 'Exception in insertpegawai: ' . $e->getMessage());
			return false;
		}
	}

	function success()
	{
		//load berhasil	
		$config = array();
		$config["base_url"] = SITE_URL() . "/app/uploadDataPegawai/"; //base url
		$config["total_rows"] = $this->M_app->record_count(); //table
		$config["per_page"] = 25; // postingan perhalaman
		$config["uri_segment"] = 3; //au
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data["show"] = $this->M_app->getData($config["per_page"], $page); //posting blog 
		$data["links"] = $this->pagination->create_links();
		$data['status'] = 'Berhasil di upload';
		$this->load->view('portal/v_csv', $data);
	}

	function gagal()
	{
		//File Max
		$config = array();
		$config["base_url"] = SITE_URL() . "/app/uploadDataPegawai/"; //base url
		$config["total_rows"] = $this->M_app->record_count(); //table
		$config["per_page"] = 25; // postingan perhalaman
		$config["uri_segment"] = 3; //au
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data["show"] = $this->M_app->getData($config["per_page"], $page); //posting blog
		$data["links"] = $this->pagination->create_links();
		$data['status'] = 'File Terlalu Besar, Max 1.3MB';
		$this->load->view('portal/v_csv', $data);
	}

	function errorcase()
	{
		//Error Case
		$config = array();
		$config["base_url"] = SITE_URL() . "/app/errorcase/"; //base url
		$config["total_rows"] = $this->M_app->errcount(); //table
		$config["per_page"] = 50; // postingan perhalaman
		$config["uri_segment"] = 3; //au
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data["show"] = $this->M_app->errdata($config["per_page"], $page); //posting blog
		$data["links"] = $this->pagination->create_links();
		$data['status'] = 'Silahkan Cek Error Dibawah...';
		$this->load->view('portal/v_errorcase', $data);
	}

	public function changePassword()
	{
		// Handle changing user password
		$pegawaiid = $this->session->userdata('userid');
		$this->form_validation->set_rules('oldpassword', 'Old password', 'trim|callback_isOldPassword');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('passconf', 'Password Confirm', 'trim|required|matches[password]');

		$this->form_validation->set_message('matches', 'Konfirmasi password tidak sesuai dengan password baru.');
		if ($this->form_validation->run() == FALSE) {
			$data['status'] = "";
			$this->load->view('portal/v_changepassword', $data);
		} else {
			$this->M_app->update($pegawaiid);
			redirect('app/berhasil');
		}
	}

	function berhasil()
	{ //berhasil change password
		$data['status'] = "berhasil";
		$this->load->view('portal/v_changepassword', $data);
	}

	public function download()
	{
		// Handle file download
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('siap_tpl_path');

		if ($filename == '') {
			echo '<h2>Belum upload file</h2>';
		} elseif (file_exists($path . $filename)) {
			force_download($filename, file_get_contents($path . $filename));
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}

	public function liatprofil()
	{
		// Download file for executive profiles
		$this->load->helper('download');

		$filename = $this->input->get('filename');
		$path = config_item('siap_tpl_path');
		$fullpath = $path . $filename;

		if ($filename == '') {
			echo '<h2>Belum upload file</h2>';
		} elseif (file_exists($fullpath)) {
			force_download($filename, file_get_contents($fullpath));
		} else {
			echo '<h2>Maaf, File hilang</h2>';
		}
	}

	public function isOldPassword($oldpassword)
	{
		// Check old password
		if ($this->M_app->isOldPassword($oldpassword)) {
			return true;
		} else {
			$this->form_validation->set_message('isOldPassword', 'Password lama tidak sesuai.');
			return false;
		}
	}
}
