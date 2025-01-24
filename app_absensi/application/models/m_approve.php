<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class m_approve extends CI_Model
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

	public function getListApprovalCuti($params)
	{
		// die(var_dump($params));
		try {
			// Call the stored procedure and check if the result is valid
			$mresult = $this->tp_connpgsql->callSpCount('kehadiran.sp_getverifikasiabsensi', $params, false);

			// Return the result if it's valid, otherwise return an empty array
			if ($mresult !== false) {
				return $mresult;
			} else {
				throw new Exception('Stored procedure call failed.');
			}

		} catch (Exception $e) {
			// Log the error for debugging
			log_message('error', 'Error in getListApprovalCuti: ' . $e->getMessage());

			// Return an empty array on failure
			return array();
		}
	}

	function approvebulk($params)
	{
		$this->load->database();

		try {
			$this->db->trans_start();  // Start the transaction

			// Prepare and execute the query
			$result = $this->db->query("SELECT kehadiran.sp_approvebulkkehadiran(?)", array($params));

			if (!$result) {
				throw new Exception('Failed to execute stored procedure: ' . $this->db->_error_message());
			}

			$this->db->trans_complete();  // Complete the transaction
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Transaction failed');
			}

		} catch (Exception $e) {
			// Log the error message
			log_message('error', $e->getMessage());
			return false;  // Return false to indicate failure
		} finally {
			$this->db->close(); // Ensure the database is closed regardless of the result
		}

		return true; // Return true if everything is successful
	}

	function updStatusAbsensi($params)
	{
		$this->load->database();

		// Ensure the params have 4 elements as expected
		if (count($params) !== 4) {
			log_message('error', 'Invalid number of parameters provided to sp_updstatusverifikasi');
			return false;  // Return false if invalid parameters
		}

		try {
			$this->db->trans_start();  // Start the transaction

			// Prepare and execute the query with the stored procedure
			$result = $this->db->query("SELECT kehadiran.sp_updstatusabsensi(?, ?, ?, ?)", $params);

			if (!$result) {
				throw new Exception('Failed to execute stored procedure: ' . $this->db->_error_message());
			}

			$this->db->trans_complete();  // Complete the transaction
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Transaction failed');
			}

		} catch (Exception $e) {
			// Log the error message
			log_message('error', $e->getMessage());
			return false;  // Return false to indicate failure
		} finally {
			$this->db->close(); // Ensure the database is closed in all cases
		}

		return true; // Return true if everything is successful
	}


	function getCutiById($params)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getabsensibyid', $params);
		return $mresult['firstrow'];
	}

	function getDetailPengajuanCuti($pengajuanid)
	{
		$mresult = $this->tp_connpgsql->callSpReturn('kehadiran.sp_getdetailpengajuanabsensi', array($pengajuanid));
		return $mresult['data'];
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
}
