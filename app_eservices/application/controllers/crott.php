<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class crott extends EServices_Controller
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

		$pegawai = $this->db->query("
					SELECT a.pegawaiid 
					FROM pegawai a
					INNER JOIN riwayatjabatan b on b.pegawaiid = a.pegawaiid
					WHERE b.tglselesai IS NULL
					ORDER BY a.pegawaiid;
					");

		$pegawaiid = $pegawai->result_array();

		foreach ($pegawaiid as $pi) {
			$idpegawai = $pi['pegawaiid'];

			$q = $this->db->query("
				SELECT 	ROW_NUMBER() OVER(PARTITION BY a.pegawaiid ORDER BY a.tglmulai) rn ,
						a.pegawaiid ,
						a.tglmulai , 
						a.tglselesai , 
						a.lama ,
						a.jeniscutiid , 
				b.jatahawal ,
				DATE_PART('MONTH', a.tglmulai) BlnAwal , 
				DATE_PART('MONTH', a.tglselesai) BlnAkhir ,
				b.saldo ,
				b.sisacutithnlalu
				FROM eservices.vwcuti a
				INNER JOIN eservices.historysaldocuti b ON b.pegawaiid = a.pegawaiid AND b.tahun = a.tahun
				WHERE a.status IN('7','9','10','11','12','13','15') 
				AND		a.jeniscutiid IN ('1','6')
				AND 	a.tahun = DATE_PART('YEAR',NOW())
				AND 	a.pegawaiid = ?
				ORDER BY rn;
				", $idpegawai);


			$qu = $this->db->query("
				SELECT 	a.saldo ,
				a.sisacutithnlalu
				FROM eservices.historysaldocuti a
				WHERE	a.tahun = DATE_PART('YEAR',NOW())
				AND 	a.pegawaiid = ?;
				", $idpegawai);
			$this->db->close();


			$params = $q->result_array();
			$cekLY = $qu->result_array();
			$rnlast = !empty(end($params)['rn']) ? end($params)['rn'] : null;
			$saldoCY = null;
			$saldoLY = null;

			if (!empty($params)) {
				$jatahAwal = $params[0]['saldo'];
				$saldoCY = $params[0]['saldo'];
				$saldoLY = $params[0]['sisacutithnlalu'];
			} else {
				$jatahAwal = !empty($cekLY[0]['saldo']) ? $cekLY[0]['saldo'] : 0;
				$saldoCY = !empty($cekLY[0]['saldo']) ? $cekLY[0]['saldo'] : 0;
				$saldoLY = !empty($cekLY[0]['sisacutithnlalu']) ? $cekLY[0]['sisacutithnlalu'] : 0;
			}

			for ($i = 0; $i < $rnlast; $i++) {
				$blnAwal = $params[$i]['blnawal'];
				$jeniscuti = $params[$i]['jeniscutiid'];
				$lamacuti = $params[$i]['lama'];

				if (in_array($blnAwal[0], array('1', '2', '3')) && $saldoLY > 0) {
					if ($jeniscuti == '6') {
						$saldoCY = $saldoCY - $lamacuti;
						$saldoLY = $saldoLY;
					} else if ($saldoLY - $lamacuti < 0) {
						$saldoCY = ($saldoCY + $saldoLY) - $lamacuti;
						$saldoLY = 0;
					} else {
						$saldoCY = $saldoCY;
						$saldoLY = $saldoLY - $lamacuti;
					}
				} else if ($jeniscuti == '6') {
					$saldoCY = $saldoCY - $lamacuti;
					$saldoLY = $saldoLY;
				} else {
					$saldoCY = $saldoCY - $lamacuti;
					$saldoLY = 0;
				}
			}

			$arr = array(
				'pegawaiid' => $idpegawai,
				'tahun' => date("Y"),
				'jatahAwal' => $jatahAwal,
				'saldoCY' => $saldoCY,
				'saldoLY' => $saldoLY,
			);

			$this->db->query("
			DELETE FROM eservices.stgsisacuti
			WHERE pegawaiid = '" . $arr['pegawaiid'] . "' 
			 AND tahun  = " . $arr['tahun'] . " ;

			INSERT INTO eservices.stgsisacuti
			SELECT  '" . $arr['pegawaiid'] . "' ,
			" . $arr['tahun'] . " ,
			" . $arr['jatahAwal'] . " ,
			" . $arr['saldoCY'] . " ,
			" . $arr['saldoLY'] . " ;
			");
		}

		echo 'Crott Berhasil !!!';
	}

	function genHistorysaldocuti()
	{
		$this->m_pengajuan->getHistorysaldocuti;
	}

	function getSisaCuti()
	{
		$this->m_pengajuan->getInfoSisaCuti;
	}
}
