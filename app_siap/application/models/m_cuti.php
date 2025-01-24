<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_cuti extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function getListCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpCount('kehadiran.sp_getlistcutipeg', $params, false);
		return $mresult;
	}

	function getListCetakCuti($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				a.pengajuanid,
				u1.nik,
				np1.fullname as namadepan,
				a.tglpermohonan,
				b.tglmulai,
				b.tglselesai,
				b.lama,
				e.name as status,
				d1.name as jeniscuti,
				CASE WHEN d1.id = 3 THEN d.name 
					ELSE b.alasancuti 
				END AS alasancuti, 
				np2.fullname as hrd
			FROM 
				kehadiran.cuti_hdr a
				LEFT JOIN kehadiran.cuti_dtl b ON a.pengajuanid = b.pengajuanid
				LEFT JOIN kehadiran.cuti_jenis_dtl d ON b.detailjeniscutiid = d.id
				LEFT JOIN kehadiran.cuti_jenis_hdr d1 ON b.jeniscutiid = d1.id
				LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = a.pegawaiid
				LEFT JOIN struktur.vw_satkertree vs ON vs.id = sp.satkerid
				LEFT JOIN kehadiran.cuti_status e ON a.status = e.id
				LEFT JOIN public.pegawai f ON a.pegawaiid = f.id
				LEFT JOIN public.users u1 ON u1.id = f.userid
				LEFT JOIN public.namapegawai np1 ON np1.pegawaiid = f.id
				LEFT JOIN public.pegawai g ON a.hrd = g.id
				LEFT JOIN public.namapegawai np2 ON np2.pegawaiid = g.id
			WHERE 
				b.tglmulai BETWEEN TO_DATE('" . $params['v_mulai'] . "','DD/MM/YYYY') AND TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY')
				AND vs.code LIKE '" . $params['v_satkerid'] . "' || '%' 
				AND e.name LIKE '" . $params['v_nstatus'] . "' || '%'
			ORDER BY a.nourut
		", array($params));
		$this->db->close();
		$mresult = array('success' => true, 'data' => $q->result_array());
		return $mresult;
	}

	function getCutiById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getcutibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getdetailpengajuancuti', array($pengajuanid));
		return $mresult['data'];
	}

	function getDetailPengajuanCutiHidden($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getdetailpengajuancutiHidden', array($pengajuanid));
		return $mresult['data'];
	}

	function updStatusCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$this->db->query("
			SELECT kehadiran.sp_updstatuscuti(?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function setLooping($param)
	{
		$this->load->database();

		$pegawai = $this->db->query("
					SELECT 
						a.id as pegawaiid 	
					FROM 
						pegawai a
						INNER JOIN riwayatjabatan b on b.pegawaiid = a.id
					WHERE b.tglselesai IS NULL
					AND a.id = $param
					ORDER BY a.id;
					");

		$pegawaiid = $pegawai->result_array();

		foreach ($pegawaiid as $pi) {
			$idpegawai = $pi['pegawaiid'];

			$q = $this->db->query("
				SELECT 	
					ROW_NUMBER() OVER(PARTITION BY a.pegawaiid ORDER BY b.tglmulai) rn ,
					a.pegawaiid ,
					b.tglmulai , 
					b.tglselesai , 
					b.lama ,
					b.jeniscutiid , 
					d.saldo as jatahawal ,
					DATE_PART('MONTH', b.tglmulai) BlnAwal , 
					DATE_PART('MONTH', b.tglselesai) BlnAkhir ,
					d.saldo ,
					d1.saldocy as sisacutithnlalu
				FROM 
					kehadiran.cuti_hdr a
					LEFT JOIN kehadiran.cuti_dtl b ON b.pengajuanid = a.pengajuanid
					LEFT JOIN kehadiran.cuti_status c ON c.id = a.status
					INNER JOIN kehadiran.cuti_sisa d ON d.pegawaiid = a.pegawaiid AND d.tahun = a.periode
					INNER JOIN kehadiran.cuti_sisa d1 ON d1.pegawaiid = a.pegawaiid AND d1.tahun = a.periode - 1
				WHERE 
					a.status IN('7','9','10','11','12','13','15') 
					AND b.jeniscutiid IN ('1','6')
					AND a.periode = DATE_PART('YEAR',NOW())
					AND a.pegawaiid = ?
				ORDER BY rn;
				", $idpegawai);


			$qu = $this->db->query("
				SELECT 	
					a.saldo ,
					b.saldocy as sisacutithnlalu
				FROM 
					kehadiran.cuti_sisa a
					INNER JOIN kehadiran.cuti_sisa b ON b.pegawaiid = a.pegawaiid AND b.tahun = a.tahun -1
				WHERE	
					a.tahun = DATE_PART('YEAR',NOW())
					AND a.pegawaiid = ?;
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
			DELETE FROM kehadiran.cuti_sisa
			WHERE pegawaiid = '" . $arr['pegawaiid'] . "' 
			 AND tahun  = " . $arr['tahun'] . " ;

			INSERT INTO kehadiran.cuti_sisa (pegawaiid, tahun, saldo, saldocy, saldoly)
			VALUES (" . $arr['pegawaiid'] . ", " . $arr['tahun'] . ", " . $arr['jatahAwal'] . ", " . $arr['saldoCY'] . ", " . $arr['saldoLY'] . ");");
		}
		return $this->db->trans_status();
	}

	function getHariLibur()
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl FROM harilibur WHERE tgl >= TO_DATE('01/01/2018','DD/MM/YYYY') ORDER BY tgl ASC		
		");
		$this->db->close();

		return $q->result_array();
	}

	function updStatusCutiKosong($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			UPDATE kehadiran.cuti_hdr SET status = '" . $params['v_status'] . "' WHERE pengajuanid = '" . $params['v_pengajuanid'] . "'
		", array($params));
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
