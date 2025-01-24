<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class m_crott extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function setLooping2($params)
	{
		$pegawaiid = $params['pegawaiid'];
		$tgl = $params['tgl'];
		$this->load->database();

		$q = $this->db->query("
				SELECT 	ROW_NUMBER() OVER(PARTITION BY a.pegawaiid ORDER BY a.tglmulai) rn ,
				a.pegawaiid ,
				a.tglmulai , 
				a.tglselesai , 
				a.lama , 
				b.jatahawal ,
				DATE_PART('MONTH', a.tglmulai) BlnAwal , 
				DATE_PART('MONTH', a.tglselesai) BlnAkhir ,
				b.saldo ,
				b.sisacutithnlalu
				FROM eservices.vwcuti a
				INNER JOIN eservices.historysaldocuti b ON b.pegawaiid = a.pegawaiid AND b.tahun = a.tahun
				WHERE a.status IN('7','9','10','11','12','13','15') 
				AND		a.jeniscutiid IN ('1','6') 
				AND 	DATE_PART('YEAR', a.tglmulai) = '" . intval(date("Y", strtotime($tgl))) . "'
				AND 	a.pegawaiid = ?
				ORDER BY rn;
				LIMIT 5
				", $pegawaiid);

		$qu = $this->db->query("
				SELECT 	a.saldo ,
				a.sisacutithnlalu
				FROM eservices.historysaldocuti a
				WHERE	a.tahun = DATE_PART('YEAR',NOW())
				AND 	a.pegawaiid = ?;
				", $pegawaiid);
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
			$lamacuti = $params[$i]['lama'];

			if (in_array($blnAwal[0], array('1', '2', '3')) && $saldoLY > 0) {
				if ($saldoLY - $lamacuti < 0) {
					$saldoCY = ($saldoCY + $saldoLY) - $lamacuti;
					$saldoLY = 0;
				} else {
					$saldoCY = $saldoCY;
					$saldoLY = $saldoLY - $lamacuti;
				}
			} else {
				$saldoCY = $saldoCY - $lamacuti;
				$saldoLY = 0;
			}

			$arr = array(
				'pegawaiid' => $pegawaiid,
				'tahun' => intval(date("Y", strtotime($tgl))),
				'jatahAwal' => $jatahAwal,
				'saldoCY' => $saldoCY,
				'saldoLY' => $saldoLY,
				'tgl' => $tgl,
			);
		}

		// var_dump($arr);
		// die;


		$cek = $this->db->query("
		SELECT * 
		FROM eservices.stgsisacuti
		WHERE pegawaiid = ? AND tahun = DATE_PART('YEAR',NOW())
		", $pegawaiid);

		$this->db->query("
		INSERT INTO eservices.stgsisacuti
		SELECT  '" . $arr['pegawaiid'] . "' ,
		" . $arr['tahun'] . " ,
		" . $arr['jatahAwal'] . " ,
		" . $arr['saldoCY'] . " ,
		" . $arr['saldoLY'] . " 
		");

		return $arr;
		// var_dump($tgl);
		// var_dump(intval(date("Y", strtotime($tgl))));
	}
}
