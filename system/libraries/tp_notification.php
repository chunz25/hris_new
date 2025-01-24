<?php
class tp_notification
{
	var $CI;
	function __construct()
	{
		$this->CI = &get_instance();
	}
	function addNotif($params)
	{
		$this->CI->load->database();
		$this->CI->db->trans_start();
		$this->CI->db->query("
			INSERT INTO kehadiran.cuti_notifikasi(tglnotif,jenisnotif,description,penerima,useridfrom,usergroupidfrom,pengirim,isshow,modulid,modul,isread)
			VALUES(NOW(),?,?,?,CAST(? AS INT),CAST(? AS INT),?,'1',CAST(? AS INT),?,0);
		", $params);
		$this->CI->db->trans_complete();
		$this->CI->db->close();
		return $this->CI->db->trans_status();
	}
	function getCountNotif($penerimaid)
	{
		$this->CI->db->where('penerima', $penerimaid);
		return $this->CI->db->count_all_results("notifikasi");
	}
	// Get all notifikasi
	function getAllNotification($penerimaid, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, np.fullname as nama, u.nik
			FROM kehadiran.cuti_notifikasi n
			LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN namapegawai np on np.pegawaiid = p.id
			LEFT JOIN users u on u.id = p.userid
			WHERE n.penerima = ?
			ORDER BY n.tglnotif DESC
			OFFSET " . $row . " ROWS
			FETCH NEXT 25 ROWS ONLY;
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}

	// Editing by Tama
	function getAllNotificationCuti($penerimaid, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
				SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
					n.jenisnotif, np.fullname as nama, u.nik
				FROM kehadiran.cuti_notifikasi n
				LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
				LEFT JOIN namapegawai np on np.pegawaiid = p.id
				LEFT JOIN users u on u.id = p.userid
				WHERE n.penerima = ?
				and n.modulid='2'
				ORDER BY n.tglnotif DESC
				OFFSET " . $row . " ROWS
				FETCH NEXT 25 ROWS ONLY;
			", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}

	function getAllNotificationDinas($penerimaid, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
				SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
					n.jenisnotif, np.fullname as nama, u.nik
				FROM kehadiran.cuti_notifikasi n
				LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
				LEFT JOIN namapegawai np on np.pegawaiid = p.id
				LEFT JOIN users u on u.id = p.userid
				WHERE n.penerima = ?
				and n.modulid='3'
				ORDER BY n.tglnotif DESC
				OFFSET " . $row . " ROWS
				FETCH NEXT 25 ROWS ONLY;
			", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}
	// End Editing Tama

	function getAllNotificationHR($penerimaid, $row)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, np.fullname as nama, u.nik
			FROM kehadiran.cuti_notifikasi n
			LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN namapegawai np on np.pegawaiid = p.id
			LEFT JOIN users u on u.id = p.userid
			WHERE n.penerima = ?
			And n.jenisnotif NOT LIKE 'Pengajuan%'
			ORDER BY n.tglnotif DESC
			OFFSET ? ROWS
			FETCH NEXT 25 ROWS ONLY
		", array($penerimaid, $row));
		$this->CI->db->close();
		return $q->result_array();
	}

	function getShortNotif($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, np.fullname as nama, u.nik
			FROM kehadiran.cuti_notifikasi n
			LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN namapegawai np on np.pegawaiid = p.id
			LEFT JOIN users u on u.id = p.userid
			WHERE n.penerima = ?
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}

	// Editing by Tama
	function getShortNotifCuti($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, np.fullname as nama, u.nik
			FROM kehadiran.cuti_notifikasi n
			LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN namapegawai np on np.pegawaiid = p.id
			LEFT JOIN users u on u.id = p.userid
			WHERE n.penerima = ?
			and n.modulid='2'
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}
	// End Editing by Tama

	// Editing by Tama
	function getShortNotifDinas($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, np.fullname as nama, u.nik
			FROM kehadiran.cuti_notifikasi n
			LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN namapegawai np on np.pegawaiid = p.id
			LEFT JOIN users u on u.id = p.userid
			WHERE n.penerima = ?
			and n.modulid='3'
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid));
		$this->CI->db->close();
		return $q->result_array();
	}
	// End Editing by Tama


	function getShortNotifHR($penerimaid, $nik)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, np.fullname as nama, u.nik
			FROM kehadiran.cuti_notifikasi n
			LEFT JOIN pegawai p ON CAST(n.pengirim AS INT) = p.id
			LEFT JOIN namapegawai np on np.pegawaiid = p.id
			LEFT JOIN users u on u.id = p.userid
			WHERE n.penerima = ? And p.nik = ?
			And n.jenisnotif NOT LIKE 'Pengajuan%'
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid, $nik));
		$this->CI->db->close();
		return $q->result_array();
	}

	function getCountNotifUnread($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT COUNT(*) jml FROM kehadiran.cuti_notifikasi WHERE penerima = ? AND (isread IS NULL OR isread = 0)
		", array($penerimaid));
		$this->CI->db->close();
		return $q->first_row()->jml;
	}
	function updateNotifRead($penerimaid)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("UPDATE kehadiran.cuti_notifikasi SET isread = 1 WHERE penerima = ?", array($penerimaid));
		$this->CI->db->close();
		return $q;
	}
}
