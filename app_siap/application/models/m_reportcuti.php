<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_reportcuti extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function reportListStatusPegawai($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid'])) {
			if (!empty($params['v_nama'])) {
				$cond_where = " AND UPPER(np.fullname) LIKE '%' || UPPER('" . $params['v_nama'] . "') || '%' AND vs.code LIKE '" . $params['v_satkerid'] . "' || '%'";
			} else {
				$cond_where = " AND vs.code LIKE '" . $params['v_satkerid'] . "' || '%'";
			}
		} else {
			if (!empty($params['v_nama'])) {
				$cond_where = " AND UPPER(np.fullname) LIKE '%' || UPPER('" . $params['v_nama'] . "') || '%'";
			}
		}

		$query = "
		SELECT
			p.id as pegawaiid ,
			u.nik ,
			np.fullname as nama ,
			j.name as jabatan ,
			vs.id as idsatker ,
			vs.code as satkerid ,
			vs.unitkerja ,
			vs.direktorat ,
			vs.divisi ,
			vs.departemen ,
			vs.seksi ,
			vs.subseksi ,
			cs.saldo as jatahcuti,
			lg.name as level ,
			lg.susunan as idnew ,
			l.name as lokasi ,
			cs.saldocy as sisacutithnini
		FROM 
			pegawai p
			LEFT JOIN users u ON u.id = p.userid
			LEFT JOIN namapegawai np ON np.pegawaiid = p.id
			LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
			LEFT JOIN struktur.jabatan j ON j.id = sp.jabatanid
			LEFT JOIN struktur.levelgrade lg ON lg.id = sp.levelid
			LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
			LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = p.id
			LEFT JOIN lokasi l ON l.id = rj.lokasiid
			LEFT JOIN kehadiran.cuti_sisa cs ON cs.pegawaiid = p.id AND cs.tahun = date_part('year', now())
		WHERE 
			rj.tglselesai is NULL 
			AND rj.lokasiid = 1
		" . $cond_where . " ORDER BY lg.susunan";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getReportListBatalCutiBersama($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_satkerid'])) {
			if (!empty($params['v_date'])) {
				$cond_where = " AND vs.code LIKE '" . $params['v_satkerid'] . "' || '%' AND cd.tglmulai = '" . $params['v_date'] . "' ORDER BY cd.tglmulai ASC";
			} else {
				$cond_where = " AND vs.code LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY cd.tglmulai ASC";
			}
		} else {
			if (!empty($params['v_date'])) {
				$cond_where = " AND cd.tglmulai = '" . $params['v_date'] . "' ORDER BY cd.tglmulai ASC";
			} else {
				$cond_where = " ORDER BY cd.tglmulai ASC";
			}
		}

		$query = "
			SELECT
			p.id as pegawaiid ,
			u.nik ,
			np.fullname as namadepan ,
			ch.pengajuanid ,
			cjd.name as detailjeniscuti ,
			cd.tglmulai ,
			cd.tglselesai ,
			cst.name as status ,
			l.name as lokasi ,
			ch.tglpermohonan ,
			vs.id as idsatker ,
			vs.code as satkerid ,
			vs.unitkerja ,
			vs.direktorat ,
			vs.divisi ,
			vs.departemen ,
			vs.seksi ,
			vs.subseksi ,
			j.name as jabatan ,
			lg.name as level ,
			lg.susunan as idnew
		FROM 
			pegawai p
			LEFT JOIN users u ON u.id = p.userid
			LEFT JOIN namapegawai np ON np.pegawaiid = p.id
			LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
			LEFT JOIN struktur.jabatan j ON j.id = sp.jabatanid
			LEFT JOIN struktur.levelgrade lg ON lg.id = sp.levelid
			LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
			LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = p.id
			LEFT JOIN lokasi l ON l.id = rj.lokasiid
			LEFT JOIN kehadiran.cuti_hdr ch ON ch.pegawaiid = p.id
			LEFT JOIN kehadiran.cuti_dtl cd ON cd.pengajuanid = ch.pengajuanid
			LEFT JOIN kehadiran.cuti_jenis_dtl cjd ON cjd.id = cd.detailjeniscutiid
			LEFT JOIN kehadiran.cuti_status cst ON cst.id = ch.status
		WHERE 
			cst.id IN (9,10,12,14)
			AND cd.jeniscutiid = 6
			AND rj.tglselesai is NULL 
			AND rj.lokasiid = 1
			AND ch.periode = date_part('year', now())
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getListCutiPegawai($params)
	{
		$this->load->database();

		$cond_where = '';

		if (!empty($params['v_satkerid'])) {
			$cond_where = "AND vs.code LIKE '" . $params['v_satkerid'] . "%' ORDER BY cd.tglmulai DESC";
		} else {
			$cond_where = "ORDER BY cd.tglmulai DESC";
		}

		$query = "
			SELECT
				ch.pengajuanid ,
				u.nik ,
				np.fullname as namadepan ,
				cd.tglmulai ,
				cd.tglselesai ,
				ch.tglpermohonan ,
				cd.lama ,
				cst.name as status ,
				cjh.name as jeniscuti ,
				cd.alasancuti ,
				ch.status as statusid ,
				vs.id as idsatker ,
				vs.code as satkerid ,
				vs.unitkerja ,
				vs.direktorat ,
				vs.divisi ,
				vs.departemen ,
				vs.seksi ,
				vs.subseksi ,
				ch.hrd
			FROM 
				pegawai p
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN namapegawai np ON np.pegawaiid = p.id
				LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
				LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
				LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = p.id
				LEFT JOIN kehadiran.cuti_hdr ch ON ch.pegawaiid = p.id
				LEFT JOIN kehadiran.cuti_dtl cd ON cd.pengajuanid = ch.pengajuanid
				LEFT JOIN kehadiran.cuti_jenis_hdr cjh ON cjh.id = cd.jeniscutiid
				LEFT JOIN kehadiran.cuti_jenis_dtl cjd ON cjd.id = cd.detailjeniscutiid
				LEFT JOIN kehadiran.cuti_status cst ON cst.id = ch.status
			WHERE 
				( 
				  (cd.tglmulai BETWEEN TO_DATE('" . $params['v_mulai'] . "','DD/MM/YYYY') AND TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY')) 
				OR
				  (cd.tglselesai BETWEEN TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY') AND TO_DATE('" . $params['v_selesai'] . "','DD/MM/YYYY'))
				)
		" . $cond_where;

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "
		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}

	function getreporthistorycuti($params)
	{
		$this->load->database();

		$query = "
			SELECT
				ch.pengajuanid ,
				ch.pegawaiid ,
				ch.nourut ,
				u.nik ,	
				np.fullname as nama ,
				j.name as jabatan ,
				l.name as lokasi ,
				vs.id as idsatker ,
				vs.code as satkerid ,
				vs.unitkerja ,
				vs.direktorat ,
				vs.divisi ,
				vs.departemen ,
				vs.seksi ,
				vs.subseksi ,
				ch.periode ,
				TO_CHAR(ch.tglpermohonan,'DD/MM/YYYY') tglpermohonan ,
				ch.status as statusid ,
				cst.name as status ,
				ch.verifikasinotes ,
				ch.atasan1 as atasan1id ,
				np1.fullname as atasan1nama ,
				ch.atasan2 as atasan2id ,
				np2.fullname as atasan2nama ,
				ch.pelimpahan as pelimpahanid ,
				np3.fullname as pelimpahannama ,
				SUM(cd.lama) lama ,
				string_agg(cjh.name, ' | ' ORDER BY (cjh.name)) AS jeniscuti ,
				string_agg(to_char(cd.tglmulai, 'DD/MM/YYYY'), ' | ' ORDER BY cd.tglmulai) AS tglmulai ,
				string_agg(to_char(cd.tglselesai, 'DD/MM/YYYY'), ' | ' ORDER BY cd.tglselesai) AS tglselesai ,
				string_agg(cd.alasancuti, ' | ' ORDER BY cd.alasancuti) AS alasancuti
			FROM 
				kehadiran.cuti_hdr ch
				LEFT JOIN kehadiran.cuti_dtl cd ON cd.pengajuanid = ch.pengajuanid
				LEFT JOIN pegawai p ON p.id = ch.pegawaiid
				LEFT JOIN users u ON u.id = p.userid
				LEFT JOIN namapegawai np ON np.pegawaiid = ch.pegawaiid
				LEFT JOIN namapegawai np1 ON np1.pegawaiid = ch.atasan1
				LEFT JOIN namapegawai np2 ON np2.pegawaiid = ch.atasan2
				LEFT JOIN namapegawai np3 ON np3.pegawaiid = ch.pelimpahan
				LEFT JOIN struktur.satkerpegawai sp ON sp.pegawaiid = ch.pegawaiid
				LEFT JOIN struktur.jabatan j ON j.id = sp.jabatanid
				LEFT JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
				LEFT JOIN riwayatjabatan rj ON rj.pegawaiid = ch.pegawaiid
				LEFT JOIN lokasi l ON l.id = rj.lokasiid
				LEFT JOIN kehadiran.cuti_jenis_hdr cjh ON cjh.id = cd.jeniscutiid
				LEFT JOIN kehadiran.cuti_status cst ON cst.id = ch.status
			WHERE
				rj.tglselesai is NULL 
				AND pegawaiid = '" . $params['v_pegawaiid'] . "'
			GROUP BY
				ch.pengajuanid ,
				ch.pegawaiid ,
				ch.nourut ,
				u.nik ,	
				np.fullname ,
				j.name ,
				l.name ,
				vs.id ,
				vs.code ,
				vs.unitkerja ,
				vs.direktorat ,
				vs.divisi ,
				vs.departemen ,
				vs.seksi ,
				vs.subseksi ,
				ch.periode ,
				TO_CHAR(ch.tglpermohonan,'DD/MM/YYYY') ,
				ch.status ,
				cst.name ,
				ch.verifikasinotes ,
				ch.atasan1 ,
				np1.fullname ,
				ch.atasan2 ,
				np2.fullname ,
				ch.pelimpahan ,
				np3.fullname
			ORDER BY
				ch.pegawaiid
		";

		$q = $this->db->query("
			SELECT a.*
			FROM (" . $query . ") a
			ORDER BY to_date(a.tglmulai, 'dd/mm/yyyy') DESC
			OFFSET " . $params['v_start'] . " LIMIT " . $params['v_limit'] . "

		", $params);

		$q2 = $this->db->query("
			SELECT COUNT(*) as jml
			FROM (" . $query . ") a
		");

		$this->db->close();
		$result = array('success' => true, 'count' => $q2->first_row()->jml, 'data' => $q->result_array());
		return $result;
	}
}
