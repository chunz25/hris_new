<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class crotber extends EServices_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_pengajuan');
	}

	public function index()
	{
		$this->setLooping();
	}

	function setLooping()
	{
		$this->load->database();

		$cutber = $this->db->query("
			SELECT * FROM eservices.vwcuti WHERE tahun = DATE_PART('year', NOW()) AND jeniscutiid = '6';
				");
		$cuti = $cutber->result_array();

		if ($cuti == NULL) {
			$query = $this->db->query("
			SELECT * FROM eservices.vwcutber WHERE tahun = DATE_PART('year', NOW());
				");

			$pegawai = $query->result_array();
			foreach ($pegawai as $p) {

				$pegawaiid = $p['pegawaiid'];
				$atasan1 = $p['verifikatorid'];
				$atasan2 = $p['atasanid'];
				$pelimpahan = $p['pelimpahan'];
				$hp = $p['hp'];
				$tglcutber = $p['tgl'];
				$tahun = $p['tahun'];
				$alasan = $p['keterangan'];
				$params = array(
					'v_pegawaiid' => $pegawaiid,
					'v_periode' => $tahun,
					'v_tglpermohonan' => $tglcutber,
					'v_atasan1' => $atasan1,
					'v_atasan2' => $atasan2,
					'v_pelimpahan' => $pelimpahan,
					'v_status' => '7',
					'v_verifikasinotes' => null,
					'v_files' => null,
					'v_filestype' => null,
					'v_hp' => $hp,
				);

				$mpengajuan = $this->m_pengajuan->addPengajuanCuti($params);

				$sendParams = array(
					'v_pengajuanid' => $mpengajuan['pengajuanid'],
					'v_jeniscutiid' => '6',
					'v_detailjeniscutiid' => '18',
					'v_tglmulai' => $tglcutber,
					'v_tglselesai' => $tglcutber,
					'v_lama' => '1',
					'v_satuan' => 'HARI KERJA',
					'v_sisacuti' => '0',
					'v_alasan' => $alasan,
				);

				$this->m_pengajuan->addDetailPengajuanCuti($sendParams);
			}

			echo 'Generate Cuti Bersama Berhasil !!!';
		} else {
			echo 'Sudah ada Cuti Bersama Tahun ini.';
		}
	}
}
