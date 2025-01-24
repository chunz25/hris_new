<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class M_pengajuan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	// Add a notification
	function addNotif($params)
	{
		$this->db->trans_start();

		try {
			$this->db->query("
                INSERT INTO kehadiran.absensi_notifikasi(tglnotif, jenisnotif, description, penerima, useridfrom, usergroupidfrom, pengirim, isshow, modulid, modul, isread)
                VALUES(NOW(), ?, ?, ?, CAST(? AS INT), CAST(? AS INT), ?, '1', CAST(? AS INT), ?, 0);
            ", $params);

			$this->db->trans_complete();
			return $this->db->trans_status();
		} catch (Exception $e) {
			log_message('error', 'Error in addNotif: ' . $e->getMessage());
			return false;
		}
	}

	// Get short notifications for a recipient
	function getShortNotif($penerimaid)
	{
		try {
			$query = $this->db->query("
                SELECT 
					n.notifid , 
					TO_CHAR(n.tglnotif, 'DD-MM-YYYY') AS tglnotif ,
                	n.jenisnotif , 
					np.fullname AS nama , 
					u.nik
                FROM kehadiran.absensi_notifikasi n
					LEFT JOIN pegawai p ON n.pengirim = p.id
					LEFT JOIN users u ON u.id = p.userid
					LEFT JOIN namapegawai np ON np.pegawaiid = p.id
                WHERE n.penerima = ?
                ORDER BY n.tglnotif DESC
                FETCH FIRST 5 ROWS ONLY
            ", array($penerimaid));

			return $query->result_array();
		} catch (Exception $e) {
			log_message('error', 'Error in getShortNotif: ' . $e->getMessage());
			return [];
		}
	}

	// Get count of unread notifications
	function getCountNotifUnread($penerimaid)
	{
		try {
			$query = $this->db->query("
                SELECT COUNT(*) AS jml FROM kehadiran.absensi_notifikasi 
                WHERE penerima = ? AND (isread IS NULL OR isread = 0)
            ", array($penerimaid));

			return $query->first_row()->jml;
		} catch (Exception $e) {
			log_message('error', 'Error in getCountNotifUnread: ' . $e->getMessage());
			return 0;
		}
	}

	// Get total count of notifications
	function getCountNotif($penerimaid)
	{
		$this->db->where('penerima', $penerimaid);
		return $this->db->count_all_results("kehadiran.absensi_notifikasi");
	}

	// Get all notifications for a recipient with pagination
	function getAllNotification($penerimaid, $row)
	{
		try {
			$query = $this->db->query("
                SELECT * FROM kehadiran.vw_notif_absensi a
                WHERE a.penerima = ?
                ORDER BY a.tglnotif DESC
                OFFSET ? ROWS
                FETCH NEXT 25 ROWS ONLY;
            ", array($penerimaid, $row));

			return $query->result_array();
		} catch (Exception $e) {
			log_message('error', 'Error in getAllNotification: ' . $e->getMessage());
			return [];
		}
	}

	// Get all notifications specific to HR
	function getAllNotificationHR($penerimaid, $nik, $row)
	{
		try {
			$query = $this->db->query("
                SELECT * FROM kehadiran.vw_notif_absensi a
                WHERE a.penerima = ? AND a.nik = ?
                ORDER BY a.tglnotif DESC
                OFFSET ? ROWS
                FETCH NEXT 25 ROWS ONLY
            ", array($penerimaid, $nik, $row));

			return $query->result_array();
		} catch (Exception $e) {
			log_message('error', 'Error in getAllNotificationHR: ' . $e->getMessage());
			return [];
		}
	}

	// Update notification status to read
	function updateNotifRead($penerimaid)
	{
		try {
			$this->db->query("UPDATE kehadiran.absensi_notifikasi SET isread = 1 WHERE penerima = ?", array($penerimaid));
			return true;
		} catch (Exception $e) {
			log_message('error', 'Error in updateNotifRead: ' . $e->getMessage());
			return false;
		}
	}

	function getShortNotifHR($penerimaid, $nik)
	{
		$this->CI->load->database();
		$q = $this->CI->db->query("
			SELECT n.notifid, TO_CHAR(n.tglnotif, 'DD-MM-YYYY') tglnotif,
				n.jenisnotif, fnnamalengkap(p.namadepan, p.namabelakang) nama, p.nik
			FROM kehadiran.absensi_notifikasi n
			LEFT JOIN pegawai p ON n.pengirim = p.pegawaiid
			WHERE n.penerima = ? And p.nik = ?
			And n.jenisnotif NOT LIKE 'Pengajuan%'
			ORDER BY n.tglnotif DESC
			FETCH FIRST 5 ROWS ONLY
		", array($penerimaid, $nik));
		$this->CI->db->close();
		return $q->result_array();
	}

	// Get open dates
	function getOpenDate()
	{
		try {
			$query = $this->db->query("SELECT * FROM report.opendate");
			return $query->result_array();
		} catch (Exception $e) {
			log_message('error', 'Error in getOpenDate: ' . $e->getMessage());
			return [];
		}
	}

	// Get holidays
	function getHariLibur()
	{
		try {
			$query = $this->db->query("
                SELECT DISTINCT TO_CHAR(tgl, 'YYYY-MM-DD') AS tgl 
                FROM harilibur 
                WHERE tgl >= TO_DATE('01/01/2018', 'DD/MM/YYYY') 
                ORDER BY tgl ASC
            ");
			return $query->result_array();
		} catch (Exception $e) {
			log_message('error', 'Error in getHariLibur: ' . $e->getMessage());
			return [];
		}
	}

	// Get approver's information
	function getAppVer($pegawaiid)
	{
		// Ensure the input is not empty and is valid
		if (empty($pegawaiid) || !is_numeric($pegawaiid)) {
			log_message('error', 'Invalid pegawaiid provided to getAppVer: ' . print_r($pegawaiid, true));
			return []; // Return an empty array for invalid input
		}

		$this->load->database(); // Make sure the database library is loaded

		try {
			// Use a prepared statement to enhance security
			$query = $this->db->query("
            SELECT 
                a.id AS pegawaiid,
                b.nik,
                c.fullname AS nama,
                e.name AS jabatan,
                f.emailkantor AS email
            FROM pegawai a
            INNER JOIN users b ON b.id = a.userid
            INNER JOIN namapegawai c ON c.pegawaiid = a.id
            INNER JOIN struktur.satkerpegawai d ON d.pegawaiid = a.id
            INNER JOIN struktur.jabatan e ON e.id = d.jabatanid
            INNER JOIN datapegawai f ON f.pegawaiid = a.id
            WHERE a.id = ?
        ", [$pegawaiid]); // Use parameterized query

			return $query->result_array();
		} catch (Exception $e) {
			// Log the error message
			log_message('error', 'Error in getAppVer: ' . $e->getMessage());
			return []; // Return empty array on error
		}
	}


	// Get employee information
	public function getInfoPegawai($pegawaiid)
	{
		// Validate the input parameter
		if (!is_numeric($pegawaiid) || $pegawaiid <= 0) {
			log_message('error', 'Invalid pegawaiid provided: ' . $pegawaiid);
			return []; // Return empty array if parameter is invalid
		}

		try {
			// Prepare and execute the query, using parameter binding
			$result = $this->db->query("
				SELECT
					a.id AS pegawaiid,
					b.nik,
					c.fullname AS nama,
					e.name AS jabatanname,
					d.satkerid,
					g.unitkerja,
					g.direktorat,
					g.divisi,
					g.departemen,
					g.seksi,
					g.subseksi,
					i.id AS lokasiid,
					i.name AS lokasiname
				FROM pegawai a
				INNER JOIN users b ON b.id = a.userid
				INNER JOIN namapegawai c ON c.pegawaiid = a.id
				INNER JOIN struktur.satkerpegawai d ON d.pegawaiid = a.id
				INNER JOIN struktur.jabatan e ON e.id = d.jabatanid
				INNER JOIN struktur.levelgrade f ON f.id = d.levelid
				INNER JOIN struktur.vw_satkertree g ON g.id = d.satkerid
				INNER JOIN riwayatjabatan h ON h.pegawaiid = a.id
				INNER JOIN lokasi i ON i.id = h.lokasiid
				WHERE a.isaktif = 1
				AND a.id = ?;
        	", $pegawaiid);

			// Fetch result as an array
			$resultArray = $result->result_array();

			// Check if result is empty
			if (empty($resultArray)) {
				log_message('warning', 'No data found for pegawaiid: ' . $pegawaiid);
			}

			return $resultArray; // Return the result from the query
		} catch (Exception $e) {
			// Log the error message for debugging
			log_message('error', 'Error in getInfoPegawai: ' . $e->getMessage());
			// Return a standardized error response
			return ['error' => 'Unable to retrieve information.']; // Provide feedback on failure
		}
	}


	// Check attendance request
	function cekPengajuanKehadiran($params)
	{
		try {
			$query = $this->db->query("
                SELECT kehadiran.sp_cekpengajuanabsensi(?, ?, ?, ?) AS jml
            ", $params);
			return $query->first_row()->jml;
		} catch (Exception $e) {
			log_message('error', 'Error in cekPengajuanKehadiran: ' . $e->getMessage());
			return 0;
		}
	}

	// Add attendance request
	public function addPengajuanAbsensi($params)
	{
		try {
			// Call stored procedure
			$result = $this->tp_connpgsql->callSpReturn('kehadiran.sp_addpengajuanabsen', $params);
			// $result = 'OKE!';
			// Check if the result is false, meaning the query failed
			if ($result === false) {
				throw new Exception('Database query failed in addPengajuanAbsensi');
			}

			return $result;

		} catch (Exception $e) {
			// Log the error with additional debugging information
			log_message('error', 'Error in addPengajuanAbsensi: ' . $e->getMessage());
			return array();  // Return empty array on failure
		}
	}



	// Add logs
	function addLogs()
	{
		$params = array(
			'nik' => $this->session->userdata('username'),
			'url' => $_SERVER['REQUEST_URI'],
			'ipuser' => $_SERVER['REMOTE_ADDR'],
		);

		try {
			$this->db->query("
                INSERT INTO reporthris.userlogs VALUES (?, ?, NOW(), ?)", $params);
		} catch (Exception $e) {
			log_message('error', 'Error in addLogs: ' . $e->getMessage());
		}
	}
}