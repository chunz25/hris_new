<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_pengajuan extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_crott');
	}

	function getInfoCuti($pegawaiid)
	{
		// Initialize database connection and handle errors
		$this->load->database();
		if ($this->db->conn_id === FALSE) {
			log_message('error', 'Database connection failed.');
			return false;
		}

		try {
			// Safely run the query with bound parameters
			$sql = "
            SELECT COUNT(*) AS count 
            FROM kehadiran.cuti_hdr 
            WHERE status IN (2,3,5,9,10,12) 
            AND pegawaiid = ?
        ";
			$q = $this->db->query($sql, array($pegawaiid));

			// Check if the query was successful
			if (!$q) {
				throw new Exception('Query failed: ' . $this->db->_error_message());
			}

			$result = $q->first_row();
			return $result;
		} catch (Exception $e) {
			// Log the error for debugging purposes
			log_message('error', $e->getMessage());
			return false;
		} finally {
			// Close the database connection
			$this->db->close();
		}
	}

	function getcutiid($pegawaiid, $pengajuan)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT * FROM kehadiran.cuti_hdr a
			WHERE a.pegawaiid = '" . $pegawaiid . "'
			AND a.pengajuanid = '" . $pengajuan . "'
		");
		$this->db->close();

		return $q->result_array();
	}


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
        ", [(int) $pegawaiid]); // Use parameterized query

			return $query->result_array();
		} catch (Exception $e) {
			// Log the error message
			log_message('error', 'Error in getAppVer: ' . $e->getMessage());
			return []; // Return empty array on error
		}
	}

	function getStatusCuti($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 
				b.name as status 
			FROM kehadiran.cuti_hdr a
				LEFT JOIN kehadiran.cuti_status b on a.status = b.id
			WHERE a.status IN (2,3,5,9,10,12) 
				  AND a.pegawaiid = ?
		", array((int) $pegawaiid));
		$this->db->close();
		return $q->first_row();
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

	function getInfoSisaCuti($pegawaiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT 	* FROM kehadiran.vw_sisacuti
			WHERE pegawaiid = ?
			AND tahun = DATE_PART('YEAR',NOW())
		", $pegawaiid);

		$params = $q->result_array();
		$arr = array(
			'pegawaiid' => $pegawaiid,
			'jatahAwal' => $params[0]['saldo'],
			'saldoCY' => $params[0]['saldocy'],
			'saldoLY' => $params[0]['saldoly'],
		);

		return $arr;
	}

	function getInfoPegawai($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('public.sp_getinfopegawai', $params);
		return $mresult;
	}

	function getListRekan($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getrekanpelimpahan', $params);
		return $mresult['data'];
	}

	function getComboJenisCuti($jeniscutiid = '')
	{
		$this->load->database();
		$whereClause = '';
		if (!empty($jeniscutiid)) {
			$whereClause = " AND jeniscutiid = '" . $jeniscutiid . "' ";
		}
		$sql = "
			SELECT id, name AS text 
			FROM kehadiran.cuti_jenis_hdr 
			WHERE id NOT IN('6')			
			" . $whereClause . "
			ORDER BY id
		";

		$q = $this->db->query($sql);
		$this->db->close();
		return $q->result_array();
	}

	function getComboDetailJenisCuti($jeniscutiid)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT id AS id, name AS text, jatahcuti, 'HARI KERJA' as satuan 
			FROM kehadiran.cuti_jenis_dtl 
			WHERE hdrid = ?
			ORDER BY id
		", array($jeniscutiid));
		$this->db->close();
		return $q->result_array();
	}

	function cekPengajuanCuti($params)
	{
		$this->load->database();
		$q = $this->db->query("
			SELECT kehadiran.sp_cekpengajuancuti(?, ?, ?, ?) AS jml
		", $params);
		$this->db->close();
		return $q->first_row()->jml;
	}

	function addPengajuanCuti($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_addpengajuancuti', $params);
		return $mresult['firstrow'];
	}

	function addDetailPengajuanCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT kehadiran.sp_adddetailpengajuancuti(?,?,?,?,?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}

	function getCutiById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getcutibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('eservices.sp_getdetailpengajuancuti', array($pengajuanid));
		return $mresult['data'];
	}

	function updStatusCuti($params)
	{
		$this->load->database();
		$this->db->trans_start();
		$q = $this->db->query("
			SELECT kehadiran.sp_updstatuscuti(?,?,?,?,?)
		", $params);
		$this->db->trans_complete();
		$this->db->close();
		return $this->db->trans_status();
	}
}
