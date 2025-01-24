<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_kehadiran extends CI_Model
{
	var $CI;
	function __construct()
	{
		parent::__construct();
		$this->CI = &get_instance();
	}

	function addNotif($params)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();
		$q = $this->CI->db->query("
			INSERT INTO kehadiran.absensi_notifikasi(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
			VALUES(NOW(),?,?,?,CAST(? AS INT),CAST(? AS INT),?,'1',CAST(? AS INT),?,0);
		", $params);
		$this->CI->db->trans_complete();
		$this->CI->db->close();
		return $this->CI->db->trans_status();
	}

	function getShortNotif($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, np.fullname as nama, u.nik
			FROM kehadiran.absensi_notifikasi n
			LEFT JOIN pegawai p ON n.pengirim = p.id
			LEFT JOIN namapegawai np ON np.pegawaiid = p.id
			LEFT JOIN users u ON u.id = p.userid
			WHERE CAST(n.penerima AS INT) = ?
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}

	function getCountNotifUnread($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT COUNT(*) jml FROM kehadiran.absensi_notifikasi WHERE penerima = ? AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}

	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE kehadiran.absensi_notifikasi SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}

	function get_satker($nik)
	{
		$fingerid = '';
		$this->db = $this->load->database('hrd', TRUE);
		$q = $this->db->query("SELECT [USERID] FROM [FINGERPRINT].[dbo].[USERINFO] WHERE [BADGENUMBER] = ?", array($nik));

		if ($q->num_rows() > 0) {
			$fingerid = $q->first_row()->USERID;
		}

		return $fingerid;
	}

	function getData($params)
	{
		$this->load->database();

		$cond_where = '';
		if (!empty($params['v_statusid']) || !empty($params['v_satkerid'])) {
			if (!empty($params['v_statusid'])) {
				$cond_where = " where a.status = '" . $params['v_statusid'] . "' and vs.code LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY a.waktu DESC";
			} else {
				$cond_where = " where vs.code LIKE '" . $params['v_satkerid'] . "' || '%' ORDER BY a.waktu DESC";
			}
		} else {
			$cond_where = " ORDER BY CASE WHEN a.status = '2' THEN '1' ELSE '0' END DESC, a.waktu DESC";
		}

		$query = "
				SELECT 
					a.pengajuanid ,
					a.pegawaiid ,
					a.nourut ,
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
					to_char(a.jamupd, 'DD/MM/YYYY') AS tglpermohonan ,
					to_char(a.waktu, 'DD/MM/YYYY') AS tglmulai ,
					a.jam ,
					a.keterangan ,
					aj.name as jenis ,
					a.status as statusid ,
					ast.name as status ,
					a.atasanid ,
					np1.fullname as atasannama
				FROM 
					kehadiran.absensi a
					INNER JOIN pegawai p ON p.id = a.pegawaiid
					INNER JOIN users u ON u.id = p.userid
					INNER JOIN namapegawai np ON np.pegawaiid = p.id
					INNER JOIN struktur.satkerpegawai sp ON sp.pegawaiid = p.id
					INNER JOIN struktur.vw_satkertree_hr vs ON vs.id = sp.satkerid
					INNER JOIN struktur.jabatan j ON j.id = sp.jabatanid
					INNER JOIN riwayatjabatan rj ON rj.pegawaiid = p.id
					INNER JOIN lokasi l ON l.id = rj.lokasiid
					INNER JOIN kehadiran.absensi_jenis aj ON aj.id = a.jenisid
					INNER JOIN kehadiran.absensi_status ast ON ast.id = a.status
					INNER JOIN namapegawai np1 ON np1.pegawaiid = a.atasanid
				WHERE p.isaktif = 1
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

	function getKehadiranById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getabsensibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getdetailpengajuanabsensi', array($pengajuanid));
		return $mresult['data'];
	}

	function prosesImportData($params)
	{
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set'))
			date_default_timezone_set($timezone);
		$tglpermohonan = date('Y-m-d H:i:s');
		$tglmulai = date('Y/m/d H:i:s', strtotime($params['v_tglmulai']));
		$tglselesai = date('Y/m/d H:i:s', strtotime($params['v_tglselesai']));

		$this->db = $this->load->database('hrd', TRUE);
		$this->db->query("INSERT INTO USER_SPEDAY(USERID,STARTSPECDAY,ENDSPECDAY,DATEID,YUANYING) VALUES (?,?,?,?,?)", array($params['v_nik'], $params['v_tglmulai'], $params['v_tglselesai'], $params['v_jenis'], $params['v_alasan']));
	}
	function updStatusKehadiran($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT kehadiran.sp_updstatusabsensi(?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
	function updStatusExp()
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query(
			"
			/*update kehadiran.absensi a set
			status = '6'
			where status = '1' and
			a.jamupd <= cast(concat(extract(year from CURRENT_DATE),'-',extract(month from CURRENT_DATE)-1,'-','14') as date) and a.jamupd <= cast(concat(extract(year from CURRENT_DATE),'-',extract(month from CURRENT_DATE),'-','14') as date)*/
			
			update kehadiran.absensi a set
			status = '6'
			where status = '1' and
			a.jamupd <= cast(concat(extract(year from CURRENT_DATE)-1,'-','12','-','14') as date) and a.jamupd <= cast(concat(extract(year from CURRENT_DATE),'-',extract(month from CURRENT_DATE),'-','14') as date)
		"
		);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
